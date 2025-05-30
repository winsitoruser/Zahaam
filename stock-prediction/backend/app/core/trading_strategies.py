"""
Module for stock trading strategies and technical analysis
"""
import pandas as pd
import numpy as np
from datetime import datetime, timedelta


def calculate_moving_averages(df, periods=[20, 50, 200]):
    """Calculate Simple Moving Averages for given periods"""
    for period in periods:
        df[f'SMA_{period}'] = df['Close'].rolling(window=period).mean()
    return df


def calculate_exponential_moving_averages(df, periods=[12, 26]):
    """Calculate Exponential Moving Averages for given periods"""
    for period in periods:
        df[f'EMA_{period}'] = df['Close'].ewm(span=period, adjust=False).mean()
    return df


def calculate_rsi(df, period=14):
    """Calculate Relative Strength Index"""
    delta = df['Close'].diff()
    gain = delta.where(delta > 0, 0).rolling(window=period).mean()
    loss = -delta.where(delta < 0, 0).rolling(window=period).mean()
    
    rs = gain / loss
    df['RSI'] = 100 - (100 / (1 + rs))
    return df


def calculate_macd(df, fast=12, slow=26, signal=9):
    """Calculate MACD (Moving Average Convergence Divergence)"""
    df['EMA_fast'] = df['Close'].ewm(span=fast, adjust=False).mean()
    df['EMA_slow'] = df['Close'].ewm(span=slow, adjust=False).mean()
    df['MACD'] = df['EMA_fast'] - df['EMA_slow']
    df['MACD_Signal'] = df['MACD'].ewm(span=signal, adjust=False).mean()
    df['MACD_Histogram'] = df['MACD'] - df['MACD_Signal']
    return df


def calculate_bollinger_bands(df, period=20, std_dev=2):
    """Calculate Bollinger Bands"""
    df['BB_Middle'] = df['Close'].rolling(window=period).mean()
    df['BB_Std'] = df['Close'].rolling(window=period).std()
    df['BB_Upper'] = df['BB_Middle'] + (df['BB_Std'] * std_dev)
    df['BB_Lower'] = df['BB_Middle'] - (df['BB_Std'] * std_dev)
    return df


def calculate_stochastic_oscillator(df, k_period=14, d_period=3):
    """Calculate Stochastic Oscillator"""
    low_min = df['Low'].rolling(window=k_period).min()
    high_max = df['High'].rolling(window=k_period).max()
    
    df['%K'] = 100 * ((df['Close'] - low_min) / (high_max - low_min))
    df['%D'] = df['%K'].rolling(window=d_period).mean()
    return df


def calculate_fibonacci_retracement(df, trend="uptrend"):
    """Calculate Fibonacci Retracement Levels"""
    if trend == "uptrend":
        high = df['High'].max()
        low = df['Low'].min()
    else:  # downtrend
        high = df['High'].iloc[-50:].max()
        low = df['Low'].iloc[-50:].min()
    
    diff = high - low
    
    df['Fib_0'] = low
    df['Fib_0.236'] = low + 0.236 * diff
    df['Fib_0.382'] = low + 0.382 * diff
    df['Fib_0.5'] = low + 0.5 * diff
    df['Fib_0.618'] = low + 0.618 * diff
    df['Fib_0.786'] = low + 0.786 * diff
    df['Fib_1'] = high
    
    return df


def strategy_moving_average_crossover(df):
    """
    Moving Average Crossover Strategy
    
    Buy signal: When short-term MA crosses above long-term MA
    Sell signal: When short-term MA crosses below long-term MA
    """
    df = calculate_moving_averages(df, [20, 50])
    
    df['Signal'] = 0
    df.loc[df['SMA_20'] > df['SMA_50'], 'Signal'] = 1  # Buy
    df.loc[df['SMA_20'] < df['SMA_50'], 'Signal'] = -1  # Sell
    
    # Detect crossovers (signal changes)
    df['Signal_Change'] = df['Signal'].diff()
    df.loc[df['Signal_Change'] == 2, 'Action'] = 'BUY'
    df.loc[df['Signal_Change'] == -2, 'Action'] = 'SELL'
    
    return df


def strategy_rsi_oversold_overbought(df):
    """
    RSI Oversold/Overbought Strategy
    
    Buy signal: When RSI goes below 30 (oversold) and then rises back above 30
    Sell signal: When RSI goes above 70 (overbought) and then falls back below 70
    """
    df = calculate_rsi(df)
    
    df['Oversold'] = df['RSI'] < 30
    df['Overbought'] = df['RSI'] > 70
    
    df['Buy_Signal'] = (df['RSI'] > 30) & (df['RSI'].shift(1) < 30)
    df['Sell_Signal'] = (df['RSI'] < 70) & (df['RSI'].shift(1) > 70)
    
    df['Action'] = None
    df.loc[df['Buy_Signal'], 'Action'] = 'BUY'
    df.loc[df['Sell_Signal'], 'Action'] = 'SELL'
    
    return df


def strategy_macd(df):
    """
    MACD Strategy
    
    Buy signal: When MACD line crosses above Signal line
    Sell signal: When MACD line crosses below Signal line
    """
    df = calculate_macd(df)
    
    df['Buy_Signal'] = (df['MACD'] > df['MACD_Signal']) & (df['MACD'].shift(1) <= df['MACD_Signal'].shift(1))
    df['Sell_Signal'] = (df['MACD'] < df['MACD_Signal']) & (df['MACD'].shift(1) >= df['MACD_Signal'].shift(1))
    
    df['Action'] = None
    df.loc[df['Buy_Signal'], 'Action'] = 'BUY'
    df.loc[df['Sell_Signal'], 'Action'] = 'SELL'
    
    return df


def strategy_bollinger_bands_bounce(df):
    """
    Bollinger Bands Bounce Strategy
    
    Buy signal: When price touches lower band and then moves up
    Sell signal: When price touches upper band and then moves down
    """
    df = calculate_bollinger_bands(df)
    
    df['Lower_Band_Touch'] = df['Low'] <= df['BB_Lower']
    df['Upper_Band_Touch'] = df['High'] >= df['BB_Upper']
    
    df['Buy_Signal'] = (df['Lower_Band_Touch']) & (df['Close'] > df['Close'].shift(1))
    df['Sell_Signal'] = (df['Upper_Band_Touch']) & (df['Close'] < df['Close'].shift(1))
    
    df['Action'] = None
    df.loc[df['Buy_Signal'], 'Action'] = 'BUY'
    df.loc[df['Sell_Signal'], 'Action'] = 'SELL'
    
    return df


def get_stop_loss_level(df, entry_price, strategy='atr', risk_percentage=2, atr_multiple=2):
    """
    Calculate stop loss level based on different strategies
    
    Parameters:
    - df: DataFrame with price data
    - entry_price: Price at entry
    - strategy: Strategy to use (atr, percentage, support)
    - risk_percentage: Percentage below entry price
    - atr_multiple: Multiple of ATR
    
    Returns:
    - stop_loss_price
    """
    if strategy == 'percentage':
        # Simple percentage-based stop loss
        stop_loss = entry_price * (1 - risk_percentage / 100)
        
    elif strategy == 'atr':
        # ATR-based stop loss
        # Calculate ATR
        period = 14
        df['TR'] = np.maximum(
            df['High'] - df['Low'],
            np.maximum(
                abs(df['High'] - df['Close'].shift(1)),
                abs(df['Low'] - df['Close'].shift(1))
            )
        )
        atr = df['TR'].rolling(window=period).mean().iloc[-1]
        
        if df['Close'].iloc[-1] > entry_price:  # Long position
            stop_loss = entry_price - (atr * atr_multiple)
        else:  # Short position
            stop_loss = entry_price + (atr * atr_multiple)
            
    elif strategy == 'support':
        # Support/Resistance based stop loss
        # Find recent lows for support
        window = 20
        recent_lows = df['Low'].rolling(window=window, center=True).min()
        support_level = recent_lows.iloc[-10:].min()
        
        stop_loss = support_level * 0.99  # Slightly below support
    
    else:
        stop_loss = entry_price * 0.95  # Default 5% below entry
        
    return round(stop_loss, 2)


def get_take_profit_level(entry_price, stop_loss, risk_reward_ratio=2):
    """
    Calculate take profit based on risk to reward ratio
    """
    risk = abs(entry_price - stop_loss)
    take_profit = entry_price + (risk * risk_reward_ratio) if entry_price > stop_loss else entry_price - (risk * risk_reward_ratio)
    
    return round(take_profit, 2)


def combine_strategies(df, strategies=['ma_crossover', 'rsi', 'macd', 'bollinger']):
    """
    Combine signals from multiple strategies with weights
    """
    signals_df = pd.DataFrame(index=df.index)
    
    # Apply each strategy
    if 'ma_crossover' in strategies:
        ma_df = strategy_moving_average_crossover(df.copy())
        signals_df['MA_Action'] = ma_df['Action']
        
    if 'rsi' in strategies:
        rsi_df = strategy_rsi_oversold_overbought(df.copy())
        signals_df['RSI_Action'] = rsi_df['Action']
        
    if 'macd' in strategies:
        macd_df = strategy_macd(df.copy())
        signals_df['MACD_Action'] = macd_df['Action']
        
    if 'bollinger' in strategies:
        bb_df = strategy_bollinger_bands_bounce(df.copy())
        signals_df['BB_Action'] = bb_df['Action']
    
    # Count buy and sell signals
    buy_count = (signals_df == 'BUY').sum(axis=1)
    sell_count = (signals_df == 'SELL').sum(axis=1)
    
    # Determine final action based on majority vote
    df['Buy_Count'] = buy_count
    df['Sell_Count'] = sell_count
    df['Strategy_Count'] = len(strategies)
    
    df['Combined_Action'] = None
    
    # If more than 50% of strategies agree
    threshold = len(strategies) * 0.5
    df.loc[buy_count > threshold, 'Combined_Action'] = 'BUY'
    df.loc[sell_count > threshold, 'Combined_Action'] = 'SELL'
    
    return df
