"""
Machine Learning models for stock price prediction

This module contains various ML models for stock price prediction
including LSTM, GRU, and ensemble models.
"""

import numpy as np
import pandas as pd
import pickle
import os
import logging
from typing import Dict, Any, List, Optional, Tuple
from datetime import datetime, timedelta

# ML libraries
from sklearn.ensemble import RandomForestRegressor
from sklearn.preprocessing import MinMaxScaler, StandardScaler
import joblib

# Configure logging
logger = logging.getLogger(__name__)

# Define model directory
MODEL_DIR = os.path.join(os.path.dirname(os.path.dirname(__file__)), "models")
if not os.path.exists(MODEL_DIR):
    os.makedirs(MODEL_DIR)

class StockPricePredictor:
    """Stock price prediction model using machine learning"""
    
    def __init__(self, ticker: str, model_type: str = "random_forest", sequence_length: int = 60):
        """
        Initialize stock price predictor
        
        Args:
            ticker: Stock ticker symbol
            model_type: Type of model to use (random_forest, arima, xgboost)
            sequence_length: Length of input sequences
        """
        self.ticker = ticker
        self.model_type = model_type
        self.sequence_length = sequence_length
        self.model = None
        self.model_path = os.path.join(MODEL_DIR, f"{ticker}_{model_type}_model.joblib")
        self.metadata_path = os.path.join(MODEL_DIR, f"{ticker}_{model_type}_metadata.pkl")
        self.metadata = {}
        
        # Scalers
        self.price_scaler = None
        self.feature_scaler = None
    
    def build_model(self, n_features: int) -> None:
        """
        Build ML model
        
        Args:
            n_features: Number of features
        """
        if self.model_type == "random_forest":
            self.model = RandomForestRegressor(
                n_estimators=100,
                max_depth=20,
                min_samples_split=5,
                min_samples_leaf=2,
                random_state=42,
                n_jobs=-1
            )
        # Future models can be added here (XGBoost, ARIMA, etc.)
        else:
            raise ValueError(f"Unknown model type: {self.model_type}")
            
        logger.info(f"Built {self.model_type} model for {self.ticker}")
    
    def create_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create technical indicators and features from price data
        
        Args:
            df: DataFrame with OHLCV data
            
        Returns:
            DataFrame with additional features
        """
        # Make a copy to avoid modifying the original
        df = df.copy()
        
        # Price-based features
        df['Return'] = df['close'].pct_change()
        df['Range'] = (df['high'] - df['low']) / df['close']
        df['LogReturn'] = np.log(df['close'] / df['close'].shift(1))
        
        # Moving averages
        for window in [5, 10, 20, 50]:
            df[f'MA_{window}'] = df['close'].rolling(window=window).mean()
            df[f'MA_ratio_{window}'] = df['close'] / df[f'MA_{window}']
        
        # Volatility
        for window in [5, 10, 20]:
            df[f'Volatility_{window}'] = df['LogReturn'].rolling(window=window).std()
        
        # Volume features
        df['Volume_MA_5'] = df['volume'].rolling(window=5).mean()
        df['Volume_Change'] = df['volume'].pct_change()
        df['Volume_MA_Ratio'] = df['volume'] / df['Volume_MA_5']
        
        # Price momentum
        for window in [1, 5, 10]:
            df[f'Momentum_{window}'] = df['close'] - df['close'].shift(window)
        
        # RSI - Relative Strength Index
        delta = df['close'].diff()
        gain = delta.where(delta > 0, 0).rolling(window=14).mean()
        loss = -delta.where(delta < 0, 0).rolling(window=14).mean()
        rs = gain / loss
        df['RSI'] = 100 - (100 / (1 + rs))
        
        # MACD - Moving Average Convergence Divergence
        ema12 = df['close'].ewm(span=12, adjust=False).mean()
        ema26 = df['close'].ewm(span=26, adjust=False).mean()
        df['MACD'] = ema12 - ema26
        df['MACD_Signal'] = df['MACD'].ewm(span=9, adjust=False).mean()
        df['MACD_Hist'] = df['MACD'] - df['MACD_Signal']
        
        # Bollinger Bands
        df['BB_Middle'] = df['close'].rolling(window=20).mean()
        df['BB_Std'] = df['close'].rolling(window=20).std()
        df['BB_Width'] = (df['BB_Middle'] + (df['BB_Std'] * 2) - (df['BB_Middle'] - (df['BB_Std'] * 2))) / df['BB_Middle']
        df['BB_Position'] = (df['close'] - (df['BB_Middle'] - (df['BB_Std'] * 2))) / (
            (df['BB_Middle'] + (df['BB_Std'] * 2)) - (df['BB_Middle'] - (df['BB_Std'] * 2)))
        
        # Create target variable (next day's close price)
        df['Target'] = df['close'].shift(-1)
        
        return df
    
    def prepare_data(self, df: pd.DataFrame) -> Dict[str, Any]:
        """
        Prepare data for machine learning models
        
        Args:
            df: DataFrame with OHLCV data
            
        Returns:
            Dict containing processed data arrays and metadata
        """
        # Add features
        df_features = self.create_features(df)
        
        # Drop rows with NaN values
        df_features = df_features.dropna()
        
        # Extract dates if available
        dates = None
        if 'date' in df_features.columns:
            dates = df_features['date']
            df_features = df_features.drop('date', axis=1)
        
        # Get target variable (next day's price)
        y = df_features['Target'].values
        
        # Drop target and any non-feature columns from X
        drop_cols = ['Target', 'open', 'high', 'low', 'close', 'volume', 'adjclose']
        feature_cols = [col for col in df_features.columns if col not in drop_cols]
        X = df_features[feature_cols].values
        
        # Scale the target (price)
        self.price_scaler = MinMaxScaler()
        y_scaled = self.price_scaler.fit_transform(y.reshape(-1, 1)).flatten()
        
        # Scale the features
        self.feature_scaler = StandardScaler()
        X_scaled = self.feature_scaler.fit_transform(X)
        
        # Split into train/test data - use the last 20% for testing
        split_idx = int(len(X_scaled) * 0.8)
        X_train, X_test = X_scaled[:split_idx], X_scaled[split_idx:]
        y_train, y_test = y_scaled[:split_idx], y_scaled[split_idx:]
        
        # For time series forecasting models that need sequences
        if self.model_type in ["lstm", "gru", "nn"]:
            # To be implemented with TensorFlow if needed
            pass
        
        return {
            'X_train': X_train,
            'X_test': X_test,
            'y_train': y_train,
            'y_test': y_test,
            'feature_columns': feature_cols,
            'dates': dates,
            'price_scaler': self.price_scaler,
            'feature_scaler': self.feature_scaler,
            'last_features': X_scaled[-1].reshape(1, -1)  # Last feature set for prediction
        }
    
    def train(self, df: pd.DataFrame) -> Dict[str, Any]:
        """
        Train the model on stock data
        
        Args:
            df: DataFrame with OHLCV data
            
        Returns:
            Dict with training results and evaluation metrics
        """
        try:
            # Prepare data
            data = self.prepare_data(df)
            X_train, y_train = data['X_train'], data['y_train']
            X_test, y_test = data['X_test'], data['y_test']
            
            # Build model if needed
            if self.model is None:
                self.build_model(X_train.shape[1])
            
            # Train the model
            self.model.fit(X_train, y_train)
            
            # Make predictions
            y_pred = self.model.predict(X_test)
            
            # Evaluate
            metrics = self.evaluate_predictions(y_test, y_pred)
            
            # Save model
            joblib.dump(self.model, self.model_path)
            
            # Save metadata
            self.metadata = {
                'ticker': self.ticker,
                'model_type': self.model_type,
                'feature_columns': data['feature_columns'],
                'metrics': metrics,
                'last_train_date': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
                'price_scaler': self.price_scaler,
                'feature_scaler': self.feature_scaler,
                'last_features': data['last_features']
            }
            
            with open(self.metadata_path, 'wb') as f:
                pickle.dump(self.metadata, f)
                
            logger.info(f"Trained {self.model_type} model for {self.ticker} with RMSE: {metrics['rmse']:.4f}")
            
            return {
                'metrics': metrics,
                'model_path': self.model_path,
                'feature_importance': self.get_feature_importance() if hasattr(self.model, 'feature_importances_') else None
            }
        
        except Exception as e:
            logger.error(f"Error training model for {self.ticker}: {str(e)}")
            raise
    
    def predict_next_days(self, df: pd.DataFrame, days: int = 7) -> Dict[str, Any]:
        """
        Make predictions for the next specified days
        
        Args:
            df: DataFrame with OHLCV data
            days: Number of days to predict ahead
            
        Returns:
            Dict with predictions and metadata
        """
        try:
            # Check if model exists
            if not os.path.exists(self.model_path) or not os.path.exists(self.metadata_path):
                raise FileNotFoundError(f"Model for {self.ticker} not found. Please train first.")
                
            # Load model if not loaded
            if self.model is None:
                self.model = joblib.load(self.model_path)
                with open(self.metadata_path, 'rb') as f:
                    self.metadata = pickle.load(f)
                
                # Restore scalers
                self.price_scaler = self.metadata['price_scaler']
                self.feature_scaler = self.metadata['feature_scaler']
            
            # Create features from the input data
            df_features = self.create_features(df.copy())
            df_features = df_features.dropna()
            
            # Get the last date for future predictions
            last_date = pd.to_datetime(df['date'].iloc[-1])
            
            # Make predictions for future days
            future_prices = []
            future_dates = []
            
            # For each future day
            current_data = df.copy()
            
            for i in range(days):
                # Create features for the current data
                current_features = self.create_features(current_data)
                current_features = current_features.dropna()
                
                # Extract the last row's features
                drop_cols = ['Target', 'open', 'high', 'low', 'close', 'volume', 'adjclose', 'date']
                feature_cols = [col for col in current_features.columns if col not in drop_cols]
                last_features = current_features[feature_cols].iloc[-1].values.reshape(1, -1)
                
                # Scale the features
                last_features_scaled = self.feature_scaler.transform(last_features)
                
                # Predict the next day's price
                next_price_scaled = self.model.predict(last_features_scaled)
                
                # Transform back to original scale
                next_price = self.price_scaler.inverse_transform(
                    next_price_scaled.reshape(-1, 1))[0][0]
                
                # Advance date (skip weekends)
                next_date = last_date + timedelta(days=1)
                while next_date.weekday() > 4:  # Skip Saturday and Sunday
                    next_date = next_date + timedelta(days=1)
                
                # Add predictions to results
                future_prices.append(float(next_price))
                future_dates.append(next_date.strftime('%Y-%m-%d'))
                
                # Create a synthetic record for the next day to use in the next iteration
                next_row = {
                    'date': next_date,
                    'open': float(current_data['close'].iloc[-1]),  # Use last close as next open
                    'close': float(next_price),
                    'high': float(max(next_price, current_data['close'].iloc[-1] * 1.01)),  # Estimated high
                    'low': float(min(next_price, current_data['close'].iloc[-1] * 0.99)),   # Estimated low
                    'volume': float(current_data['volume'].iloc[-1]),  # Reuse last volume
                    'adjclose': float(next_price)
                }
                
                # Append to data for next iteration
                current_data = pd.concat([
                    current_data,
                    pd.DataFrame([next_row])
                ]).reset_index(drop=True)
                
                # Update last_date for next iteration
                last_date = next_date
            
            return {
                'ticker': self.ticker,
                'predictions': future_prices,
                'dates': future_dates,
                'model_type': self.model_type,
                'last_actual_price': float(df['close'].iloc[-1]),
                'last_actual_date': df['date'].iloc[-1].strftime('%Y-%m-%d') 
                    if isinstance(df['date'].iloc[-1], pd.Timestamp) 
                    else df['date'].iloc[-1]
            }
        
        except Exception as e:
            logger.error(f"Error making predictions for {self.ticker}: {str(e)}")
            raise
    
    def evaluate_predictions(self, y_true: np.ndarray, y_pred: np.ndarray) -> Dict[str, float]:
        """
        Calculate evaluation metrics for predictions
        
        Args:
            y_true: True values (scaled)
            y_pred: Predicted values (scaled)
            
        Returns:
            Dict of metric names and values
        """
        from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
        
        # Original scale metrics
        y_true_orig = self.price_scaler.inverse_transform(y_true.reshape(-1, 1)).flatten()
        y_pred_orig = self.price_scaler.inverse_transform(y_pred.reshape(-1, 1)).flatten()
        
        # Calculate metrics
        mse = mean_squared_error(y_true_orig, y_pred_orig)
        rmse = np.sqrt(mse)
        mae = mean_absolute_error(y_true_orig, y_pred_orig)
        r2 = r2_score(y_true_orig, y_pred_orig)
        
        # Direction accuracy
        true_direction = np.sign(np.diff(np.append([0], y_true_orig)))
        pred_direction = np.sign(np.diff(np.append([0], y_pred_orig)))
        direction_match = true_direction == pred_direction
        direction_accuracy = np.mean(direction_match)
        
        return {
            'mse': float(mse),
            'rmse': float(rmse),
            'mae': float(mae),
            'r2': float(r2),
            'direction_accuracy': float(direction_accuracy)
        }
    
    def get_feature_importance(self) -> Dict[str, float]:
        """
        Get feature importance from the model
        
        Returns:
            Dict mapping feature names to importance scores
        """
        if not hasattr(self.model, 'feature_importances_'):
            return {}
            
        feature_cols = self.metadata.get('feature_columns', [])
        
        if not feature_cols:
            return {}
            
        importance_dict = dict(zip(feature_cols, self.model.feature_importances_))
        
        # Sort by importance (descending)
        return {k: float(v) for k, v in sorted(
            importance_dict.items(), key=lambda item: item[1], reverse=True)}
    
    @classmethod
    def load_or_create(cls, ticker: str, model_type: str = "random_forest") -> 'StockPricePredictor':
        """
        Load existing model or create a new one
        
        Args:
            ticker: Stock ticker symbol
            model_type: Type of model to use
            
        Returns:
            StockPricePredictor instance
        """
        predictor = cls(ticker, model_type)
        
        # Check if model exists
        if os.path.exists(predictor.model_path) and os.path.exists(predictor.metadata_path):
            # Load model
            predictor.model = joblib.load(predictor.model_path)
            with open(predictor.metadata_path, 'rb') as f:
                predictor.metadata = pickle.load(f)
            
            # Restore scalers
            predictor.price_scaler = predictor.metadata['price_scaler']
            predictor.feature_scaler = predictor.metadata['feature_scaler']
            
            logger.info(f"Loaded existing {model_type} model for {ticker}")
        
        return predictor


# Factory function to get predictor
def get_stock_predictor(ticker: str, model_type: str = "random_forest") -> StockPricePredictor:
    """Get stock predictor instance"""
    return StockPricePredictor.load_or_create(ticker, model_type)
