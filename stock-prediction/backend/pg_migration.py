"""
Script sederhana untuk membuat tabel di PostgreSQL dan memigrasikan data dari SQLite
Tidak memerlukan Alembic, menggunakan SQLAlchemy langsung

Instruksi penggunaan:
1. Pastikan PostgreSQL sudah terinstall
2. Buat database 'stock_prediction' di PostgreSQL
3. Sesuaikan kredensial database di bawah jika perlu
4. Jalankan script ini dengan: python pg_migration.py
"""
import os
import sqlite3
import logging
import sys
import json
import time
from datetime import datetime
import traceback

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler("migration.log"),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Database connection settings
SQLITE_DB_PATH = 'stock_prediction.db'

# User input untuk kredensial PostgreSQL
DEFAULT_PG_USER = 'zahaam'
DEFAULT_PG_PASSWORD = 'zahaampassword'
DEFAULT_PG_HOST = 'localhost'
DEFAULT_PG_PORT = '5432'
DEFAULT_PG_DB = 'stock_prediction'

# Cek apakah ada argumen --non-interactive
NON_INTERACTIVE = '--non-interactive' in sys.argv

# Gunakan environment variables jika tersedia
pg_user = os.getenv('POSTGRES_USER', DEFAULT_PG_USER)
pg_password = os.getenv('POSTGRES_PASSWORD', DEFAULT_PG_PASSWORD)
pg_host = os.getenv('POSTGRES_HOST', DEFAULT_PG_HOST)
pg_port = os.getenv('POSTGRES_PORT', DEFAULT_PG_PORT)
pg_db = os.getenv('POSTGRES_DB', DEFAULT_PG_DB)

# Jika tidak dalam mode non-interactive, minta input dari user
if not NON_INTERACTIVE:
    print("===== MIGRASI DATABASE SQLITE KE POSTGRESQL =====\n")
    print("Masukkan kredensial PostgreSQL (atau tekan Enter untuk nilai default):")
    
    pg_user = input(f"Username PostgreSQL [{pg_user}]: ") or pg_user
    pg_password = input(f"Password PostgreSQL [{pg_password}]: ") or pg_password
    pg_host = input(f"Host PostgreSQL [{pg_host}]: ") or pg_host
    pg_port = input(f"Port PostgreSQL [{pg_port}]: ") or pg_port
    pg_db = input(f"Nama Database PostgreSQL [{pg_db}]: ") or pg_db
else:
    print("===== MIGRASI DATABASE SQLITE KE POSTGRESQL (MODE NON-INTERAKTIF) =====\n")

POSTGRES_URL = f"postgresql://{pg_user}:{pg_password}@{pg_host}:{pg_port}/{pg_db}"
print(f"\nMenggunakan koneksi PostgreSQL: {POSTGRES_URL.replace(pg_password, '****')}")

def setup_database_config():
    """Setup database configuration for migration"""
    try:
        # Update database.py temporarily to use PostgreSQL
        db_config_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'app', 'core', 'database.py')
        if os.path.exists(db_config_path):
            with open(db_config_path, 'r') as f:
                content = f.read()
            
            # Backup original file
            with open(f"{db_config_path}.bak", 'w') as f:
                f.write(content)
            
            # Update to use PostgreSQL URL
            updated_content = content.replace(
                "DATABASE_URL = \"sqlite:///./stock_prediction.db\"", 
                f"DATABASE_URL = \"{POSTGRES_URL}\""
            )
            updated_content = updated_content.replace(
                "engine = create_engine(\n    DATABASE_URL, connect_args={\"check_same_thread\": False}\n)", 
                "engine = create_engine(DATABASE_URL)"
            )
            
            # Write updated config
            with open(db_config_path, 'w') as f:
                f.write(updated_content)
            
            logger.info("Database configuration updated to use PostgreSQL")
            return True
        else:
            logger.error(f"Database config file not found at {db_config_path}")
            return False
    
    except Exception as e:
        logger.error(f"Error updating database config: {str(e)}")
        return False

def restore_database_config():
    """Restore original database configuration"""
    try:
        db_config_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'app', 'core', 'database.py')
        backup_path = f"{db_config_path}.bak"
        
        if os.path.exists(backup_path):
            with open(backup_path, 'r') as f:
                original_content = f.read()
            
            with open(db_config_path, 'w') as f:
                f.write(original_content)
            
            os.remove(backup_path)
            logger.info("Original database configuration restored")
        else:
            logger.warning("No backup file found, skipping restore")
    
    except Exception as e:
        logger.error(f"Error restoring database config: {str(e)}")

def create_tables():
    """Create tables in PostgreSQL using SQL commands directly"""
    try:
        import psycopg2
        from psycopg2 import sql
        
        logger.info("Menghubungkan ke PostgreSQL...")
        conn = psycopg2.connect(
            host=pg_host,
            database=pg_db,
            user=pg_user,
            password=pg_password,
            port=pg_port
        )
        conn.autocommit = True
        cursor = conn.cursor()
        
        # SQL for creating tables
        create_stocks_table = """
        CREATE TABLE IF NOT EXISTS stocks (
            id SERIAL PRIMARY KEY,
            ticker VARCHAR(20) NOT NULL,
            name VARCHAR(100),
            sector VARCHAR(50),
            is_active BOOLEAN DEFAULT true,
            last_updated TIMESTAMP
        );
        """
        
        create_stock_prices_table = """
        CREATE TABLE IF NOT EXISTS stock_prices (
            id SERIAL PRIMARY KEY,
            stock_id INTEGER REFERENCES stocks(id),
            date TIMESTAMP NOT NULL,
            interval VARCHAR(10) NOT NULL,
            open FLOAT,
            high FLOAT,
            low FLOAT,
            close FLOAT,
            volume BIGINT
        );
        """
        
        create_stock_indicators_table = """
        CREATE TABLE IF NOT EXISTS stock_indicators (
            id SERIAL PRIMARY KEY,
            stock_id INTEGER REFERENCES stocks(id),
            date TIMESTAMP NOT NULL,
            interval VARCHAR(10) NOT NULL,
            sma_20 FLOAT,
            sma_50 FLOAT,
            sma_200 FLOAT,
            ema_12 FLOAT,
            ema_26 FLOAT,
            rsi_14 FLOAT,
            macd FLOAT,
            macd_signal FLOAT,
            macd_histogram FLOAT,
            bb_upper FLOAT,
            bb_middle FLOAT,
            bb_lower FLOAT
        );
        """
        
        # Create tables
        cursor.execute(create_stocks_table)
        cursor.execute(create_stock_prices_table)
        cursor.execute(create_stock_indicators_table)
        
        logger.info("Tables created successfully")
        
        # Close connection
        cursor.close()
        conn.close()
        
        return True
    
    except Exception as e:
        logger.error(f"Error creating tables: {str(e)}")
        logger.error(traceback.format_exc())
        return False

def migrate_data():
    """Migrate data from SQLite to PostgreSQL using direct SQL commands"""
    try:
        # Check if SQLite database exists
        if not os.path.exists(SQLITE_DB_PATH):
            logger.error(f"SQLite database {SQLITE_DB_PATH} tidak ditemukan.")
            return False
        
        logger.info(f"Menghubungkan ke database SQLite {SQLITE_DB_PATH}...")
        sqlite_conn = sqlite3.connect(SQLITE_DB_PATH)
        sqlite_cursor = sqlite_conn.cursor()
        
        # Connect to PostgreSQL
        import psycopg2
        logger.info(f"Menghubungkan ke PostgreSQL...")
        pg_conn = psycopg2.connect(
            host=pg_host,
            database=pg_db,
            user=pg_user,
            password=pg_password,
            port=pg_port
        )
        pg_conn.autocommit = False
        pg_cursor = pg_conn.cursor()
        
        # Get list of tables from SQLite
        sqlite_cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
        tables = sqlite_cursor.fetchall()
        
        if not tables:
            logger.warning("Tidak ada tabel yang ditemukan di database SQLite.")
            return False
        
        logger.info(f"Ditemukan {len(tables)} tabel untuk dimigrasi.")
        
        # Migrate each table
        for table in tables:
            table_name = table[0]
            
            # Skip SQLite internal tables
            if table_name.startswith('sqlite_'):
                continue
                
            logger.info(f"Memigrasikan tabel {table_name}...")
            
            # Get column names from SQLite table
            sqlite_cursor.execute(f"PRAGMA table_info({table_name});")
            columns = sqlite_cursor.fetchall()
            column_names = [col[1] for col in columns]
            
            # Get data from SQLite
            sqlite_cursor.execute(f"SELECT * FROM {table_name};")
            rows = sqlite_cursor.fetchall()
            
            if not rows:
                logger.warning(f"Tabel {table_name} kosong, melewati migrasi.")
                continue
            
            logger.info(f"Ditemukan {len(rows)} baris di tabel {table_name}")
            
            try:
                # Begin transaction
                pg_cursor.execute("BEGIN;")
                
                # Clear existing data in PostgreSQL table
                pg_cursor.execute(f"TRUNCATE TABLE {table_name} RESTART IDENTITY CASCADE;")
                
                # Prepare placeholders for INSERT
                placeholders = ", ".join("%s" for _ in column_names)
                columns_str = ", ".join(column_names)
                
                # Batch insert
                batch_size = 1000
                for i in range(0, len(rows), batch_size):
                    batch = rows[i:i+batch_size]
                    pg_cursor.executemany(
                        f"INSERT INTO {table_name} ({columns_str}) VALUES ({placeholders})",
                        batch
                    )
                    logger.info(f"Menyimpan batch {i//batch_size + 1}/{(len(rows)-1)//batch_size + 1} ke tabel {table_name}")
                
                # Commit transaction
                pg_conn.commit()
                logger.info(f"Berhasil memigrasikan {len(rows)} baris dari tabel {table_name}.")
                
            except Exception as e:
                pg_conn.rollback()
                logger.error(f"Error saat memigrasikan tabel {table_name}: {str(e)}")
                logger.error(traceback.format_exc())
        
        # Close connections
        sqlite_cursor.close()
        sqlite_conn.close()
        pg_cursor.close()
        pg_conn.close()
        
        logger.info("Proses migrasi data selesai.")
        return True
    
    except Exception as e:
        logger.error(f"Error dalam proses migrasi: {str(e)}")
        logger.error(traceback.format_exc())
        return False

def test_connection():
    """Test connection to PostgreSQL database"""
    try:
        import psycopg2
        
        logger.info(f"Testing connection to PostgreSQL at {pg_host}:{pg_port}...")
        conn = psycopg2.connect(
            host=pg_host,
            database=pg_db,
            user=pg_user,
            password=pg_password,
            port=pg_port
        )
        
        cursor = conn.cursor()
        cursor.execute("SELECT version();")
        version = cursor.fetchone()
        
        logger.info(f"PostgreSQL connection successful! Server version: {version[0]}")
        
        cursor.close()
        conn.close()
        return True
    except Exception as e:
        logger.error(f"PostgreSQL connection failed: {str(e)}")
        logger.error(traceback.format_exc())
        return False

def import_indonesian_stocks():
    """Import Indonesian stocks data ke PostgreSQL"""
    try:
        import psycopg2
        from datetime import datetime
        
        # Coba mengimport daftar saham dari file
        sys.path.append(os.path.dirname(os.path.abspath(__file__)))
        try:
            from app.data.indonesian_stocks import INDONESIAN_STOCKS
            logger.info(f"Ditemukan {len(INDONESIAN_STOCKS)} saham Indonesia dari modul")
        except ImportError:
            logger.warning("Tidak bisa mengimport INDONESIAN_STOCKS, membuat data dummy")
            # Dummy data if import fails
            INDONESIAN_STOCKS = {
                "BBCA.JK": {"name": "Bank Central Asia Tbk", "sector": "Finance"},
                "BBRI.JK": {"name": "Bank Rakyat Indonesia Tbk", "sector": "Finance"},
                "TLKM.JK": {"name": "Telekomunikasi Indonesia Tbk", "sector": "Communication"},
                "ASII.JK": {"name": "Astra International Tbk", "sector": "Consumer Goods"},
                "UNVR.JK": {"name": "Unilever Indonesia Tbk", "sector": "Consumer Goods"},
                "WIKA.JK": {"name": "Wijaya Karya Tbk", "sector": "Construction"}
            }
        
        # Connect to PostgreSQL
        conn = psycopg2.connect(
            host=pg_host,
            database=pg_db,
            user=pg_user,
            password=pg_password,
            port=pg_port
        )
        conn.autocommit = True
        cursor = conn.cursor()
        
        # Check if stocks table is empty
        cursor.execute("SELECT COUNT(*) FROM stocks;")
        count = cursor.fetchone()[0]
        
        if count > 0:
            logger.info(f"Tabel stocks sudah berisi {count} data, melewati import")
            return True
        
        # Import stocks data
        logger.info("Mengimport data saham Indonesia ke database...")
        
        # Prepare data for insertion
        now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        values = []
        for ticker, info in INDONESIAN_STOCKS.items():
            values.append((ticker, info["name"], info["sector"], True, now))
        
        # Insert data
        cursor.executemany(
            "INSERT INTO stocks (ticker, name, sector, is_active, last_updated) VALUES (%s, %s, %s, %s, %s)",
            values
        )
        
        logger.info(f"Berhasil mengimport {len(values)} saham ke database")
        
        # Close connection
        cursor.close()
        conn.close()
        
        return True
    
    except Exception as e:
        logger.error(f"Error saat mengimport data saham: {str(e)}")
        logger.error(traceback.format_exc())
        return False

if __name__ == "__main__":
    logger.info("===== MEMULAI PROSES MIGRASI KE POSTGRESQL =====\n")
    
    # Test connection
    if not test_connection():
        logger.error("Koneksi ke PostgreSQL gagal. Silakan periksa kembali pengaturan koneksi.")
        sys.exit(1)
    
    # Update database configuration
    print("\nMengganti konfigurasi database untuk PostgreSQL...")
    setup_database_config()
    
    try:
        # Create tables
        print("\nMembuat struktur tabel di PostgreSQL...")
        if not create_tables():
            logger.error("Gagal membuat tabel. Keluar dari program.")
            sys.exit(1)
        
        # Import Indonesian stocks data
        print("\nMemeriksa dan mengimport data saham Indonesia...")
        import_indonesian_stocks()
        
        # Migrate data from SQLite if exists
        print("\nMemeriksa database SQLite dan memigrasikan data...")
        if os.path.exists(SQLITE_DB_PATH):
            success = migrate_data()
            if success:
                logger.info("Migrasi data dari SQLite ke PostgreSQL berhasil!")
            else:
                logger.error("Migrasi data dari SQLite gagal.")
        else:
            logger.warning(f"Database SQLite {SQLITE_DB_PATH} tidak ditemukan, melewati migrasi data")
            
        print("\n===== PROSES MIGRASI SELESAI =====")
        print("\nLangkah selanjutnya:")
        print("1. Ubah DATABASE_URL di app/core/database.py untuk menggunakan PostgreSQL")
        print("2. Pastikan docker-compose.yml sudah menggunakan volume postgres_data")
        print("3. Jalankan fetch_historical_data_improved.py untuk mengambil data saham")
        print("4. Jalankan aplikasi dengan 'uvicorn app.main:app --reload'")
        
    except Exception as e:
        logger.error(f"Error tidak terduga: {str(e)}")
        logger.error(traceback.format_exc())
    
    finally:
        # Restore original database configuration
        print("\nMengembalikan konfigurasi database asli...")
        restore_database_config()
