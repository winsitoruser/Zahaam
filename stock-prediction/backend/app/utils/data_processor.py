"""
Data processor module for time-series stock data

This module handles preprocessing of time-series data for machine learning models
including feature engineering, normalization, and train-test splitting.
"""

import pandas as pd
import numpy as np
from typing import Tuple, List, Dict, Any, Optional
from sklearn.preprocessing import MinMaxScaler, StandardScaler
from sklearn.model_selection import train_test_split
import logging

logger = logging.getLogger(__name__)

class TimeSeriesProcessor:
    """Time series data processor for stock prediction"""
    
    def __init__(self, sequence_length: int = 60, test_size: float = 0.2):
        """
        Initialize time series processor
        
        Args:
            sequence_length: Length of input sequences for ML models
            test_size: Proportion of data to use for testing
        """
        self.sequence_length = sequence_length
        self.test_size = test_size
        self.price_scaler = None
        self.feature_scaler = None
    
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
        df['Return'] = df['Close'].pct_change()
        df['Range'] = (df['High'] - df['Low']) / df['Close']
        df['LogReturn'] = np.log(df['Close'] / df['Close'].shift(1))
        
        # Moving averages
        for window in [5, 10, 20, 50]:
            df[f'MA_{window}'] = df['Close'].rolling(window=window).mean()
            df[f'MA_ratio_{window}'] = df['Close'] / df[f'MA_{window}']
        
        # Volatility
        for window in [5, 10, 20]:
            df[f'Volatility_{window}'] = df['LogReturn'].rolling(window=window).std()
        
        # Volume features
        df['Volume_MA_5'] = df['Volume'].rolling(window=5).mean()
        df['Volume_Change'] = df['Volume'].pct_change()
        df['Volume_MA_Ratio'] = df['Volume'] / df['Volume_MA_5']
        
        # Price momentum
        for window in [1, 5, 10]:
            df[f'Momentum_{window}'] = df['Close'] - df['Close'].shift(window)
        
        # RSI - Relative Strength Index
        delta = df['Close'].diff()
        gain = delta.where(delta > 0, 0).rolling(window=14).mean()
        loss = -delta.where(delta < 0, 0).rolling(window=14).mean()
        rs = gain / loss
        df['RSI'] = 100 - (100 / (1 + rs))
        
        # MACD - Moving Average Convergence Divergence
        ema12 = df['Close'].ewm(span=12, adjust=False).mean()
        ema26 = df['Close'].ewm(span=26, adjust=False).mean()
        df['MACD'] = ema12 - ema26
        df['MACD_Signal'] = df['MACD'].ewm(span=9, adjust=False).mean()
        df['MACD_Hist'] = df['MACD'] - df['MACD_Signal']
        
        # Bollinger Bands
        df['BB_Middle'] = df['Close'].rolling(window=20).mean()
        df['BB_Std'] = df['Close'].rolling(window=20).std()
        df['BB_Upper'] = df['BB_Middle'] + (df['BB_Std'] * 2)
        df['BB_Lower'] = df['BB_Middle'] - (df['BB_Std'] * 2)
        df['BB_Width'] = (df['BB_Upper'] - df['BB_Lower']) / df['BB_Middle']
        df['BB_Position'] = (df['Close'] - df['BB_Lower']) / (df['BB_Upper'] - df['BB_Lower'])
        
        # Day of week (cyclical encoding)
        if 'Date' in df.columns:
            df['DayOfWeek'] = pd.to_datetime(df['Date']).dt.dayofweek
            df['DayOfWeek_sin'] = np.sin(2 * np.pi * df['DayOfWeek'] / 5)
            df['DayOfWeek_cos'] = np.cos(2 * np.pi * df['DayOfWeek'] / 5)
        
        return df
    
    def prepare_data(self, df: pd.DataFrame, target_column: str = 'Close') -> Dict[str, Any]:
        """
        Prepare data for machine learning models
        
        Args:
            df: DataFrame with OHLCV and feature data
            target_column: Column to predict
            
        Returns:
            Dict containing processed data arrays and metadata
        """
        df_processed = df.copy()
        
        # Drop rows with NaN values
        df_processed = df_processed.dropna()
        
        # Extract dates if available
        dates = None
        if 'Date' in df_processed.columns:
            dates = df_processed['Date']
            df_processed = df_processed.drop('Date', axis=1)
            
        # Select target
        y = df_processed[target_column].values
        
        # Create feature matrix
        feature_columns = [col for col in df_processed.columns if col != target_column]
        X = df_processed[feature_columns].values
        
        # Scale price data - for predictions
        self.price_scaler = MinMaxScaler()
        y_scaled = self.price_scaler.fit_transform(y.reshape(-1, 1)).flatten()
        
        # Scale feature data
        self.feature_scaler = StandardScaler()
        X_scaled = self.feature_scaler.fit_transform(X)
        
        # Create sequences
        X_seq, y_seq = self._create_sequences(X_scaled, y_scaled)
        
        # Split data
        X_train, X_test, y_train, y_test = train_test_split(
            X_seq, y_seq, test_size=self.test_size, shuffle=False
        )
        
        return {
            'X_train': X_train,
            'X_test': X_test, 
            'y_train': y_train,
            'y_test': y_test,
            'feature_columns': feature_columns,
            'dates': dates[-len(y_seq):] if dates is not None else None,
            'price_scaler': self.price_scaler,
            'feature_scaler': self.feature_scaler,
            'sequence_length': self.sequence_length,
            'last_sequence': X_scaled[-self.sequence_length:].reshape(1, self.sequence_length, X_scaled.shape[1])
        }
    
    def _create_sequences(self, X: np.ndarray, y: np.ndarray) -> Tuple[np.ndarray, np.ndarray]:
        """
        Create input sequences for time series prediction
        
        Args:
            X: Feature matrix
            y: Target vector
            
        Returns:
            X_seq: Sequence input features [samples, sequence_length, features]
            y_seq: Sequence targets [samples, 1]
        """
        X_seq, y_seq = [], []
        
        for i in range(len(X) - self.sequence_length):
            X_seq.append(X[i:i+self.sequence_length])
            y_seq.append(y[i+self.sequence_length])
            
        return np.array(X_seq), np.array(y_seq)
    
    def inverse_transform_price(self, scaled_price: np.ndarray) -> np.ndarray:
        """
        Convert scaled price predictions back to original scale
        
        Args:
            scaled_price: Scaled price array
            
        Returns:
            Original scale price array
        """
        if self.price_scaler is None:
            raise ValueError("Price scaler not initialized. Call prepare_data first.")
            
        return self.price_scaler.inverse_transform(scaled_price.reshape(-1, 1)).flatten()
    
    def evaluate_predictions(self, y_true: np.ndarray, y_pred: np.ndarray) -> Dict[str, float]:
        """
        Calculate evaluation metrics for predictions
        
        Args:
            y_true: True values
            y_pred: Predicted values
            
        Returns:
            Dict of metric names and values
        """
        from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
        
        # Original scale metrics
        y_true_orig = self.inverse_transform_price(y_true)
        y_pred_orig = self.inverse_transform_price(y_pred)
        
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
            'mse': mse,
            'rmse': rmse,
            'mae': mae,
            'r2': r2,
            'direction_accuracy': direction_accuracy
        }
