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
    """Fetch stock data from Yahoo Finance with fallback to database"""
    df = pd.DataFrame()
    company_info = {'name': ticker, 'sector': 'N/A'}
    fetch_success = False
    
    try:
        # Try to get data from Yahoo Finance
        df, info = fetch_yahoo_data(ticker, period, interval)
        
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
            fetch_success = True
            logger.info(f"Successfully fetched data from Yahoo Finance for {ticker}")
    except Exception as e:
        logger.warning(f"Failed to fetch data from Yahoo Finance for {ticker}: {str(e)}")
    
    # If Yahoo fetch failed and we have a database session, try to get latest data from database
    if not fetch_success and db is not None:
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

def save_stock_data(db: Session, ticker: str, df: pd.DataFrame, company_info: dict):
    """Save stock data and indicators to database"""
    try:
        # Get or create stock
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            stock = Stock(
                ticker=ticker,
                name=company_info.get('name', ticker),
                sector=company_info.get('sector', 'N/A'),
                last_updated=datetime.now()
            )
            db.add(stock)
            db.flush()
        else:
            stock.last_updated = datetime.now()
            db.flush()
            
        # Clear cache for this ticker since data has changed
        clear_cache(prefix=f"get_latest_stock_data-{ticker}")
        clear_cache(prefix=f"fetch_stock_data-{ticker}")
        
        # Delete existing records for this ticker to avoid duplicates
        db.query(StockPrice).filter(StockPrice.stock_id == stock.id).delete()
        db.query(StockIndicator).filter(StockIndicator.stock_id == stock.id).delete()
        
        # Add new prices and indicators
        for _, row in df.iterrows():
            if 'Date' not in row or pd.isna(row['Date']):
                continue
                
            # Convert date to datetime if it's a string
            date = row['Date']
            if isinstance(date, str):
                date = datetime.fromisoformat(date.split('T')[0])
                
            # Add stock price
            price = StockPrice(
                stock_id=stock.id,
                date=date,
                open=float(row['Open']) if 'Open' in row and not pd.isna(row['Open']) else None,
                high=float(row['High']) if 'High' in row and not pd.isna(row['High']) else None,
                low=float(row['Low']) if 'Low' in row and not pd.isna(row['Low']) else None,
                close=float(row['Close']) if 'Close' in row and not pd.isna(row['Close']) else None,
                volume=int(row['Volume']) if 'Volume' in row and not pd.isna(row['Volume']) else 0
            )
            db.add(price)
            
            # Add indicators
            indicator = StockIndicator(
                stock_id=stock.id,
                date=date,
                sma_20=float(row['sma_20']) if 'sma_20' in row and not pd.isna(row['sma_20']) else None,
                sma_50=float(row['sma_50']) if 'sma_50' in row and not pd.isna(row['sma_50']) else None,
                sma_200=float(row['sma_200']) if 'sma_200' in row and not pd.isna(row['sma_200']) else None,
                ema_12=float(row['ema_12']) if 'ema_12' in row and not pd.isna(row['ema_12']) else None,
                ema_26=float(row['ema_26']) if 'ema_26' in row and not pd.isna(row['ema_26']) else None,
                rsi_14=float(row['rsi_14']) if 'rsi_14' in row and not pd.isna(row['rsi_14']) else None,
                macd=float(row['macd']) if 'macd' in row and not pd.isna(row['macd']) else None,
                macd_signal=float(row['macd_signal']) if 'macd_signal' in row and not pd.isna(row['macd_signal']) else None,
                macd_histogram=float(row['macd_histogram']) if 'macd_histogram' in row and not pd.isna(row['macd_histogram']) else None,
                bb_upper=float(row['bb_upper']) if 'bb_upper' in row and not pd.isna(row['bb_upper']) else None,
                bb_middle=float(row['bb_middle']) if 'bb_middle' in row and not pd.isna(row['bb_middle']) else None,
                bb_lower=float(row['bb_lower']) if 'bb_lower' in row and not pd.isna(row['bb_lower']) else None,
                signal=row['signal'] if 'signal' in row and not pd.isna(row['signal']) else 'HOLD',
                signal_strength=float(row['signal_strength']) if 'signal_strength' in row and not pd.isna(row['signal_strength']) else 0.5,
                notes=row['notes'] if 'notes' in row and not pd.isna(row['notes']) else ''
            )
            db.add(indicator)
        
        db.commit()
        return True
    except IntegrityError as e:
        logger.error(f"Database integrity error for {ticker}: {str(e)}")
        db.rollback()
        return False
    except Exception as e:
        logger.error(f"Error saving data for {ticker}: {str(e)}")
        db.rollback()
        return False

@cache_data(ttl_seconds=60)  # Cache results for 1 minute
def get_latest_stock_data(db: Session, ticker: str, fetch_if_outdated=False):
    """Get the latest stock data from the database or fetch if outdated"""
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        # Stock not in database, fetch it
        if fetch_if_outdated:
            logger.info(f"Stock {ticker} not found in database, fetching new data")
            asyncio.run(fetch_and_save_single_stock(db, ticker))
            clear_cache(prefix=f"get_latest_stock_data-{ticker}")  # Clear cache for this ticker
            return get_latest_stock_data(db, ticker, False)  # Try again but don't fetch again
        return None
    
    # Get latest price data
    latest_price = db.query(StockPrice).filter(
        StockPrice.stock_id == stock.id
    ).order_by(StockPrice.date.desc()).first()
    
    # Get latest indicators
    latest_indicators = db.query(StockIndicator).filter(
        StockIndicator.stock_id == stock.id
    ).order_by(StockIndicator.date.desc()).first()
    
    # Check if data is outdated (last updated more than 24 hours ago)
    if fetch_if_outdated and latest_price and datetime.now() - latest_price.date > timedelta(hours=24):
        logger.info(f"Data for {ticker} is outdated, fetching new data")
        asyncio.run(fetch_and_save_single_stock(db, ticker))
        clear_cache(prefix=f"get_latest_stock_data-{ticker}")  # Clear cache for this ticker
        return get_latest_stock_data(db, ticker, False)  # Try again but don't fetch again
    
    return {
        'stock': stock,
        'latest_price': latest_price,
        'latest_indicators': latest_indicators
    }

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
                save_stock_data(db, ticker, df, company_info)
                logger.info(f"Successfully saved data for {ticker}")
            else:
                logger.warning(f"No data fetched for {ticker}")
        except Exception as e:
            logger.error(f"Error processing {ticker}: {str(e)}")
        
        # Delay minimal antara API calls
        time.sleep(random.uniform(0.5, 1.0))

async def fetch_and_save_single_stock(db: Session, ticker: str):
    """Fetch and save data for a single stock with fallback to database"""
    try:
        logger.info(f"Fetching data for {ticker}...")
        # Pass db session to enable fallback to database
        df, company_info = await fetch_stock_data(ticker, db=db)
        if not df.empty:
            save_stock_data(db, ticker, df, company_info)
            logger.info(f"Successfully saved data for {ticker}")
            return True
        else:
            logger.warning(f"No data fetched for {ticker}")
            return False
    except Exception as e:
        logger.error(f"Error processing {ticker}: {str(e)}")
        return False
