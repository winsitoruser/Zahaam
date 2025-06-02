"""
Script untuk memeriksa dan memvalidasi struktur dan data database PostgreSQL setelah migrasi
"""
import pandas as pd
from sqlalchemy import create_engine, inspect, text
import os
from dotenv import load_dotenv
import logging
from datetime import datetime

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# Load environment variables
load_dotenv()

# Database connection settings
POSTGRES_URL = os.getenv("DATABASE_URL", "postgresql://zahaam:zahaampassword@localhost:5432/stock_prediction")

def check_database_structure():
    """Memeriksa struktur database PostgreSQL"""
    logger.info(f"Menghubungkan ke PostgreSQL di {POSTGRES_URL}...")
    engine = create_engine(POSTGRES_URL)
    inspector = inspect(engine)
    
    # Get all tables
    tables = inspector.get_table_names()
    logger.info(f"Tabel yang ditemukan: {len(tables)}")
    
    for table in tables:
        columns = inspector.get_columns(table)
        logger.info(f"\nTabel: {table} ({len(columns)} kolom)")
        
        # Print column details
        for column in columns:
            logger.info(f"  - {column['name']}: {column['type']}")
        
        # Count rows
        with engine.connect() as conn:
            result = conn.execute(text(f"SELECT COUNT(*) FROM {table}"))
            count = result.scalar()
            logger.info(f"  Jumlah rows: {count}")
            
            # Show sample data if available
            if count > 0:
                result = conn.execute(text(f"SELECT * FROM {table} LIMIT 2"))
                rows = result.fetchall()
                logger.info(f"  Contoh data:")
                for row in rows:
                    logger.info(f"    {row}")

def check_wika_data():
    """Memeriksa data WIKA.JK"""
    logger.info(f"Memeriksa data WIKA.JK...")
    engine = create_engine(POSTGRES_URL)
    
    try:
        with engine.connect() as conn:
            # Check if WIKA exists in stocks table
            result = conn.execute(text("SELECT id, ticker, name FROM stocks WHERE ticker = 'WIKA.JK'"))
            stock = result.fetchone()
            
            if not stock:
                logger.warning("WIKA.JK tidak ditemukan di tabel stocks")
                return
            
            logger.info(f"WIKA.JK stock_id: {stock[0]}")
            
            # Check stock prices
            result = conn.execute(
                text(f"SELECT COUNT(*) FROM stock_prices WHERE stock_id = {stock[0]}")
            )
            count = result.scalar()
            
            if count == 0:
                logger.warning(f"Tidak ada data harga untuk WIKA.JK")
                return
            
            logger.info(f"Jumlah data harga untuk WIKA.JK: {count}")
            
            # Check data for each interval
            for interval in ["1d", "1h", "15m", "5m"]:
                result = conn.execute(
                    text(f"SELECT COUNT(*) FROM stock_prices WHERE stock_id = {stock[0]} AND interval = '{interval}'")
                )
                count = result.scalar()
                logger.info(f"  Interval {interval}: {count} data")
                
                if count > 0:
                    # Get date range
                    result = conn.execute(
                        text(f"SELECT MIN(date), MAX(date) FROM stock_prices WHERE stock_id = {stock[0]} AND interval = '{interval}'")
                    )
                    date_range = result.fetchone()
                    logger.info(f"    Rentang waktu: {date_range[0]} hingga {date_range[1]}")
                    
                    # Get sample data
                    result = conn.execute(
                        text(f"SELECT date, open, high, low, close, volume FROM stock_prices WHERE stock_id = {stock[0]} AND interval = '{interval}' ORDER BY date DESC LIMIT 2")
                    )
                    rows = result.fetchall()
                    logger.info(f"    Contoh data terbaru:")
                    for row in rows:
                        logger.info(f"      {row}")
    
    except Exception as e:
        logger.error(f"Error saat memeriksa data WIKA.JK: {str(e)}")

def verify_data_consistency():
    """Memverifikasi konsistensi data antar tabel"""
    logger.info(f"Memverifikasi konsistensi data...")
    engine = create_engine(POSTGRES_URL)
    
    try:
        with engine.connect() as conn:
            # Get total stocks
            result = conn.execute(text("SELECT COUNT(*) FROM stocks"))
            stock_count = result.scalar()
            logger.info(f"Total saham di database: {stock_count}")
            
            # Get active stocks
            result = conn.execute(text("SELECT COUNT(*) FROM stocks WHERE is_active = true"))
            active_count = result.scalar()
            logger.info(f"Total saham aktif: {active_count}")
            
            # Get total price records
            result = conn.execute(text("SELECT COUNT(*) FROM stock_prices"))
            price_count = result.scalar()
            logger.info(f"Total data harga: {price_count}")
            
            # Get distribution by interval
            result = conn.execute(
                text("SELECT interval, COUNT(*) FROM stock_prices GROUP BY interval ORDER BY interval")
            )
            interval_counts = result.fetchall()
            logger.info(f"Distribusi data berdasarkan interval:")
            for interval, count in interval_counts:
                logger.info(f"  {interval}: {count} data")
            
            # Get stocks with most data
            result = conn.execute(
                text("""
                SELECT s.ticker, COUNT(*) as count 
                FROM stock_prices sp 
                JOIN stocks s ON sp.stock_id = s.id 
                GROUP BY s.ticker 
                ORDER BY count DESC 
                LIMIT 5
                """)
            )
            top_stocks = result.fetchall()
            logger.info(f"Saham dengan data terbanyak:")
            for ticker, count in top_stocks:
                logger.info(f"  {ticker}: {count} data")
            
            # Get stocks with least data
            result = conn.execute(
                text("""
                SELECT s.ticker, COUNT(*) as count 
                FROM stock_prices sp 
                JOIN stocks s ON sp.stock_id = s.id 
                GROUP BY s.ticker 
                ORDER BY count ASC 
                LIMIT 5
                """)
            )
            bottom_stocks = result.fetchall()
            logger.info(f"Saham dengan data paling sedikit:")
            for ticker, count in bottom_stocks:
                logger.info(f"  {ticker}: {count} data")
            
            # Check for stocks with no data
            result = conn.execute(
                text("""
                SELECT s.ticker 
                FROM stocks s 
                LEFT JOIN stock_prices sp ON s.id = sp.stock_id 
                WHERE sp.id IS NULL
                """)
            )
            no_data_stocks = result.fetchall()
            logger.info(f"Saham tanpa data harga: {len(no_data_stocks)}")
            if no_data_stocks:
                for stock in no_data_stocks[:10]:  # Show only the first 10 if there are many
                    logger.info(f"  {stock[0]}")
            
    except Exception as e:
        logger.error(f"Error saat verifikasi konsistensi data: {str(e)}")

if __name__ == "__main__":
    logger.info("Memulai pemeriksaan database PostgreSQL...")
    
    # Check database structure
    check_database_structure()
    
    # Check WIKA.JK data specifically
    check_wika_data()
    
    # Verify data consistency
    verify_data_consistency()
    
    logger.info("Pemeriksaan database selesai.")
