"""
Utility functions for fetching stock data from Yahoo Finance and saving to database
"""
import yfinance as yf
import pandas as pd
import pandas_ta as ta
import numpy as np
from datetime import datetime, timedelta
from sqlalchemy.orm import Session
from sqlalchemy.exc import IntegrityError
import logging
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.data.indonesian_stocks import INDONESIAN_STOCKS
import asyncio
import time
import random
from ratelimit import limits, sleep_and_retry
from app.core.cache_manager import cache_data, clear_cache

logger = logging.getLogger(__name__)

def calculate_indicators(df):
    """Calculate technical indicators for a dataframe of stock prices"""
    if df.empty:
        return df
    
    try:
        # SMA
        df['sma_20'] = ta.sma(df['Close'], length=20)
        df['sma_50'] = ta.sma(df['Close'], length=50)
        df['sma_200'] = ta.sma(df['Close'], length=200)
        
        # EMA
        df['ema_12'] = ta.ema(df['Close'], length=12)
        df['ema_26'] = ta.ema(df['Close'], length=26)
        
        # RSI
        df['rsi_14'] = ta.rsi(df['Close'], length=14)
        
        # MACD
        macd = ta.macd(df['Close'], fast=12, slow=26, signal=9)
        if not macd.empty:
            df['macd'] = macd['MACD_12_26_9']
            df['macd_signal'] = macd['MACDs_12_26_9']
            df['macd_histogram'] = macd['MACDh_12_26_9']
        
        # Bollinger Bands
        bbands = ta.bbands(df['Close'], length=20, std=2)
        if not bbands.empty:
            df['bb_upper'] = bbands['BBU_20_2.0']
            df['bb_middle'] = bbands['BBM_20_2.0']
            df['bb_lower'] = bbands['BBL_20_2.0']
            
        # Generate signals
        df['signal'] = 'HOLD'
        df['signal_strength'] = 0.5
        
        # Criteria for signals
        # Buy signals
        buy_conditions = (
            (df['Close'] < df['bb_lower']) |  # Price below lower BB
            ((df['macd'] > df['macd_signal']) & (df['macd'].shift(1) <= df['macd_signal'].shift(1))) |  # MACD crosses above signal
            (df['rsi_14'] < 30)  # RSI oversold
        )
        df.loc[buy_conditions, 'signal'] = 'BUY'
        
        # Sell signals
        sell_conditions = (
            (df['Close'] > df['bb_upper']) |  # Price above upper BB
            ((df['macd'] < df['macd_signal']) & (df['macd'].shift(1) >= df['macd_signal'].shift(1))) |  # MACD crosses below signal
            (df['rsi_14'] > 70)  # RSI overbought
        )
        df.loc[sell_conditions, 'signal'] = 'SELL'
        
        # Signal strength based on RSI distance from midpoint
        df['signal_strength'] = abs(df['rsi_14'] - 50) / 50
        df['signal_strength'] = df['signal_strength'].fillna(0.5)
        
        # Add notes for signals
        df['notes'] = ''
        df.loc[df['Close'] < df['bb_lower'], 'notes'] = df.loc[df['Close'] < df['bb_lower'], 'notes'] + 'Price below lower Bollinger Band. '
        df.loc[df['Close'] > df['bb_upper'], 'notes'] = df.loc[df['Close'] > df['bb_upper'], 'notes'] + 'Price above upper Bollinger Band. '
        df.loc[df['rsi_14'] < 30, 'notes'] = df.loc[df['rsi_14'] < 30, 'notes'] + 'RSI in oversold territory. '
        df.loc[df['rsi_14'] > 70, 'notes'] = df.loc[df['rsi_14'] > 70, 'notes'] + 'RSI in overbought territory. '
        df.loc[(df['macd'] > df['macd_signal']) & (df['macd'].shift(1) <= df['macd_signal'].shift(1)), 'notes'] = \
            df.loc[(df['macd'] > df['macd_signal']) & (df['macd'].shift(1) <= df['macd_signal'].shift(1)), 'notes'] + 'MACD crossed above signal line. '
        df.loc[(df['macd'] < df['macd_signal']) & (df['macd'].shift(1) >= df['macd_signal'].shift(1)), 'notes'] = \
            df.loc[(df['macd'] < df['macd_signal']) & (df['macd'].shift(1) >= df['macd_signal'].shift(1)), 'notes'] + 'MACD crossed below signal line. '
    
    except Exception as e:
        logger.error(f"Error calculating indicators: {str(e)}")
    
    return df

# Rate limit: 2 calls per second, with a small random delay to avoid bursts
@sleep_and_retry
@limits(calls=2, period=1)
def fetch_yahoo_data(ticker, period="1y", interval="1d"):
    """Fetch stock data from Yahoo Finance with rate limiting"""
    # Minimal delay untuk rate limiting, tapi tidak terlalu lama
    stock = yf.Ticker(ticker)
    return stock.history(period=period, interval=interval), stock.info

@cache_data(ttl_seconds=300)  # Cache data for 5 minutes
async def fetch_stock_data(ticker, period="1y", interval="1d", db=None):
    """Fetch stock data from Yahoo Finance, save to database, and then distribute.
    
    Following the data flow:
    1. Try to fetch real-time data from Yahoo Finance
    2. If successful, save it to database first
    3. Always return data from database (most recent) to frontend
    4. If API fails or hits limit, return previously stored data from database
    """
    df = pd.DataFrame()
    company_info = {'name': ticker, 'sector': 'N/A'}
    fetch_success = False
    
    # If we don't have a database session, we can't implement the desired flow
    if db is None:
        logger.warning(f"Database session is required for the data flow - {ticker}")
        return df, company_info
    
    # Step 1: Try to fetch real-time data from Yahoo Finance
    try:
        yahoo_df, info = fetch_yahoo_data(ticker, period, interval)
        
        # Reset index to include Date as a column
        yahoo_df = yahoo_df.reset_index()
        
        # Calculate indicators
        yahoo_df = calculate_indicators(yahoo_df)
        
        if not yahoo_df.empty:
            company_info = {
                'name': info.get('longName', ticker),
                'sector': info.get('sector', 'N/A'),
                'industry': info.get('industry', 'N/A'),
                'marketCap': info.get('marketCap', 0),
                'currentPrice': info.get('currentPrice', 0),
                'previousClose': info.get('previousClose', 0),
                'open': info.get('open', 0),
                'dayLow': info.get('dayLow', 0),
                'dayHigh': info.get('dayHigh', 0),
                'volume': info.get('volume', 0),
                'fiftyTwoWeekLow': info.get('fiftyTwoWeekLow', 0),
                'fiftyTwoWeekHigh': info.get('fiftyTwoWeekHigh', 0),
            }
            fetch_success = True
            logger.info(f"Successfully fetched data from Yahoo Finance for {ticker}")
            
            # Step 2: Save newly fetched data to database first
            await save_stock_data(db, ticker, yahoo_df, company_info)
            logger.info(f"Saved new data to database for {ticker}")
    except Exception as e:
        logger.warning(f"Failed to fetch data from Yahoo Finance for {ticker}: {str(e)}")
    
    # Step 3: Always return data from database (either newly saved data or previous data)
    # This way we implement the flow: API -> DB -> Frontend
    try:
        logger.info(f"Attempting to retrieve latest data from database for {ticker}")
        # Get stock from database
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        
        if stock:
            # Get latest prices (last 365 days or whatever is available)
            prices = db.query(StockPrice).filter(
                StockPrice.stock_id == stock.id
            ).order_by(StockPrice.date.desc()).limit(365).all()
            
            if prices:
                # Convert to dataframe
                price_data = [{
                    'Date': p.date,
                    'Open': p.open,
                    'High': p.high,
                    'Low': p.low,
                    'Close': p.close,
                    'Volume': p.volume
                } for p in prices]
                
                df = pd.DataFrame(price_data)
                df = df.sort_values('Date')  # Sort by date ascending
                
                # Get the latest indicators
                indicators = db.query(StockIndicator).filter(
                    StockIndicator.stock_id == stock.id
                ).order_by(StockIndicator.date.desc()).limit(365).all()
                
                # If we have indicators, use them
                if indicators:
                    for ind in indicators:
                        date_match = df['Date'] == ind.date
                        if any(date_match):
                            idx = date_match.idxmax()
                            df.loc[idx, 'sma_20'] = ind.sma_20
                            df.loc[idx, 'sma_50'] = ind.sma_50
                            df.loc[idx, 'sma_200'] = ind.sma_200
                            df.loc[idx, 'ema_12'] = ind.ema_12
                            df.loc[idx, 'ema_26'] = ind.ema_26
                            df.loc[idx, 'rsi_14'] = ind.rsi_14
                            df.loc[idx, 'macd'] = ind.macd
                            df.loc[idx, 'macd_signal'] = ind.macd_signal
                            df.loc[idx, 'macd_histogram'] = ind.macd_histogram
                            df.loc[idx, 'bb_upper'] = ind.bb_upper
                            df.loc[idx, 'bb_middle'] = ind.bb_middle
                            df.loc[idx, 'bb_lower'] = ind.bb_lower
                            df.loc[idx, 'signal'] = ind.signal
                            df.loc[idx, 'signal_strength'] = ind.signal_strength
                            df.loc[idx, 'notes'] = ind.notes
                else:
                    # Otherwise calculate them
                    df = calculate_indicators(df)
                
                company_info = {
                    'name': stock.name,
                    'sector': stock.sector,
                    'currentPrice': df.iloc[-1]['Close'] if not df.empty else 0
                }
                
                logger.info(f"Successfully retrieved data from database for {ticker}")
            else:
                logger.warning(f"No price data found in database for {ticker}")
        else:
            logger.warning(f"Stock {ticker} not found in database")
                
    except Exception as e:
        logger.error(f"Error retrieving data from database for {ticker}: {str(e)}")
    
    return df, company_info

async def save_stock_data(db, ticker, df, company_info):
    """Save stock data and indicators to database
    
    This function saves the data in an atomic way to ensure data consistency.
    """
    if df.empty:
        logger.warning(f"Cannot save empty dataframe for {ticker}")
        return
    
    try:
        # Check if stock exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        
        # If not, create it
        if not stock:
            stock = Stock(
                ticker=ticker,
                name=company_info.get('name', ticker),
                sector=company_info.get('sector', 'N/A'),
                last_updated=datetime.now()
            )
            db.add(stock)
            db.commit()
            db.refresh(stock)
        else:
            # Update stock info
            stock.name = company_info.get('name', stock.name)
            stock.sector = company_info.get('sector', stock.sector)
            stock.last_updated = datetime.now()
            db.commit()
        
        # Process and save each day's data
        for _, row in df.iterrows():
            date = row['Date']
            
            # Check if price exists for this date
            price = db.query(StockPrice).filter(
                StockPrice.stock_id == stock.id,
                StockPrice.date == date
            ).first()
            
            # Create or update price
            if not price:
                price = StockPrice(
                    stock_id=stock.id,
                    date=date,
                    open=row['Open'],
                    high=row['High'],
                    low=row['Low'],
                    close=row['Close'],
                    volume=row['Volume']
                )
                db.add(price)
            else:
                price.open = row['Open']
                price.high = row['High']
                price.low = row['Low']
                price.close = row['Close']
                price.volume = row['Volume']
            
            # Check if indicators exist for this date
            indicator = db.query(StockIndicator).filter(
                StockIndicator.stock_id == stock.id,
                StockIndicator.date == date
            ).first()
            
            # Create or update indicators
            if not indicator:
                indicator = StockIndicator(
                    stock_id=stock.id,
                    date=date,
                    sma_20=row.get('sma_20'),
                    sma_50=row.get('sma_50'),
                    sma_200=row.get('sma_200'),
                    ema_12=row.get('ema_12'),
                    ema_26=row.get('ema_26'),
                    rsi_14=row.get('rsi_14'),
                    macd=row.get('macd'),
                    macd_signal=row.get('macd_signal'),
                    macd_histogram=row.get('macd_histogram'),
                    bb_upper=row.get('bb_upper'),
                    bb_middle=row.get('bb_middle'),
                    bb_lower=row.get('bb_lower'),
                    signal=row.get('signal'),
                    signal_strength=row.get('signal_strength'),
                    notes=row.get('notes')
                )
                db.add(indicator)
            else:
                indicator.sma_20 = row.get('sma_20')
                indicator.sma_50 = row.get('sma_50') 
                indicator.sma_200 = row.get('sma_200')
                indicator.ema_12 = row.get('ema_12')
                indicator.ema_26 = row.get('ema_26')
                indicator.rsi_14 = row.get('rsi_14')
                indicator.macd = row.get('macd')
                indicator.macd_signal = row.get('macd_signal')
                indicator.macd_histogram = row.get('macd_histogram')
                indicator.bb_upper = row.get('bb_upper')
                indicator.bb_middle = row.get('bb_middle')
                indicator.bb_lower = row.get('bb_lower')
                indicator.signal = row.get('signal')
                indicator.signal_strength = row.get('signal_strength')
                indicator.notes = row.get('notes')
        
        db.commit()
        logger.info(f"Successfully saved {len(df)} records for {ticker}")
        
    except IntegrityError as e:
        db.rollback()
        logger.error(f"Database integrity error saving {ticker}: {str(e)}")
    except Exception as e:
        db.rollback()
        logger.error(f"Error saving {ticker} data: {str(e)}")

@cache_data(ttl_seconds=60)  # Cache results for 1 minute
async def get_latest_stock_data(db, ticker, fetch_if_outdated=False):
    """Get the latest stock data from the database or fetch if outdated
    
    This function follows the data flow: API -> DB -> Frontend
    - Always returns data from DB
    - If data is outdated, triggers a fetch in background but still returns current DB data
    - Next request will get the updated data from DB
    """
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    
    if not stock:
        logger.warning(f"Stock {ticker} not found in database")
        return None, None
    
    # Check if data is outdated (older than 24 hours for daily data)
    is_outdated = False
    if stock.last_updated:
        time_diff = datetime.now() - stock.last_updated
        is_outdated = time_diff.total_seconds() > 24 * 60 * 60  # 24 hours
    
    # If outdated and fetch_if_outdated is True, fetch new data in background
    # but still return current data immediately
    if is_outdated and fetch_if_outdated:
        logger.info(f"Data for {ticker} is outdated, triggering background refresh")
        # Use create_task to run this asynchronously
        asyncio.create_task(fetch_and_save_single_stock(db, ticker))
    
    # Get the latest price
    latest_price = (
        db.query(StockPrice)
        .filter(StockPrice.stock_id == stock.id)
        .order_by(StockPrice.date.desc())
        .first()
    )
    
    # Get the latest indicator
    latest_indicator = (
        db.query(StockIndicator)
        .filter(StockIndicator.stock_id == stock.id)
        .order_by(StockIndicator.date.desc())
        .first()
    )
    
    # Always return data from database, regardless of whether a refresh was triggered
    # This maintains the API -> DB -> Frontend flow
    return latest_price, latest_indicator

async def fetch_and_save_single_stock(db, ticker):
    """Fetch and save data for a single stock following the API -> DB -> Frontend flow
    
    This function implements the flow where:
    1. Data is fetched from Yahoo Finance API
    2. Saved directly to database
    3. No direct return to frontend (that's handled by separate endpoints)
    4. If API fails, no action needed as endpoints already return DB data
    """
    try:
        # Fetch data from Yahoo Finance
        try:
            df, info = fetch_yahoo_data(ticker, period="1y", interval="1d")
            
            # Reset index to include Date as a column
            df = df.reset_index()
            
            # Calculate indicators
            df = calculate_indicators(df)
            
            if not df.empty:
                company_info = {
                    'name': info.get('longName', ticker),
                    'sector': info.get('sector', 'N/A'),
                    'industry': info.get('industry', 'N/A'),
                    'marketCap': info.get('marketCap', 0),
                    'currentPrice': info.get('currentPrice', 0),
                    'previousClose': info.get('previousClose', 0),
                    'open': info.get('open', 0),
                    'dayLow': info.get('dayLow', 0),
                    'dayHigh': info.get('dayHigh', 0),
                    'volume': info.get('volume', 0),
                    'fiftyTwoWeekLow': info.get('fiftyTwoWeekLow', 0),
                    'fiftyTwoWeekHigh': info.get('fiftyTwoWeekHigh', 0),
                }
                
                # Save directly to database
                await save_stock_data(db, ticker, df, company_info)
                logger.info(f"Successfully updated data for {ticker}")
                
                # Update last_updated timestamp
                stock = db.query(Stock).filter(Stock.ticker == ticker).first()
                if stock:
                    stock.last_updated = datetime.now()
                    db.commit()
                
                return True
            else:
                logger.warning(f"No data available from Yahoo Finance for {ticker}")
                return False
        except Exception as e:
            logger.error(f"Failed to fetch data from Yahoo Finance for {ticker}: {str(e)}")
            return False
            
    except Exception as e:
        logger.error(f"Error in fetch_and_save_single_stock for {ticker}: {str(e)}")
        return False

async def fetch_and_save_all_stocks(db: Session, ticker_list=None):
    """Fetch and save data for all Indonesian stocks with rate limiting"""
    if ticker_list is None:
        ticker_list = [stock['ticker'] for stock in INDONESIAN_STOCKS]
    
    total = len(ticker_list)
    for i, ticker in enumerate(ticker_list):
        logger.info(f"Fetching data for {ticker}... ({i+1}/{total})")
        try:
            # Pass db session to enable fallback to database
            df, company_info = await fetch_stock_data(ticker, db=db)
            if not df.empty:
                await save_stock_data(db, ticker, df, company_info)
                logger.info(f"Successfully saved data for {ticker}")
            else:
                logger.warning(f"No data fetched for {ticker}")
        except Exception as e:
            logger.error(f"Error processing {ticker}: {str(e)}")
        
        # Delay minimal antara API calls
        time.sleep(random.uniform(0.5, 1.0))
