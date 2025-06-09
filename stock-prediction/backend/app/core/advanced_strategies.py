"""
Advanced trading strategies and technical analysis techniques

This module provides advanced trading strategies and analysis methods
beyond the basic strategies in trading_strategies.py
"""

import pandas as pd
import numpy as np
from typing import Dict, List, Any, Optional, Tuple
import talib
from datetime import datetime, timedelta
import logging

logger = logging.getLogger(__name__)

# Import basic trading strategies to extend functionality
from app.core.trading_strategies import (
    calculate_moving_averages,
    calculate_exponential_moving_averages,
    calculate_rsi,
    calculate_macd,
    calculate_bollinger_bands
)


def calculate_ichimoku_cloud(df: pd.DataFrame) -> pd.DataFrame:
    """
    Calculate Ichimoku Cloud components
    
    - Tenkan-sen (Conversion Line): (9-period high + 9-period low)/2
    - Kijun-sen (Base Line): (26-period high + 26-period low)/2
    - Senkou Span A (Leading Span A): (Conversion Line + Base Line)/2
    - Senkou Span B (Leading Span B): (52-period high + 52-period low)/2
    - Chikou Span (Lagging Span): Close plotted 26 periods in the past
    """
    # Make a copy to avoid modifying original
    df = df.copy()
    
    # Calculate Tenkan-sen (Conversion Line)
    period9_high = df['High'].rolling(window=9).max()
    period9_low = df['Low'].rolling(window=9).min()
    df['tenkan_sen'] = (period9_high + period9_low) / 2
    
    # Calculate Kijun-sen (Base Line)
    period26_high = df['High'].rolling(window=26).max()
    period26_low = df['Low'].rolling(window=26).min()
    df['kijun_sen'] = (period26_high + period26_low) / 2
    
    # Calculate Senkou Span A (Leading Span A)
    df['senkou_span_a'] = ((df['tenkan_sen'] + df['kijun_sen']) / 2).shift(26)
    
    # Calculate Senkou Span B (Leading Span B)
    period52_high = df['High'].rolling(window=52).max()
    period52_low = df['Low'].rolling(window=52).min()
    df['senkou_span_b'] = ((period52_high + period52_low) / 2).shift(26)
    
    # Calculate Chikou Span (Lagging Span)
    df['chikou_span'] = df['Close'].shift(-26)
    
    return df


def calculate_divergence(df: pd.DataFrame, price_col='Close', indicator_col='RSI', window=14) -> pd.DataFrame:
    """
    Detect divergence between price and an indicator
    
    Divergence occurs when price makes a new high/low but the indicator doesn't
    """
    df = df.copy()
    
    # Ensure we have the indicator
    if indicator_col not in df.columns and indicator_col == 'RSI':
        df = calculate_rsi(df, window)
    
    # Find local minima and maxima in price
    df['price_min'] = df[price_col] == df[price_col].rolling(window=window, center=True).min()
    df['price_max'] = df[price_col] == df[price_col].rolling(window=window, center=True).max()
    
    # Find local minima and maxima in indicator
    df['ind_min'] = df[indicator_col] == df[indicator_col].rolling(window=window, center=True).min()
    df['ind_max'] = df[indicator_col] == df[indicator_col].rolling(window=window, center=True).max()
    
    # Detect bullish divergence (price makes lower low, indicator makes higher low)
    bullish_div = []
    bearish_div = []
    
    for i in range(window, len(df) - window):
        # Look for price minima
        if df['price_min'].iloc[i]:
            # Look back for previous price minimum within reasonable distance
            for j in range(i-window*3, i-window):
                if j > 0 and df['price_min'].iloc[j]:
                    # Check if price made lower low but indicator made higher low
                    if (df[price_col].iloc[i] < df[price_col].iloc[j] and 
                        df[indicator_col].iloc[i] > df[indicator_col].iloc[j]):
                        bullish_div.append(i)
                    break
        
        # Look for price maxima
        if df['price_max'].iloc[i]:
            # Look back for previous price maximum within reasonable distance
            for j in range(i-window*3, i-window):
                if j > 0 and df['price_max'].iloc[j]:
                    # Check if price made higher high but indicator made lower high
                    if (df[price_col].iloc[i] > df[price_col].iloc[j] and 
                        df[indicator_col].iloc[i] < df[indicator_col].iloc[j]):
                        bearish_div.append(i)
                    break
    
    # Mark divergences on DataFrame
    df['bullish_divergence'] = False
    df['bearish_divergence'] = False
    
    df.loc[bullish_div, 'bullish_divergence'] = True
    df.loc[bearish_div, 'bearish_divergence'] = True
    
    return df


def calculate_support_resistance(df: pd.DataFrame, window=20, threshold=0.02) -> Dict:
    """
    Calculate support and resistance levels using pivot points
    
    Args:
        df: DataFrame with OHLC data
        window: Window size for identifying pivots
        threshold: Minimum price distance (%) to consider a new level
    
    Returns:
        Dict with support and resistance levels
    """
    # Make a copy to avoid modifying original
    df = df.copy()
    
    # Find pivot highs (resistance) and lows (support)
    pivot_high = df['High'].rolling(window=window, center=True).apply(
        lambda x: x[len(x)//2] == max(x), raw=True)
    pivot_low = df['Low'].rolling(window=window, center=True).apply(
        lambda x: x[len(x)//2] == min(x), raw=True)
    
    # Get pivot prices
    resistance_levels = df.loc[pivot_high, 'High'].tolist()
    support_levels = df.loc[pivot_low, 'Low'].tolist()
    
    # Cluster similar levels
    def cluster_levels(levels, threshold):
        if not levels:
            return []
        
        levels = sorted(levels)
        clusters = [[levels[0]]]
        
        for level in levels[1:]:
            last_cluster = clusters[-1]
            last_avg = sum(last_cluster) / len(last_cluster)
            
            # If within threshold distance, add to current cluster
            if abs(level - last_avg) / last_avg < threshold:
                last_cluster.append(level)
            else:
                # Otherwise, start new cluster
                clusters.append([level])
        
        # Calculate average for each cluster
        return [sum(cluster) / len(cluster) for cluster in clusters]
    
    # Cluster and sort levels
    support_levels = cluster_levels(support_levels, threshold)
    resistance_levels = cluster_levels(resistance_levels, threshold)
    
    # Restrict to major levels (closest to current price)
    current_price = df['Close'].iloc[-1]
    
    # Sort by distance to current price
    support_levels = sorted([(abs(level - current_price), level) for level in support_levels])
    resistance_levels = sorted([(abs(level - current_price), level) for level in resistance_levels])
    
    # Get the levels, sorted by distance
    support = [round(level[1], 2) for level in support_levels if level[1] < current_price][:3]
    resistance = [round(level[1], 2) for level in resistance_levels if level[1] > current_price][:3]
    
    return {
        "support": support,
        "resistance": resistance
    }
