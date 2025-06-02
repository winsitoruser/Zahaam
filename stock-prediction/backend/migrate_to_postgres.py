"""
Script untuk memigrasikan data dari SQLite ke PostgreSQL
"""
import sqlite3
import pandas as pd
from sqlalchemy import create_engine
from tqdm import tqdm
import os
from dotenv import load_dotenv
import logging

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# Load environment variables
load_dotenv()

# Database connection settings
SQLITE_DB_PATH = 'stock_prediction.db'
POSTGRES_URL = os.getenv("DATABASE_URL", "postgresql://zahaam:zahaampassword@localhost:5432/stock_prediction")

def migrate_data():
    """Migrate data from SQLite to PostgreSQL"""
    
    # Check if SQLite database exists
    if not os.path.exists(SQLITE_DB_PATH):
        logger.error(f"SQLite database {SQLITE_DB_PATH} tidak ditemukan.")
        return False
    
    # Connect to SQLite database
    logger.info(f"Menghubungkan ke database SQLite {SQLITE_DB_PATH}...")
    sqlite_conn = sqlite3.connect(SQLITE_DB_PATH)
    
    # Connect to PostgreSQL
    logger.info(f"Menghubungkan ke PostgreSQL di {POSTGRES_URL}...")
    pg_engine = create_engine(POSTGRES_URL)
    
    # Get list of tables from SQLite
    cursor = sqlite_conn.cursor()
    cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
    tables = cursor.fetchall()
    
    if not tables:
        logger.warning("Tidak ada tabel yang ditemukan di database SQLite.")
        return False
    
    logger.info(f"Ditemukan {len(tables)} tabel untuk dimigrasi.")
    
    # Migrate each table
    for table in tqdm(tables, desc="Migrasi Tabel"):
        table_name = table[0]
        logger.info(f"Memigrasikan tabel {table_name}...")
        
        # Read data from SQLite
        df = pd.read_sql_query(f"SELECT * FROM {table_name}", sqlite_conn)
        
        if df.empty:
            logger.warning(f"Tabel {table_name} kosong, melewati migrasi.")
            continue
        
        # Write data to PostgreSQL
        try:
            df.to_sql(table_name, pg_engine, if_exists='replace', index=False)
            logger.info(f"Berhasil memigrasikan {len(df)} baris dari tabel {table_name}.")
        except Exception as e:
            logger.error(f"Error saat memigrasikan tabel {table_name}: {str(e)}")
    
    logger.info("Proses migrasi selesai.")
    return True

if __name__ == "__main__":
    logger.info("Memulai proses migrasi dari SQLite ke PostgreSQL...")
    
    success = migrate_data()
    
    if success:
        logger.info("Migrasi berhasil diselesaikan. Silakan verifikasi data di PostgreSQL.")
    else:
        logger.error("Migrasi tidak berhasil. Silakan cek log untuk detail.")
