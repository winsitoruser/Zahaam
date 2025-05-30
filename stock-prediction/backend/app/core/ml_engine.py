"""
Machine Learning engine for ZAHAAM big data system.
Handles model training, evaluation, and prediction.
"""
import logging
import pandas as pd
import numpy as np
from typing import Dict, List, Any, Optional, Tuple, Union
from datetime import datetime, timedelta
import asyncio
from sqlalchemy.orm import Session
import joblib
import os
import json
from sklearn.preprocessing import StandardScaler, MinMaxScaler
from sklearn.model_selection import train_test_split, TimeSeriesSplit
from sklearn.linear_model import LinearRegression, Ridge, Lasso
from sklearn.ensemble import RandomForestRegressor, GradientBoostingRegressor
from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
# TensorFlow import - make it optional
try:
    import tensorflow as tf
    from tensorflow.keras.models import Sequential, load_model, Model
    from tensorflow.keras.layers import Dense, LSTM, Dropout, Input, Concatenate
    from tensorflow.keras.optimizers import Adam
    from tensorflow.keras.callbacks import EarlyStopping, ModelCheckpoint
    TENSORFLOW_AVAILABLE = True
except ImportError:
    # TensorFlow not available, using alternative models
    print("TensorFlow not available, using scikit-learn models only")
    TENSORFLOW_AVAILABLE = False

from app.models.stocks import Stock, StockPrice, StockIndicator
from app.models.big_data import (
    MLModel, StockPrediction, StockMetrics, DataProcessingJob
)
from app.core.data_processor import get_data_processor

logger = logging.getLogger(__name__)

# Directory to store model files
MODEL_DIR = os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(__file__))), 'models')
os.makedirs(MODEL_DIR, exist_ok=True)

class MLEngine:
    """Machine learning engine for stock prediction"""
    
    def __init__(self, db: Session):
        self.db = db
        self.data_processor = get_data_processor(db)
        
    async def prepare_features(self, ticker: str, days: int = 365, prediction_horizon: int = 5, interval: str = "1d") -> Tuple[pd.DataFrame, bool]:
        """
        Prepare features for model training and prediction
        
        Args:
            ticker: Stock ticker symbol
            days: Number of days of historical data to use
            prediction_horizon: Number of time units to predict into the future
            interval: Data interval (1m, 5m, 15m, 30m, 1h, 1d, 1wk, 1mo)
            
        Returns:
            Tuple of (features DataFrame, success flag)
        """
        try:
            # Process stock data to ensure we have the latest
            df, success = await self.data_processor.process_stock_data(ticker, days)
            if not success or df.empty:
                logger.error(f"Failed to process data for {ticker}")
                return pd.DataFrame(), False
                
            # Create target variable: future price movement
            # For regression models, predict the actual price n days in the future
            df['Target_Price'] = df['Close'].shift(-prediction_horizon)
            
            # For classification models, predict the direction (up/down)
            df['Target_Direction'] = (df['Target_Price'] > df['Close']).astype(int)
            
            # Calculate the percent change for target
            df['Target_Percent_Change'] = (df['Target_Price'] / df['Close'] - 1) * 100
            
            # Drop rows with NaN targets (at the end of the dataframe)
            df = df.dropna(subset=['Target_Price', 'Target_Direction', 'Target_Percent_Change'])
            
            # Create feature sets
            features = self._create_feature_set(df)
            
            return features, True
            
        except Exception as e:
            logger.error(f"Error preparing features for {ticker}: {str(e)}")
            return pd.DataFrame(), False
    
    def _create_feature_set(self, df: pd.DataFrame) -> pd.DataFrame:
        """Create a comprehensive feature set for machine learning"""
        # Start with a copy of the dataframe
        features = df.copy()
        
        # List of technical indicators to use as features
        indicator_columns = [
            'SMA_5', 'SMA_10', 'SMA_20', 'SMA_50', 'SMA_100', 'SMA_200',
            'EMA_5', 'EMA_10', 'EMA_20', 'EMA_50', 'EMA_100', 'EMA_200',
            'RSI', 'MACD', 'MACD_Signal', 'MACD_Histogram',
            'BB_Upper_20', 'BB_Middle_20', 'BB_Lower_20',
            '%K', '%D', 'ATR', 'OBV', 'MFI',
            'Volatility_20', 'Volume_MA_20', 'Relative_Volume'
        ]
        
        # Price-derived features
        features['Price_SMA_20_Ratio'] = features['Close'] / features['SMA_20']
        features['Price_SMA_50_Ratio'] = features['Close'] / features['SMA_50']
        features['Price_SMA_200_Ratio'] = features['Close'] / features['SMA_200']
        
        # Momentum features
        for period in [5, 10, 20, 50]:
            features[f'Momentum_{period}'] = features['Close'] / features['Close'].shift(period) - 1
        
        # Volatility features
        features['High_Low_Range'] = (features['High'] - features['Low']) / features['Close']
        features['Daily_Return_Abs'] = abs(features['Return'])
        
        # Volume features
        features['Volume_Price_Ratio'] = features['Volume'] / features['Close']
        features['Dollar_Volume'] = features['Volume'] * features['Close']
        
        # Price patterns
        features['Higher_High'] = ((features['High'] > features['High'].shift(1)) & 
                                  (features['High'].shift(1) > features['High'].shift(2))).astype(int)
        features['Lower_Low'] = ((features['Low'] < features['Low'].shift(1)) & 
                                (features['Low'].shift(1) < features['Low'].shift(2))).astype(int)
        
        # Trend features
        features['Uptrend'] = ((features['SMA_20'] > features['SMA_50']) & 
                              (features['SMA_50'] > features['SMA_200'])).astype(int)
        features['Downtrend'] = ((features['SMA_20'] < features['SMA_50']) & 
                                (features['SMA_50'] < features['SMA_200'])).astype(int)
        
        # Distance features
        features['BB_Position'] = (features['Close'] - features['BB_Lower_20']) / (features['BB_Upper_20'] - features['BB_Lower_20'])
        
        # Lag features (previous days values)
        for lag in range(1, 6):
            features[f'Close_Lag_{lag}'] = features['Close'].shift(lag)
            features[f'Return_Lag_{lag}'] = features['Return'].shift(lag)
            features[f'Volume_Lag_{lag}'] = features['Volume'].shift(lag)
        
        # Moving average crossovers
        features['SMA_20_50_Crossover'] = ((features['SMA_20'] > features['SMA_50']) & 
                                         (features['SMA_20'].shift() <= features['SMA_50'].shift())).astype(int)
        features['SMA_50_200_Crossover'] = ((features['SMA_50'] > features['SMA_200']) & 
                                          (features['SMA_50'].shift() <= features['SMA_200'].shift())).astype(int)
        
        # RSI conditions
        features['RSI_Oversold'] = (features['RSI'] < 30).astype(int)
        features['RSI_Overbought'] = (features['RSI'] > 70).astype(int)
        
        # MACD conditions
        features['MACD_Crossover'] = ((features['MACD'] > features['MACD_Signal']) & 
                                    (features['MACD'].shift() <= features['MACD_Signal'].shift())).astype(int)
        features['MACD_Crossunder'] = ((features['MACD'] < features['MACD_Signal']) & 
                                     (features['MACD'].shift() >= features['MACD_Signal'].shift())).astype(int)
        
        # Drop NA values
        features = features.dropna()
        
        # Add date-based features
        features['DayOfWeek'] = features['Date'].dt.dayofweek
        features['Month'] = features['Date'].dt.month
        features['Year'] = features['Date'].dt.year
        features['DayOfMonth'] = features['Date'].dt.day
        features['Quarter'] = features['Date'].dt.quarter
        
        # One-hot encode categorical features
        for col in ['DayOfWeek', 'Month', 'Quarter']:
            dummies = pd.get_dummies(features[col], prefix=col)
            features = pd.concat([features, dummies], axis=1)
            
        return features
    
    async def train_ml_model(self, ticker: str, model_type: str = 'lstm', days: int = 500, interval: str = '1d', prediction_horizon: int = 5) -> Tuple[str, bool]:
        """
        Train a machine learning model for stock prediction
        
        Args:
            ticker: Stock ticker symbol
            model_type: Type of model to train ('linear', 'forest', 'gbm', 'lstm')
            days: Number of days of historical data to use
            
        Returns:
            Tuple of (model_id, success flag)
        """
        try:
            # Create a training job record
            job = DataProcessingJob(
                job_type="model_training",
                status="running",
                parameters={"ticker": ticker, "model_type": model_type, "days": days}
            )
            self.db.add(job)
            self.db.commit()
            
            # Prepare features
            features, success = await self.prepare_features(ticker, days, prediction_horizon, interval)
            if not success or features.empty:
                job.status = "failed"
                job.error_message = f"Failed to prepare features for {ticker}"
                job.completed_at = datetime.now()
                self.db.commit()
                return "", False
            
            # Determine the target variable based on model type
            if model_type in ['lstm', 'linear', 'forest', 'gbm']:
                target_col = 'Target_Price'
            else:
                target_col = 'Target_Direction'  # Classification target
                
            # Select features and target
            X = features.drop(['Date', 'Target_Price', 'Target_Direction', 'Target_Percent_Change'], axis=1)
            y = features[target_col]
            
            # Handle non-numeric columns
            X = X.select_dtypes(include=['number'])
            
            # Train-test split
            X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, shuffle=False)
            
            # Scale the data
            scaler_X = StandardScaler()
            X_train_scaled = scaler_X.fit_transform(X_train)
            X_test_scaled = scaler_X.transform(X_test)
            
            scaler_y = StandardScaler()
            y_train_scaled = scaler_y.fit_transform(y_train.values.reshape(-1, 1)).flatten()
            y_test_scaled = scaler_y.transform(y_test.values.reshape(-1, 1)).flatten()
            
            # Train the model based on the specified type
            model, model_path, metrics = None, "", {}
            
            if model_type == 'linear':
                model, model_path, metrics = self._train_linear_model(
                    X_train_scaled, y_train_scaled, X_test_scaled, y_test_scaled, ticker
                )
            elif model_type == 'forest':
                model, model_path, metrics = self._train_random_forest(
                    X_train_scaled, y_train_scaled, X_test_scaled, y_test_scaled, ticker
                )
            elif model_type == 'gbm':
                model, model_path, metrics = self._train_gradient_boosting(
                    X_train_scaled, y_train_scaled, X_test_scaled, y_test_scaled, ticker
                )
            elif model_type == 'lstm':
                # Reshape data for LSTM [samples, time steps, features]
                X_train_lstm = X_train_scaled.reshape((X_train_scaled.shape[0], 1, X_train_scaled.shape[1]))
                X_test_lstm = X_test_scaled.reshape((X_test_scaled.shape[0], 1, X_test_scaled.shape[1]))
                
                model, model_path, metrics = self._train_lstm_model(
                    X_train_lstm, y_train_scaled, X_test_lstm, y_test_scaled, ticker
                )
            else:
                raise ValueError(f"Unsupported model type: {model_type}")
                
            if not model_path:
                job.status = "failed"
                job.error_message = f"Failed to train model for {ticker}"
                job.completed_at = datetime.now()
                self.db.commit()
                return "", False
                
            # Save scalers
            scaler_paths = self._save_scalers(ticker, scaler_X, scaler_y)
            
            # Create ML model record in database
            ml_model = MLModel(
                name=f"{ticker}_{model_type}_{interval}_{prediction_horizon}",
                description=f"{model_type.upper()} model for predicting {ticker} stock price {prediction_horizon} {interval} ahead",
                model_type=model_type,
                target="price",
                version="1.0",
                metrics=metrics,
                parameters={
                    "days": days, 
                    "interval": interval,
                    "prediction_horizon": prediction_horizon,
                    "scaler_paths": scaler_paths
                },
                is_active=True
            )
            self.db.add(ml_model)
            self.db.commit()
            self.db.refresh(ml_model)
            
            # Update the job status
            job.status = "completed"
            job.completed_at = datetime.now()
            job.results = {"model_id": ml_model.id, "metrics": metrics}
            self.db.commit()
            
            logger.info(f"Successfully trained {model_type} model for {ticker}")
            return str(ml_model.id), True
            
        except Exception as e:
            logger.error(f"Error training model for {ticker}: {str(e)}")
            if 'job' in locals():
                job.status = "failed"
                job.error_message = str(e)
                job.completed_at = datetime.now()
                self.db.commit()
            return "", False
    
    def _train_linear_model(self, X_train, y_train, X_test, y_test, ticker):
        """Train a linear regression model"""
        try:
            # Create and train the model
            model = Ridge(alpha=1.0)
            model.fit(X_train, y_train)
            
            # Evaluate the model
            y_pred = model.predict(X_test)
            mse = mean_squared_error(y_test, y_pred)
            mae = mean_absolute_error(y_test, y_pred)
            r2 = r2_score(y_test, y_pred)
            
            metrics = {
                "mse": float(mse),
                "mae": float(mae),
                "r2": float(r2),
                "rmse": float(np.sqrt(mse))
            }
            
            # Save the model
            model_path = os.path.join(MODEL_DIR, f"{ticker}_linear.joblib")
            joblib.dump(model, model_path)
            
            return model, model_path, metrics
            
        except Exception as e:
            logger.error(f"Error training linear model: {str(e)}")
            return None, "", {}
    
    def _train_random_forest(self, X_train, y_train, X_test, y_test, ticker):
        """Train a random forest regression model"""
        try:
            # Create and train the model
            model = RandomForestRegressor(n_estimators=100, random_state=42)
            model.fit(X_train, y_train)
            
            # Evaluate the model
            y_pred = model.predict(X_test)
            mse = mean_squared_error(y_test, y_pred)
            mae = mean_absolute_error(y_test, y_pred)
            r2 = r2_score(y_test, y_pred)
            
            metrics = {
                "mse": float(mse),
                "mae": float(mae),
                "r2": float(r2),
                "rmse": float(np.sqrt(mse))
            }
            
            # Save the model
            model_path = os.path.join(MODEL_DIR, f"{ticker}_forest.joblib")
            joblib.dump(model, model_path)
            
            return model, model_path, metrics
            
        except Exception as e:
            logger.error(f"Error training random forest model: {str(e)}")
            return None, "", {}
    
    def _train_gradient_boosting(self, X_train, y_train, X_test, y_test, ticker):
        """Train a gradient boosting regression model"""
        try:
            # Create and train the model
            model = GradientBoostingRegressor(n_estimators=100, learning_rate=0.1, random_state=42)
            model.fit(X_train, y_train)
            
            # Evaluate the model
            y_pred = model.predict(X_test)
            mse = mean_squared_error(y_test, y_pred)
            mae = mean_absolute_error(y_test, y_pred)
            r2 = r2_score(y_test, y_pred)
            
            metrics = {
                "mse": float(mse),
                "mae": float(mae),
                "r2": float(r2),
                "rmse": float(np.sqrt(mse))
            }
            
            # Save the model
            model_path = os.path.join(MODEL_DIR, f"{ticker}_gbm.joblib")
            joblib.dump(model, model_path)
            
            return model, model_path, metrics
            
        except Exception as e:
            logger.error(f"Error training gradient boosting model: {str(e)}")
            return None, "", {}
    
    def _train_lstm_model(self, X_train, y_train, X_test, y_test, ticker):
        """Train an LSTM deep learning model"""
        try:
            # Create and train the model
            model = Sequential()
            model.add(LSTM(50, return_sequences=True, input_shape=(X_train.shape[1], X_train.shape[2])))
            model.add(Dropout(0.2))
            model.add(LSTM(50, return_sequences=False))
            model.add(Dropout(0.2))
            model.add(Dense(25))
            model.add(Dense(1))
            
            model.compile(optimizer='adam', loss='mean_squared_error')
            
            # Early stopping to prevent overfitting
            early_stopping = EarlyStopping(monitor='val_loss', patience=10, restore_best_weights=True)
            
            # Train the model
            history = model.fit(
                X_train, y_train,
                epochs=100,
                batch_size=32,
                validation_data=(X_test, y_test),
                callbacks=[early_stopping],
                verbose=0
            )
            
            # Evaluate the model
            loss = model.evaluate(X_test, y_test, verbose=0)
            y_pred = model.predict(X_test)
            mse = mean_squared_error(y_test, y_pred)
            mae = mean_absolute_error(y_test, y_pred.flatten())
            
            # R2 might not be directly available from keras
            r2 = r2_score(y_test, y_pred.flatten())
            
            metrics = {
                "mse": float(mse),
                "mae": float(mae),
                "r2": float(r2),
                "rmse": float(np.sqrt(mse)),
                "loss": float(loss)
            }
            
            # Save the model
            model_path = os.path.join(MODEL_DIR, f"{ticker}_lstm.h5")
            model.save(model_path)
            
            return model, model_path, metrics
            
        except Exception as e:
            logger.error(f"Error training LSTM model: {str(e)}")
            return None, "", {}
    
    def _save_scalers(self, ticker, scaler_X, scaler_y):
        """Save the scalers for later use in predictions"""
        try:
            scaler_X_path = os.path.join(MODEL_DIR, f"{ticker}_scaler_X.joblib")
            scaler_y_path = os.path.join(MODEL_DIR, f"{ticker}_scaler_y.joblib")
            
            joblib.dump(scaler_X, scaler_X_path)
            joblib.dump(scaler_y, scaler_y_path)
            
            return {"X": scaler_X_path, "y": scaler_y_path}
            
        except Exception as e:
            logger.error(f"Error saving scalers: {str(e)}")
            return {}
    
    async def generate_prediction(self, ticker: str, model_id: Optional[str] = None, interval: str = '1d', prediction_horizon: Optional[int] = None) -> Dict[str, Any]:
        """
        Generate predictions for a stock using trained model
        
        Args:
            ticker: Stock ticker symbol
            model_id: ID of the model to use, or None to use the best available model
            
        Returns:
            Dictionary with prediction results
        """
        try:
            # If no model_id specified, find the best model for this ticker and timeframe
            if not model_id:
                query = (self.db.query(MLModel)
                         .filter(MLModel.is_active == True)
                         .order_by(MLModel.created_at.desc()))
                
                # If interval and prediction_horizon are specified, try to find a matching model
                if interval and prediction_horizon:
                    ml_model = query.filter(MLModel.name == f"{ticker}_{interval}_{prediction_horizon}").first()
                    
                    # If no exact match, try to find a model with the same interval
                    if not ml_model and interval:
                        ml_model = query.filter(MLModel.name.like(f"{ticker}_%_{interval}_%")).first()
                
                # If still no model found, get any model for this ticker
                if not ml_model:
                    ml_model = query.filter(MLModel.name.like(f"{ticker}_%")).first()
                
                if not ml_model:
                    logger.warning(f"No active model found for {ticker}, training a new one")
                    model_id, success = await self.train_ml_model(ticker, interval=interval, prediction_horizon=prediction_horizon or 5)
                    if not success:
                        return {"error": f"Failed to train model for {ticker}"}
                else:
                    model_id = str(ml_model.id)
                    # Extract model parameters
                    if not prediction_horizon and 'prediction_horizon' in ml_model.parameters:
                        prediction_horizon = ml_model.parameters['prediction_horizon']
                    if not interval and 'interval' in ml_model.parameters:
                        interval = ml_model.parameters['interval']
            
            # Get the model record
            ml_model = self.db.query(MLModel).filter(MLModel.id == int(model_id)).first()
            if not ml_model:
                return {"error": f"Model with ID {model_id} not found"}
                
            # Get the model type
            model_type = ml_model.model_type
            
            # Process latest data
            features, success = await self.prepare_features(ticker, interval=interval, prediction_horizon=prediction_horizon or 5)
            if not success or features.empty:
                return {"error": f"Failed to prepare features for {ticker}"}
                
            # Get the latest data point for prediction
            latest_data = features.iloc[-1:].copy()
            X = latest_data.drop(['Date', 'Target_Price', 'Target_Direction', 'Target_Percent_Change'], axis=1)
            X = X.select_dtypes(include=['number'])
            
            # Load scalers
            scaler_paths = ml_model.parameters.get('scaler_paths', {})
            if not scaler_paths:
                return {"error": "Scaler paths not found in model parameters"}
                
            scaler_X = joblib.load(scaler_paths['X'])
            scaler_y = joblib.load(scaler_paths['y'])
            
            # Scale the input data
            X_scaled = scaler_X.transform(X)
            
            # Load the model and generate prediction
            prediction_result = self._predict_with_model(model_type, ticker, X_scaled, scaler_y)
            if 'error' in prediction_result:
                return prediction_result
                
            # Get latest price for reference
            latest_price = features.iloc[-1]['Close']
            predicted_price = prediction_result['predicted_price']
            
            # Calculate percent change
            percent_change = (predicted_price / latest_price - 1) * 100
            
            # Determine trading signal based on predicted change
            signal = "HOLD"
            signal_strength = 0.5
            
            if percent_change > 2:
                signal = "BUY"
                signal_strength = min(0.9, 0.5 + abs(percent_change) / 20)
            elif percent_change < -2:
                signal = "SELL"
                signal_strength = min(0.9, 0.5 + abs(percent_change) / 20)
                
            # Calculate confidence interval
            confidence = prediction_result.get('confidence', 0.8)
            mse = ml_model.metrics.get('mse', 0)
            rmse = np.sqrt(mse) if mse else prediction_result.get('rmse', 0)
            
            upper_bound = predicted_price + 1.96 * rmse
            lower_bound = predicted_price - 1.96 * rmse
            
            # Save prediction to database
            stock = self.db.query(Stock).filter(Stock.ticker == ticker).first()
            if stock:
                # Calculate target date based on interval and prediction horizon
                target_date = self._calculate_target_date(datetime.now(), interval, prediction_horizon or 5)
                
                prediction = StockPrediction(
                    stock_id=stock.id,
                    model_id=ml_model.id,
                    prediction_date=datetime.now(),
                    target_date=target_date,
                    predicted_price=predicted_price,
                    confidence=confidence,
                    upper_bound=upper_bound,
                    lower_bound=lower_bound,
                    signal=signal,
                    signal_strength=signal_strength,
                    features_used=list(X.columns)
                )
                self.db.add(prediction)
                self.db.commit()
            
            # Get interval and prediction horizon from model if not provided
            actual_interval = interval or ml_model.parameters.get('interval', '1d')
            actual_horizon = prediction_horizon or ml_model.parameters.get('prediction_horizon', 5)
            
            # Calculate target date based on interval and prediction horizon
            target_date = self._calculate_target_date(datetime.now(), actual_interval, actual_horizon)
            
            # Format the interval for display
            interval_display = self._format_interval_for_display(actual_interval, actual_horizon)
            
            # Prepare and return the prediction results
            result = {
                "ticker": ticker,
                "model_type": model_type,
                "model_id": model_id,
                "prediction_date": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                "target_date": target_date.strftime("%Y-%m-%d %H:%M:%S"),
                "interval": actual_interval,
                "prediction_horizon": actual_horizon,
                "interval_display": interval_display,
                "current_price": latest_price,
                "predicted_price": predicted_price,
                "percent_change": percent_change,
                "confidence": confidence,
                "upper_bound": upper_bound,
                "lower_bound": lower_bound,
                "signal": signal,
                "signal_strength": signal_strength,
                "metrics": ml_model.metrics
            }
            
            return result
            
        except Exception as e:
            logger.error(f"Error generating prediction for {ticker}: {str(e)}")
            return {"error": str(e)}
    
    def _predict_with_model(self, model_type, ticker, X_scaled, scaler_y):
        """Make prediction with the specified model type"""
        try:
            model_path = ""
            
            if model_type == 'linear':
                model_path = os.path.join(MODEL_DIR, f"{ticker}_linear.joblib")
            elif model_type == 'forest':
                model_path = os.path.join(MODEL_DIR, f"{ticker}_forest.joblib")
            elif model_type == 'gbm':
                model_path = os.path.join(MODEL_DIR, f"{ticker}_gbm.joblib")
            elif model_type == 'lstm':
                model_path = os.path.join(MODEL_DIR, f"{ticker}_lstm.h5")
                
            if not os.path.exists(model_path):
                return {"error": f"Model file not found: {model_path}"}
                
            # Load the model
            if model_type == 'lstm':
                model = load_model(model_path)
                # Reshape for LSTM [samples, time steps, features]
                X_reshaped = X_scaled.reshape((X_scaled.shape[0], 1, X_scaled.shape[1]))
                y_pred_scaled = model.predict(X_reshaped)
            else:
                model = joblib.load(model_path)
                y_pred_scaled = model.predict(X_scaled)
                
            # Inverse transform to get actual price
            y_pred = scaler_y.inverse_transform(y_pred_scaled.reshape(-1, 1)).flatten()
            
            # Calculate confidence based on model metrics
            # This is a simplified approach; real confidence would be model-specific
            confidence = 0.8  # Default confidence
            
            return {
                "predicted_price": float(y_pred[0]),
                "confidence": confidence
            }
            
        except Exception as e:
            logger.error(f"Error predicting with model: {str(e)}")
            return {"error": str(e)}
    
    def _calculate_target_date(self, base_date: datetime, interval: str, horizon: int) -> datetime:
        """Calculate target date based on interval and prediction horizon"""
        if interval == '1m':
            return base_date + timedelta(minutes=horizon)
        elif interval == '5m':
            return base_date + timedelta(minutes=5 * horizon)
        elif interval == '15m':
            return base_date + timedelta(minutes=15 * horizon)
        elif interval == '30m':
            return base_date + timedelta(minutes=30 * horizon)
        elif interval == '1h':
            return base_date + timedelta(hours=horizon)
        elif interval == '1d':
            return base_date + timedelta(days=horizon)
        elif interval == '1wk':
            return base_date + timedelta(weeks=horizon)
        elif interval == '1mo':
            # Approximate a month as 30 days
            return base_date + timedelta(days=30 * horizon)
        else:
            # Default to days
            return base_date + timedelta(days=horizon)
    
    def _format_interval_for_display(self, interval: str, horizon: int) -> str:
        """Format interval and horizon for human-readable display"""
        if interval == '1m':
            unit = "minute" if horizon == 1 else "minutes"
            return f"{horizon} {unit}"
        elif interval == '5m':
            return f"{5 * horizon} minutes"
        elif interval == '15m':
            return f"{15 * horizon} minutes"
        elif interval == '30m':
            return f"{30 * horizon} minutes"
        elif interval == '1h':
            unit = "hour" if horizon == 1 else "hours"
            return f"{horizon} {unit}"
        elif interval == '1d':
            unit = "day" if horizon == 1 else "days"
            return f"{horizon} {unit}"
        elif interval == '1wk':
            unit = "week" if horizon == 1 else "weeks"
            return f"{horizon} {unit}"
        elif interval == '1mo':
            unit = "month" if horizon == 1 else "months"
            return f"{horizon} {unit}"
        else:
            return f"{horizon} {interval} intervals"
            
    def calculate_signal_strength(self, ticker: str, predictions: Optional[List[Dict]] = None) -> Dict[str, Any]:
        """Calculate comprehensive signal strength based on multiple timeframes
        
        Args:
            ticker: Stock ticker symbol
            predictions: Optional list of predictions, if None will fetch from database
            
        Returns:
            Dictionary with signal strength metrics and interpretation
        """
        # Get predictions from database if not provided
        if not predictions:
            timeframes = ['1h', '3h', '24h', '7d', '30d']
            predictions = []
            
            for timeframe in timeframes:
                pred = self.db.query(StockPrediction).filter(
                    StockPrediction.ticker == ticker,
                    StockPrediction.timeframe == timeframe
                ).order_by(StockPrediction.created_at.desc()).first()
                
                if pred:
                    predictions.append({
                        'timeframe': pred.timeframe,
                        'signal': pred.signal,
                        'signal_strength': pred.signal_strength or 0.5,
                        'confidence': pred.confidence or 85
                    })
        
        if not predictions:
            return {
                'ticker': ticker,
                'overall_signal': 'HOLD',
                'overall_strength': 0.0,
                'short_term_bias': 'NEUTRAL',
                'medium_term_bias': 'NEUTRAL',
                'long_term_bias': 'NEUTRAL',
                'consensus_score': 0,
                'confidence': 0,
                'timeframes_analyzed': 0
            }
        
        # Define timeframe weights (longer timeframes have more weight)
        weights = {
            '1h': 0.1,
            '3h': 0.15,
            '24h': 0.2,
            '7d': 0.25,
            '30d': 0.3
        }
        
        # Group timeframes
        short_term = [p for p in predictions if p['timeframe'] in ['1h', '3h']]
        medium_term = [p for p in predictions if p['timeframe'] in ['24h', '7d']]
        long_term = [p for p in predictions if p['timeframe'] in ['30d']]
        
        # Calculate weighted signal strength
        total_weight = 0
        weighted_signal = 0
        total_confidence = 0
        
        for pred in predictions:
            timeframe = pred['timeframe']
            weight = weights.get(timeframe, 0.2)
            
            # Convert signal to numeric value
            signal_value = 0
            if pred['signal'] == 'BUY':
                signal_value = 1.0
            elif pred['signal'] == 'SELL':
                signal_value = -1.0
            
            # Apply confidence and strength factors
            confidence = pred.get('confidence', 85) / 100
            strength = pred.get('signal_strength', 0.5)
            
            weighted_signal += signal_value * weight * strength
            total_weight += weight
            total_confidence += confidence * weight
        
        # Normalize to range -1 to 1
        overall_strength = weighted_signal / total_weight if total_weight > 0 else 0
        avg_confidence = (total_confidence / total_weight if total_weight > 0 else 0) * 100
        
        # Calculate bias for each timeframe group
        short_term_bias = self._calculate_group_bias(short_term, weights)
        medium_term_bias = self._calculate_group_bias(medium_term, weights)
        long_term_bias = self._calculate_group_bias(long_term, weights)
        
        # Determine overall signal
        overall_signal = 'HOLD'
        if overall_strength >= 0.4:
            overall_signal = 'BUY'
        elif overall_strength <= -0.4:
            overall_signal = 'SELL'
        
        # Calculate consensus score (0-10)
        consensus_score = round((overall_strength + 1) * 5, 1)
        
        return {
            'ticker': ticker,
            'overall_signal': overall_signal,
            'overall_strength': overall_strength,
            'short_term_bias': short_term_bias,
            'medium_term_bias': medium_term_bias,
            'long_term_bias': long_term_bias,
            'consensus_score': consensus_score,
            'confidence': round(avg_confidence, 1),
            'timeframes_analyzed': len(predictions)
        }
    
    def _calculate_group_bias(self, predictions: List[Dict], weights: Dict[str, float]) -> str:
        """Calculate bias for a group of timeframe predictions"""
        if not predictions:
            return 'NEUTRAL'
        
        total_weight = 0
        weighted_signal = 0
        
        for pred in predictions:
            timeframe = pred['timeframe']
            weight = weights.get(timeframe, 0.2)
            
            # Convert signal to numeric value
            signal_value = 0
            if pred['signal'] == 'BUY':
                signal_value = 1.0
            elif pred['signal'] == 'SELL':
                signal_value = -1.0
            
            # Apply strength factor
            strength = pred.get('signal_strength', 0.5)
            
            weighted_signal += signal_value * weight * strength
            total_weight += weight
        
        # Normalize to range -1 to 1
        group_strength = weighted_signal / total_weight if total_weight > 0 else 0
        
        if group_strength >= 0.3:
            return 'BULLISH'
        elif group_strength <= -0.3:
            return 'BEARISH'
        else:
            return 'NEUTRAL'


# Singleton instance
_ml_engine = None

def get_ml_engine(db: Session) -> MLEngine:
    """Get the singleton instance of the ML engine"""
    global _ml_engine
    if _ml_engine is None or _ml_engine.db != db:
        _ml_engine = MLEngine(db)
    return _ml_engine
