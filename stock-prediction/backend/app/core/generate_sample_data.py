"""
Generate sample data for stock prediction app
"""
import random
import sqlite3
import pandas as pd
from datetime import datetime, timedelta
import os
import logging
import math
import numpy as np
from sqlalchemy.orm import Session
from app.data.indonesian_stocks import INDONESIAN_STOCKS
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.core.database import SessionLocal

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def generate_price_data(base_price, days=365, volatility=0.02):
    """Generate synthetic price data with trends and volatility"""
    prices = []
    current_price = base_price
    
    # Generate trend components - we'll have a few trend changes throughout the year
    change_points = [0, int(days/3), int(2*days/3), days]
    trends = [random.uniform(-0.0005, 0.0005) for _ in range(len(change_points)-1)]
    
    for i in range(days):
        # Find which trend period we're in
        for j in range(len(change_points)-1):
            if change_points[j] <= i < change_points[j+1]:
                trend = trends[j]
                break
        
        # Add random noise (volatility)
        daily_return = trend + random.normalvariate(0, volatility)
        current_price *= (1 + daily_return)
        
        # Calculate high, low and open with some randomness around close
        daily_volatility = current_price * volatility
        high = current_price + abs(random.normalvariate(0, daily_volatility))
        low = current_price - abs(random.normalvariate(0, daily_volatility))
        low = max(low, high * 0.9)  # ensure low is not too low
        open_price = low + random.random() * (high - low)
        
        # Ensure we don't get negative prices
        low = max(0.1, low)
        open_price = max(0.1, open_price)
        current_price = max(0.1, current_price)
        
        prices.append({
            'open': round(open_price, 2),
            'high': round(high, 2),
            'low': round(low, 2),
            'close': round(current_price, 2),
            'volume': int(random.uniform(500000, 5000000))
        })
    
    return prices

def calculate_indicators(prices_df):
    """Calculate technical indicators for the price data"""
    # Calculate SMA
    prices_df['sma_20'] = prices_df['close'].rolling(window=20).mean()
    prices_df['sma_50'] = prices_df['close'].rolling(window=50).mean()
    prices_df['sma_200'] = prices_df['close'].rolling(window=200).mean()
    
    # Calculate EMA
    prices_df['ema_12'] = prices_df['close'].ewm(span=12, adjust=False).mean()
    prices_df['ema_26'] = prices_df['close'].ewm(span=26, adjust=False).mean()
    
    # Calculate MACD
    prices_df['macd'] = prices_df['ema_12'] - prices_df['ema_26']
    prices_df['macd_signal'] = prices_df['macd'].ewm(span=9, adjust=False).mean()
    prices_df['macd_histogram'] = prices_df['macd'] - prices_df['macd_signal']
    
    # Calculate RSI
    delta = prices_df['close'].diff()
    gain = delta.where(delta > 0, 0).rolling(window=14).mean()
    loss = -delta.where(delta < 0, 0).rolling(window=14).mean()
    rs = gain / loss
    prices_df['rsi_14'] = 100 - (100 / (1 + rs))
    
    # Calculate Bollinger Bands
    prices_df['bb_middle'] = prices_df['close'].rolling(window=20).mean()
    prices_df['bb_std'] = prices_df['close'].rolling(window=20).std()
    prices_df['bb_upper'] = prices_df['bb_middle'] + (2 * prices_df['bb_std'])
    prices_df['bb_lower'] = prices_df['bb_middle'] - (2 * prices_df['bb_std'])
    
    # Generate some buy/sell signals based on simple rules for demonstration
    # SMA crossover
    prices_df['signal'] = ''
    prices_df['signal_strength'] = 0.0
    prices_df['notes'] = ''
    
    # SMA crossover signals (short over long)
    prices_df.loc[(prices_df['sma_20'] > prices_df['sma_50']) & 
                  (prices_df['sma_20'].shift(1) <= prices_df['sma_50'].shift(1)), 
                  'signal'] = 'BUY'
    
    prices_df.loc[(prices_df['sma_20'] < prices_df['sma_50']) & 
                  (prices_df['sma_20'].shift(1) >= prices_df['sma_50'].shift(1)), 
                  'signal'] = 'SELL'
    
    # RSI signals
    prices_df.loc[(prices_df['rsi_14'] < 30) & (prices_df['rsi_14'].shift(1) >= 30), 'signal'] = 'BUY'
    prices_df.loc[(prices_df['rsi_14'] > 70) & (prices_df['rsi_14'].shift(1) <= 70), 'signal'] = 'SELL'
    
    # MACD signals
    prices_df.loc[(prices_df['macd'] > prices_df['macd_signal']) & 
                  (prices_df['macd'].shift(1) <= prices_df['macd_signal'].shift(1)), 
                  'signal'] = 'BUY'
    
    prices_df.loc[(prices_df['macd'] < prices_df['macd_signal']) & 
                  (prices_df['macd'].shift(1) >= prices_df['macd_signal'].shift(1)), 
                  'signal'] = 'SELL'
    
    # Add signal strength (just sample values)
    prices_df.loc[prices_df['signal'] == 'BUY', 'signal_strength'] = np.random.uniform(60, 90, size=len(prices_df[prices_df['signal'] == 'BUY']))
    prices_df.loc[prices_df['signal'] == 'SELL', 'signal_strength'] = np.random.uniform(60, 90, size=len(prices_df[prices_df['signal'] == 'SELL']))
    
    # Add some notes
    prices_df.loc[prices_df['signal'] == 'BUY', 'notes'] = 'Technical indicators suggest a potential uptrend'
    prices_df.loc[prices_df['signal'] == 'SELL', 'notes'] = 'Technical indicators suggest a potential downtrend'
    
    return prices_df


def generate_and_save_sample_data():
    """Generate sample data for all Indonesian stocks and save to database"""
    # Create database session
    db = SessionLocal()
    
    try:
        # First, delete all existing data
        logger.info("Clearing existing data...")
        db.query(StockIndicator).delete()
        db.query(StockPrice).delete()
        db.query(Stock).delete()
        db.commit()
        
        # Generate data for each stock
        for stock_info in INDONESIAN_STOCKS:
            ticker = stock_info["ticker"]
            name = stock_info["name"]
            sector = stock_info["sector"]
            
            logger.info(f"Generating sample data for {ticker}...")
            
            # Create stock record
            stock = Stock(
                ticker=ticker, 
                name=name, 
                sector=sector,
                is_active=True,
                last_updated=datetime.now()
            )
            db.add(stock)
            db.flush()  # Get the stock.id without committing transaction
            
            # Generate random base price appropriate for Indonesian stock market (in IDR)
            base_price = random.uniform(1000, 10000)
            
            # Generate days of price data (1 year)
            days = 365
            end_date = datetime.now()
            start_date = end_date - timedelta(days=days)
            
            # Generate synthetic price data
            price_data = generate_price_data(base_price, days)
            
            # Create dataframe and calculate indicators
            dates = [start_date + timedelta(days=i) for i in range(days)]
            df = pd.DataFrame(price_data)
            df['date'] = dates
            
            # Calculate technical indicators
            df = calculate_indicators(df)
            
            # Insert price and indicator records
            for _, row in df.iterrows():
                # Skip rows with NaN values (like the beginning where some indicators can't be calculated)
                if row.isnull().any():
                    continue
                    
                # Add price record
                price = StockPrice(
                    stock_id=stock.id,
                    date=row['date'],
                    open=row['open'],
                    high=row['high'],
                    low=row['low'],
                    close=row['close'],
                    volume=row['volume']
                )
                db.add(price)
                
                # Add indicator record
                indicator = StockIndicator(
                    stock_id=stock.id,
                    date=row['date'],
                    sma_20=row['sma_20'],
                    sma_50=row['sma_50'],
                    sma_200=row['sma_200'],
                    ema_12=row['ema_12'],
                    ema_26=row['ema_26'],
                    rsi_14=row['rsi_14'],
                    macd=row['macd'],
                    macd_signal=row['macd_signal'],
                    macd_histogram=row['macd_histogram'],
                    bb_upper=row['bb_upper'],
                    bb_middle=row['bb_middle'],
                    bb_lower=row['bb_lower'],
                    signal=row['signal'],
                    signal_strength=row['signal_strength'],
                    notes=row['notes']
                )
                db.add(indicator)
            
            # Commit after each stock to avoid transaction timeout
            db.commit()
            logger.info(f"Successfully generated data for {ticker}")
        
        logger.info("Sample data generation complete!")
        
    except Exception as e:
        db.rollback()
        logger.error(f"Error generating sample data: {str(e)}")
    finally:
        db.close()

if __name__ == "__main__":
    generate_and_save_sample_data()
