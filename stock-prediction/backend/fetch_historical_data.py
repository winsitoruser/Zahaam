"""
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpannya ke database.
Data akan diambil untuk periode 2-3 tahun ke belakang dengan interval data terkecil yang tersedia.
"""

import yfinance as yf
import pandas as pd
import pandas_ta as ta
import numpy as np
from datetime import datetime, timedelta
import time
import logging
import os
import sys

# Tambahkan path untuk mengakses modul aplikasi
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from app.core.database import engine, Base, SessionLocal, get_db
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.data.indonesian_stocks import INDONESIAN_STOCKS
from sqlalchemy.orm import Session
from sqlalchemy import func

# Konfigurasi logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

def get_db_session():
    """Fungsi untuk mendapatkan session database."""
    db = SessionLocal()
    try:
        return db
    finally:
        db.close()

def get_all_tickers():
    """Mendapatkan semua ticker saham Indonesia."""
    return [stock["ticker"] for stock in INDONESIAN_STOCKS]

def save_stock_data(ticker, data, db):
    """
    Menyimpan data historis saham ke dalam database.
    
    Args:
        ticker (str): Kode saham
        data (pandas.DataFrame): Data historis dari Yahoo Finance
        db (Session): Session database
    """
    # Check if data is empty
    if data is None or data.empty:
        logger.warning(f"Empty data for {ticker}")
        return
    # Periksa apakah saham sudah ada di database
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    
    if not stock:
        # Jika belum ada, tambahkan data saham baru
        company_info = None
        stock_info = None
        
        try:
            # Ambil info perusahaan dari Yahoo Finance
            yf_stock = yf.Ticker(ticker)
            stock_info = yf_stock.info
            
            company_info = {
                'name': stock_info.get('longName', ticker),
                'sector': stock_info.get('sector', 'Unknown'),
                'industry': stock_info.get('industry', 'Unknown'),
            }
        except Exception as e:
            logger.error(f"Error fetching company info for {ticker}: {str(e)}")
            company_info = {
                'name': ticker,
                'sector': 'Unknown',
                'industry': 'Unknown',
            }
        
        # Temukan info dari INDONESIAN_STOCKS jika ada
        for indo_stock in INDONESIAN_STOCKS:
            if indo_stock["ticker"] == ticker:
                company_info = {
                    'name': indo_stock.get('name', company_info['name']),
                    'sector': indo_stock.get('sector', company_info['sector']),
                    'industry': indo_stock.get('industry', company_info['industry']),
                }
                break
        
        # Buat entitas Stock baru
        stock = Stock(
            ticker=ticker,
            name=company_info['name'],
            sector=company_info['sector'],
            industry=company_info['industry'],
            is_active=True,
            last_updated=datetime.now()
        )
        db.add(stock)
        db.commit()
        db.refresh(stock)
        logger.info(f"Added new stock: {ticker}")
    else:
        # Update last_updated timestamp
        stock.last_updated = datetime.now()
        db.commit()
    
    # Format data untuk penyimpanan
    # Reset index untuk mengubah Date/Datetime menjadi kolom
    data = data.reset_index()
    
    # Check if 'Date' column exists, if not, see if 'Datetime' column exists
    date_column = None
    if 'Date' in data.columns:
        date_column = 'Date'
    elif 'Datetime' in data.columns:
        date_column = 'Datetime'
    
    if date_column is None:
        logger.error(f"No date column found in data for {ticker}")
        return
        
        # Simpan setiap baris data historis
        count = 0
        for _, row in data.iterrows():
            # Periksa apakah data untuk tanggal tersebut sudah ada
            existing = db.query(StockPrice).filter(
                StockPrice.stock_id == stock.id,
                StockPrice.date == row[date_column]
            ).first()
            
            if not existing:
                # Membuat entitas StockPrice baru
                stock_price = StockPrice(
                    stock_id=stock.id,
                    date=row[date_column],
                    open=row['Open'] if not pd.isna(row['Open']) else None,
                    high=row['High'] if not pd.isna(row['High']) else None,
                    low=row['Low'] if not pd.isna(row['Low']) else None,
                    close=row['Close'] if not pd.isna(row['Close']) else None,
                    volume=int(row['Volume']) if not pd.isna(row['Volume']) else 0
                )
                db.add(stock_price)
                count += 1
        
        if count > 0:
            db.commit()
            logger.info(f"Added {count} new price records for {ticker}")
        else:
            logger.info(f"No new price data for {ticker}")
            
        # Hitung indikator teknikal
        try:
            calculate_technical_indicators(ticker, db)
        except Exception as e:
            logger.error(f"Error calculating technical indicators for {ticker}: {str(e)}")

def calculate_technical_indicators(ticker, db):
    """
    Menghitung indikator teknikal untuk saham.
    
    Args:
        ticker (str): Kode saham
        db (Session): Session database
    """
    try:
        # Ambil stock_id
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            logger.error(f"Stock {ticker} not found in database")
            return
        
        # Ambil data harga saham dari database
        prices = db.query(StockPrice).filter(
            StockPrice.stock_id == stock.id
        ).order_by(StockPrice.date).all()
        
        if not prices:
            logger.error(f"No price data for {ticker}")
            return
            
    except Exception as e:
        logger.error(f"Error calculating technical indicators for {ticker}: {str(e)}")
        return
    
    # Konversi ke DataFrame
    df = pd.DataFrame([
        {
            'Date': p.date,
            'Open': p.open,
            'High': p.high,
            'Low': p.low,
            'Close': p.close,
            'Volume': p.volume
        } for p in prices
    ])
    
    # Pastikan ada cukup data untuk menghitung indikator
    if len(df) < 200:
        logger.warning(f"Not enough data to calculate all indicators for {ticker}")
        if len(df) < 20:
            logger.error(f"Insufficient data for {ticker}")
            return
    
    # Hitung indikator teknikal dasar
    df['SMA_20'] = ta.sma(df['Close'], length=20) if len(df) >= 20 else None
    df['SMA_50'] = ta.sma(df['Close'], length=50) if len(df) >= 50 else None
    df['SMA_200'] = ta.sma(df['Close'], length=200) if len(df) >= 200 else None
    df['EMA_12'] = ta.ema(df['Close'], length=12) if len(df) >= 12 else None
    df['EMA_26'] = ta.ema(df['Close'], length=26) if len(df) >= 26 else None
    df['RSI'] = ta.rsi(df['Close'], length=14) if len(df) >= 14 else None
    
    # Hitung MACD
    try:
        macd = ta.macd(df['Close'])
        df['MACD'] = macd['MACD_12_26_9']
        df['MACD_Signal'] = macd['MACDs_12_26_9']
        df['MACD_Hist'] = macd['MACDh_12_26_9']
    except Exception as e:
        logger.warning(f"Error calculating MACD for {ticker}: {str(e)}")
        df['MACD'] = None
        df['MACD_Signal'] = None
        df['MACD_Hist'] = None
    
    # Hitung Bollinger Bands
    try:
        bbands = ta.bbands(df['Close'])
        df['BB_Upper'] = bbands['BBU_20_2.0']
        df['BB_Middle'] = bbands['BBM_20_2.0']
        df['BB_Lower'] = bbands['BBL_20_2.0']
    except Exception as e:
        logger.warning(f"Error calculating Bollinger Bands for {ticker}: {str(e)}")
        df['BB_Upper'] = None
        df['BB_Middle'] = None
        df['BB_Lower'] = None
    
    # Replace NaN with None for database storage
    df = df.replace({np.nan: None})
    
    # Simpan indikator ke database
    count = 0
    for _, row in df.iterrows():
        # Periksa apakah data untuk tanggal tersebut sudah ada
        existing = db.query(StockIndicator).filter(
            StockIndicator.stock_id == stock.id,
            StockIndicator.date == row['Date']
        ).first()
        
        if existing:
            # Update indikator yang sudah ada
            existing.sma_20 = row['SMA_20']
            existing.sma_50 = row['SMA_50']
            existing.sma_200 = row['SMA_200']
            existing.ema_12 = row['EMA_12']
            existing.ema_26 = row['EMA_26']
            existing.rsi = row['RSI']
            existing.macd = row['MACD']
            existing.macd_signal = row['MACD_Signal']
            existing.macd_hist = row['MACD_Hist']
            existing.bb_upper = row['BB_Upper']
            existing.bb_middle = row['BB_Middle']
            existing.bb_lower = row['BB_Lower']
        else:
            # Buat indikator baru
            indicator = StockIndicator(
                stock_id=stock.id,
                date=row['Date'],
                sma_20=row['SMA_20'],
                sma_50=row['SMA_50'],
                sma_200=row['SMA_200'],
                ema_12=row['EMA_12'],
                ema_26=row['EMA_26'],
                rsi=row['RSI'],
                macd=row['MACD'],
                macd_signal=row['MACD_Signal'],
                macd_hist=row['MACD_Hist'],
                bb_upper=row['BB_Upper'],
                bb_middle=row['BB_Middle'],
                bb_lower=row['BB_Lower']
            )
            db.add(indicator)
            count += 1
    
    if count > 0:
        db.commit()
        logger.info(f"Added/updated {count} technical indicators for {ticker}")

def fetch_historical_data(ticker, period="3y", interval="1d"):
    """
    Mengambil data historis dari Yahoo Finance.
    
    Args:
        ticker (str): Kode saham
        period (str): Periode data ('1d', '5d', '1mo', '3mo', '6mo', '1y', '2y', '3y', '5y', '10y', 'ytd', 'max')
        interval (str): Interval data ('1d', '1wk', '1mo', '1m', '5m', '15m', '30m', '60m', '1h', '1d', '5d', '1mo')
    """
    try:
        # Ambil data dari Yahoo Finance
        stock = yf.Ticker(ticker)
        df = stock.history(period=period, interval=interval)
        
        if df.empty:
            logger.warning(f"No data returned for {ticker}")
            return None
        
        return df
    except Exception as e:
        logger.error(f"Error fetching data for {ticker}: {str(e)}")
        return None

def fetch_and_save_all():
    """Ambil dan simpan data historis untuk semua saham Indonesia."""
    db = get_db_session()
    tickers = get_all_tickers()
    
    logger.info(f"Starting historical data fetch for {len(tickers)} Indonesian stocks")
    
    # Periode dan interval yang akan diambil
    periods_intervals = [
        ("3y", "1d"),     # Data harian untuk 3 tahun
        ("1mo", "1h"),    # Data jam-an untuk 1 bulan
        ("7d", "15m"),    # Data 15 menit untuk 1 minggu
        ("1d", "5m"),     # Data 5 menit untuk 1 hari
    ]
    
    # Ambil semua saham Indonesia
    # tickers = tickers[:5]  # Uncomment untuk pengujian dengan 5 saham saja
    
    for i, ticker in enumerate(tickers):
        logger.info(f"Processing {ticker} ({i+1}/{len(tickers)})")
        
        # Ambil data dengan berbagai periode dan interval
        for period, interval in periods_intervals:
            logger.info(f"Fetching {ticker} with period={period}, interval={interval}")
            data = fetch_historical_data(ticker, period, interval)
            
            if data is not None and not data.empty:
                save_stock_data(ticker, data, db)
                logger.info(f"Saved {ticker} data with {interval} interval for {period} period")
            else:
                logger.warning(f"No data retrieved for {ticker} with period={period}, interval={interval}")
            
            # Beri waktu jeda untuk menghindari rate limiting
            time.sleep(1)
    
    logger.info("Completed fetching and saving historical data")

if __name__ == "__main__":
    fetch_and_save_all()
