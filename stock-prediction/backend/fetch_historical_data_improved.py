"""
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""
import yfinance as yf
import pandas as pd
import pandas_ta as ta
import numpy as np
from sqlalchemy import create_engine, text
from sqlalchemy.orm import sessionmaker
import time
import logging
import os
from datetime import datetime
from tqdm import tqdm
import traceback
from dotenv import load_dotenv
import sys

# Tambahkan path untuk import modul app
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

# Import models dan data
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.data.indonesian_stocks import INDONESIAN_STOCKS
from app.core.database import engine, SessionLocal

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler("fetch_historical.log"),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Load environment variables
load_dotenv()

# Intervals and periods untuk data yang berbeda
INTERVALS_AND_PERIODS = [
    {"interval": "1d", "period": "3y"},    # Data harian untuk 3 tahun
    {"interval": "1h", "period": "1mo"},   # Data jam untuk 1 bulan
    {"interval": "15m", "period": "7d"},   # Data 15 menit untuk 7 hari
    {"interval": "5m", "period": "1d"}     # Data 5 menit untuk 1 hari
]

# Fungsi untuk menghitung indikator teknikal
def calculate_indicators(df):
    """
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""Menghitung indikator teknikal dari data harga"""
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""
    if df.empty:
        return df
    
    try:
        # Simple Moving Averages
        df['sma_20'] = ta.sma(df['close'], length=20)
        df['sma_50'] = ta.sma(df['close'], length=50)
        df['sma_200'] = ta.sma(df['close'], length=200)
        
        # Exponential Moving Averages
        df['ema_12'] = ta.ema(df['close'], length=12)
        df['ema_26'] = ta.ema(df['close'], length=26)
        
        # RSI
        df['rsi_14'] = ta.rsi(df['close'], length=14)
        
        # MACD
        macd = ta.macd(df['close'])
        df['macd'] = macd['MACD_12_26_9']
        df['macd_signal'] = macd['MACDs_12_26_9']
        df['macd_histogram'] = macd['MACDh_12_26_9']
        
        # Bollinger Bands
        bbands = ta.bbands(df['close'], length=20)
        df['bb_upper'] = bbands['BBU_20_2.0']
        df['bb_middle'] = bbands['BBM_20_2.0']
        df['bb_lower'] = bbands['BBL_20_2.0']
        
        # Replace NaN values with None for database storage
        df = df.replace({np.nan: None})
    except Exception as e:
        logger.error(f"Error saat menghitung indikator: {str(e)}")
    
    return df

# Fungsi untuk mengambil data dari Yahoo Finance dengan sistem retry
def fetch_stock_data(ticker, interval, period, max_retries=3, retry_delay=5):
    """
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""Mengambil data saham dari Yahoo Finance dengan sistem retry"""
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""
    retries = 0
    while retries < max_retries:
        try:
            logger.info(f"Fetching {ticker} with period={period}, interval={interval}")
            stock = yf.Ticker(ticker)
            df = stock.history(period=period, interval=interval)
            
            if df.empty:
                logger.warning(f"No data returned for {ticker}")
                return None
            
            # Persiapkan dataframe
            df = df.reset_index()
            
            # Pastikan kolom 'Date' ada, untuk kompatibilitas dengan berbagai versi yfinance
            if 'Date' in df.columns:
                df['date'] = df['Date']
            elif 'Datetime' in df.columns:
                df['date'] = df['Datetime']
            else:
                logger.error(f"No date/datetime column found in {ticker} data")
                return None
            
            # Rename columns to match database model
            df.columns = [col.lower() for col in df.columns]
            
            # Drop kolom yang tidak diperlukan
            df = df[['date', 'open', 'high', 'low', 'close', 'volume']]
            
            # Convert date ke format string untuk database
            df['date'] = df['date'].dt.strftime('%Y-%m-%d %H:%M:%S')
            
            # Hitung indikator teknikal
            df = calculate_indicators(df)
            
            logger.info(f"Saved {ticker} data with {interval} interval for {period} period")
            return df
        except Exception as e:
            retries += 1
            logger.warning(f"Attempt {retries}/{max_retries} failed for {ticker}: {str(e)}")
            if retries < max_retries:
                time.sleep(retry_delay)
            else:
                logger.error(f"Failed to fetch data for {ticker} after {max_retries} attempts")
                return None

# Fungsi untuk menyimpan data ke database
def save_to_database(ticker, df, interval, session):
    """
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""Menyimpan data saham ke database"""
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""
    try:
        if df is None or df.empty:
            logger.warning(f"No data retrieved for {ticker} with interval={interval}")
            return 0
        
        # Get stock ID from database
        stock = session.query(Stock).filter(Stock.ticker == ticker).first()
        
        if not stock:
            logger.warning(f"Stock {ticker} not found in database")
            return 0
        
        # For each row in dataframe, save to database
        rows_saved = 0
        for _, row in df.iterrows():
            # Check if this price record already exists
            existing = session.query(StockPrice).filter(
                StockPrice.stock_id == stock.id,
                StockPrice.date == row['date'],
                StockPrice.interval == interval
            ).first()
            
            if existing:
                # Update existing record
                existing.open = row['open']
                existing.high = row['high']
                existing.low = row['low']
                existing.close = row['close']
                existing.volume = row['volume']
            else:
                # Create new record
                price = StockPrice(
                    stock_id=stock.id,
                    date=row['date'],
                    interval=interval,
                    open=row['open'],
                    high=row['high'],
                    low=row['low'],
                    close=row['close'],
                    volume=row['volume']
                )
                session.add(price)
            
            # Save indicators if we have them
            indicator_cols = ['sma_20', 'sma_50', 'sma_200', 'ema_12', 'ema_26', 
                             'rsi_14', 'macd', 'macd_signal', 'macd_histogram', 
                             'bb_upper', 'bb_middle', 'bb_lower']
            
            if all(col in row.index for col in indicator_cols):
                # Check if indicator record exists
                existing_indicator = session.query(StockIndicator).filter(
                    StockIndicator.stock_id == stock.id,
                    StockIndicator.date == row['date'],
                    StockIndicator.interval == interval
                ).first()
                
                if existing_indicator:
                    # Update existing record
                    for col in indicator_cols:
                        setattr(existing_indicator, col, row[col])
                else:
                    # Create new indicator record
                    indicator = StockIndicator(
                        stock_id=stock.id,
                        date=row['date'],
                        interval=interval,
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
                        bb_lower=row['bb_lower']
                    )
                    session.add(indicator)
            
            rows_saved += 1
        
        # Commit after each batch of rows for this ticker+interval
        session.commit()
        return rows_saved
    
    except Exception as e:
        logger.error(f"Error saving {ticker} data to database: {str(e)}")
        logger.error(traceback.format_exc())
        session.rollback()
        return 0

def main():
    """
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""Main function to fetch and save historical data"""
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py
3. Jalankan script ini dengan: python fetch_historical_data_improved.py
"""
    logger.info("Starting historical data fetching process")
    
    # Create database session
    session = SessionLocal()
    
    try:
        # Get list of stocks from the database or use INDONESIAN_STOCKS
        db_stocks = session.query(Stock).filter(Stock.is_active == True).all()
        
        if not db_stocks:
            logger.info("No stocks found in database, using predefined list")
            # Ensure stocks are in the database
            for ticker, info in INDONESIAN_STOCKS.items():
                existing = session.query(Stock).filter(Stock.ticker == ticker).first()
                if not existing:
                    stock = Stock(
                        ticker=ticker,
                        name=info['name'],
                        sector=info['sector'],
                        is_active=True,
                        last_updated=datetime.now()
                    )
                    session.add(stock)
            session.commit()
            
            # Query again after adding
            db_stocks = session.query(Stock).filter(Stock.is_active == True).all()
        
        ticker_list = [stock.ticker for stock in db_stocks]
        logger.info(f"Found {len(ticker_list)} stocks to process")
        
        # Process each stock
        for i, ticker in enumerate(tqdm(ticker_list, desc="Processing stocks")):
            logger.info(f"Processing {ticker} ({i+1}/{len(ticker_list)})")
            
            # Update last_updated timestamp for this stock
            stock = session.query(Stock).filter(Stock.ticker == ticker).first()
            stock.last_updated = datetime.now()
            session.commit()
            
            # Process each interval and period
            for config in INTERVALS_AND_PERIODS:
                interval = config["interval"]
                period = config["period"]
                
                # Fetch data from Yahoo Finance
                df = fetch_stock_data(ticker, interval, period)
                
                if df is not None:
                    # Save to database
                    rows_saved = save_to_database(ticker, df, interval, session)
                    logger.info(f"Saved {rows_saved} rows for {ticker} with {interval} interval")
                else:
                    logger.warning(f"No data retrieved for {ticker} with period={period}, interval={interval}")
                
                # Add delay to avoid rate limiting
                time.sleep(1)
        
        logger.info("Completed fetching and saving historical data")
    
    except Exception as e:
        logger.error(f"Error in main process: {str(e)}")
        logger.error(traceback.format_exc())
    
    finally:
        session.close()

if __name__ == "__main__":
    main()
