"""
Script untuk mengambil data historis saham Indonesia dari Yahoo Finance dan menyimpan ke PostgreSQL.
Versi yang diperbaiki dengan penanganan error, logging yang lebih baik, dan sistem retry.

Cara penggunaan:
1. Pastikan PostgreSQL sudah terinstall dan database sudah dibuat
2. Pastikan kredensial database sudah sesuai di app/core/database.py atau environment variable
3. Jalankan script ini dengan: python fetch_historical_data_pg.py
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
import sys
import traceback
from datetime import datetime
from tqdm import tqdm
from dotenv import load_dotenv
import psycopg2

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler("fetch_historical_pg.log"),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Load environment variables
load_dotenv()

# Tambahkan path untuk import modul app
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

# Database settings
DATABASE_URL = os.getenv("DATABASE_URL")

# Jika DATABASE_URL tidak diatur, coba PostgreSQL terlebih dahulu
if not DATABASE_URL:
    DB_HOST = os.getenv("POSTGRES_HOST", "localhost")
    DB_PORT = os.getenv("POSTGRES_PORT", "5432")
    DB_NAME = os.getenv("POSTGRES_DB", "stock_prediction")
    DB_USER = os.getenv("POSTGRES_USER", "zahaam")
    DB_PASSWORD = os.getenv("POSTGRES_PASSWORD", "zahaampassword")
    
    # Coba koneksi ke PostgreSQL
    try:
        conn = psycopg2.connect(
            host=DB_HOST,
            database=DB_NAME,
            user=DB_USER,
            password=DB_PASSWORD,
            port=DB_PORT
        )
        conn.close()
        # Jika berhasil, gunakan PostgreSQL
        DATABASE_URL = f"postgresql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_NAME}"
        logger.info(f"Menggunakan PostgreSQL di {DB_HOST}:{DB_PORT}")
    except Exception as e:
        logger.warning(f"PostgreSQL tidak tersedia: {str(e)}")
        # Fallback ke SQLite (gunakan absolute path untuk mencegah kesalahan relatif path)
        db_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), "stock_prediction.db")
        DATABASE_URL = f"sqlite:///{db_path}"
        logger.info(f"Menggunakan SQLite sebagai fallback: {DATABASE_URL}")
else:
    logger.info(f"Menggunakan DATABASE_URL dari environment: {DATABASE_URL}")

# Tentukan apakah database adalah PostgreSQL atau SQLite
IS_POSTGRES = DATABASE_URL.startswith("postgresql")

# Intervals dan periods untuk data yang berbeda
INTERVALS_AND_PERIODS = [
    {"interval": "1d", "period": "3y"},    # Data harian untuk 3 tahun
    {"interval": "1h", "period": "1mo"},   # Data jam untuk 1 bulan
    {"interval": "15m", "period": "7d"},   # Data 15 menit untuk 7 hari
    {"interval": "5m", "period": "1d"}     # Data 5 menit untuk 1 hari
]

def upgrade_sqlite_schema(engine):
    """Fungsi untuk mengupgrade skema database SQLite jika perlu"""
    if not engine.url.drivername.startswith('sqlite'):
        return False
    
    from sqlalchemy import inspect, text
    inspector = inspect(engine)
    upgraded = False
    
    # Periksa apakah tabel stock_prices ada dan memiliki kolom interval
    if 'stock_prices' in inspector.get_table_names():
        columns = [col['name'] for col in inspector.get_columns('stock_prices')]
        if 'interval' not in columns:
            # Tambahkan kolom interval ke tabel yang sudah ada
            logger.info("Mengupgrade skema SQLite: menambahkan kolom interval ke tabel stock_prices")
            with engine.connect() as conn:
                conn.execute(text("ALTER TABLE stock_prices ADD COLUMN interval VARCHAR(10) DEFAULT '1d'"))
                conn.commit()
                upgraded = True
    
    # Periksa apakah tabel stock_indicators perlu diupgrade
    if 'stock_indicators' in inspector.get_table_names():
        columns = [col['name'] for col in inspector.get_columns('stock_indicators')]
        if 'interval' not in columns:
            # Tambahkan kolom interval ke tabel stock_indicators
            logger.info("Mengupgrade skema SQLite: menambahkan kolom interval ke tabel stock_indicators")
            with engine.connect() as conn:
                conn.execute(text("ALTER TABLE stock_indicators ADD COLUMN interval VARCHAR(10) DEFAULT '1d'"))
                conn.commit()
                upgraded = True
    
    return upgraded

def check_db_schema(engine):
    """Memeriksa skema database yang ada untuk mendeteksi versi/struktur"""
    from sqlalchemy import inspect, text
    inspector = inspect(engine)
    schema_info = {
        'has_interval_column': False,
    }
    
    # Coba upgrade database terlebih dahulu jika SQLite
    if engine.url.drivername.startswith('sqlite'):
        upgraded = upgrade_sqlite_schema(engine)
        if upgraded:
            logger.info("Database SQLite berhasil diupgrade, memeriksa ulang skema")
            # Refresh inspector setelah upgrade
            inspector = inspect(engine)
    
    try:
        # Periksa apakah tabel stock_prices ada dan memiliki kolom interval
        if 'stock_prices' in inspector.get_table_names():
            columns = [col['name'] for col in inspector.get_columns('stock_prices')]
            schema_info['has_interval_column'] = 'interval' in columns
            logger.info(f"Skema database: tabel stock_prices {'memiliki' if schema_info['has_interval_column'] else 'tidak memiliki'} kolom interval")
        else:
            logger.info("Skema database: tabel stock_prices belum ada")
    except Exception as e:
        logger.warning(f"Gagal memeriksa skema database: {str(e)}")
    
    return schema_info

def setup_database():
    """Mengatur koneksi database dan memastikan tabel ada"""
    global DATABASE_URL, IS_POSTGRES, DB_SCHEMA_INFO
    
    # Initialize variabel global DB_SCHEMA_INFO jika belum ada
    if 'DB_SCHEMA_INFO' not in globals():
        DB_SCHEMA_INFO = {'has_interval_column': False}
    
    # Coba hubungkan ke PostgreSQL jika DATABASE_URL belum diset
    if not DATABASE_URL:
        try:
            # Gunakan koneksi psycopg2 untuk mengecek koneksi langsung
            import psycopg2
            
            conn = psycopg2.connect(
                host=DB_HOST,
                port=DB_PORT,
                database=DB_NAME,
                user=DB_USER,
                password=DB_PASSWORD
            )
            
            # Jika berhasil, gunakan PostgreSQL
            DATABASE_URL = f"postgresql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_NAME}"
            IS_POSTGRES = True
            logger.info(f"Menggunakan PostgreSQL di {DB_HOST}:{DB_PORT}")
            conn.close()  # Tutup koneksi setelah selesai pengujian
        except Exception as e:
            logger.warning(f"PostgreSQL tidak tersedia: {str(e)}")
            # Fallback ke SQLite (gunakan absolute path untuk mencegah kesalahan relatif path)
            db_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), "stock_prediction.db")
            DATABASE_URL = f"sqlite:///{db_path}"
            IS_POSTGRES = False
            logger.info(f"Menggunakan SQLite sebagai fallback: {DATABASE_URL}")
    else:
        logger.info(f"Menggunakan DATABASE_URL dari environment: {DATABASE_URL}")
        IS_POSTGRES = DATABASE_URL.startswith('postgresql')
    
    # Test koneksi dan pemeriksaan database
    try:
        if IS_POSTGRES:
            # Jika PostgreSQL, uji koneksi dengan psycopg2
            logger.info("Menguji koneksi ke PostgreSQL...")
            try:
                import psycopg2
                # Ekstrak parameter koneksi dari DATABASE_URL
                from urllib.parse import urlparse
                url = urlparse(DATABASE_URL)
                db_host = url.hostname
                db_port = url.port or 5432
                db_name = url.path[1:]
                db_user = url.username
                db_password = url.password
                
                conn = psycopg2.connect(
                    host=db_host,
                    port=db_port,
                    database=db_name,
                    user=db_user,
                    password=db_password
                )
                cursor = conn.cursor()
                
                # Periksa versi PostgreSQL
                cursor.execute("SELECT version();")
                version = cursor.fetchone()
                logger.info(f"Terhubung ke PostgreSQL: {version[0]}")
                conn.close()
            except Exception as e:
                logger.error(f"Gagal menghubungkan ke PostgreSQL: {str(e)}")
                raise
        else:
            # Jika SQLite, cukup log informasi
            logger.info(f"Menggunakan SQLite: {DATABASE_URL}")
            # Pastikan file SQLite ada
            if DATABASE_URL.startswith('sqlite:///'):
                db_path = DATABASE_URL.replace('sqlite:///', '')
                if not os.path.exists(db_path):
                    logger.warning(f"File database SQLite tidak ditemukan: {db_path}")
                    # SQLAlchemy akan menciptakannya otomatis
        
        # Buat koneksi SQLAlchemy untuk ORM
        engine = create_engine(DATABASE_URL)
        SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
        
        # Periksa skema database yang ada
        DB_SCHEMA_INFO = check_db_schema(engine)
        
        return engine, SessionLocal
    except Exception as e:
        logger.error(f"Error saat setup database: {str(e)}")
        logger.error(traceback.format_exc())
        sys.exit(1)

def get_stock_list():
    """Mendapatkan daftar saham dari database"""
    try:
        # Import here to avoid circular import
        from app.data.indonesian_stocks import INDONESIAN_STOCKS
        return list(INDONESIAN_STOCKS.keys())
    except ImportError:
        logger.warning("Tidak bisa mengimport INDONESIAN_STOCKS, menggunakan daftar default")
        # Daftar default jika tidak bisa import
        return [
            "BBCA.JK", "BBRI.JK", "TLKM.JK", "ASII.JK", 
            "UNVR.JK", "BMRI.JK", "PGAS.JK", "WIKA.JK"
        ]

def get_stock_id(ticker, session):
    """Mendapatkan ID saham dari database, membuat baru jika tidak ada"""
    try:
        # Import di sini untuk menghindari circular import
        from app.models.stocks import Stock
        
        # Cari stock di database
        stock = session.query(Stock).filter(Stock.ticker == ticker).first()
        
        if stock:
            return stock.id
        else:
            # Jika stock tidak ditemukan, coba cari info dari INDONESIAN_STOCKS
            try:
                from app.data.indonesian_stocks import INDONESIAN_STOCKS
                stock_info = INDONESIAN_STOCKS.get(ticker, {})
                name = stock_info.get("name", ticker)
                sector = stock_info.get("sector", "Unknown")
            except ImportError:
                name = ticker
                sector = "Unknown"
            
            # Buat stock baru
            new_stock = Stock(
                ticker=ticker,
                name=name,
                sector=sector,
                is_active=True,
                last_updated=datetime.now()
            )
            session.add(new_stock)
            session.commit()
            
            logger.info(f"Stock baru ditambahkan: {ticker}")
            return new_stock.id
    except Exception as e:
        logger.error(f"Error saat mendapatkan stock_id untuk {ticker}: {str(e)}")
        session.rollback()
        return None

def calculate_indicators(df):
    """Menghitung indikator teknikal dari data harga"""
    try:
        # Pastikan data cukup untuk perhitungan
        if len(df) < 200:
            logger.warning(f"Data terlalu sedikit untuk perhitungan indikator lengkap: {len(df)} baris")
        
        # Membuat kolom indikator default None untuk mencegah error saat tidak ada data cukup
        indicator_columns = ['sma_20', 'sma_50', 'sma_200', 'ema_12', 'ema_26', 'rsi_14',
                            'macd', 'macd_signal', 'macd_histogram',
                            'bb_upper', 'bb_middle', 'bb_lower']
        
        for col in indicator_columns:
            df[col] = None
        
        # Pastikan tidak ada nilai NaN di data harga
        if df['close'].isna().any():
            logger.warning("Terdapat nilai NaN pada kolom 'close', mengisi dengan metode forward fill")
            df['close'] = df['close'].fillna(method='ffill')
            
        # Hanya hitung indikator jika data cukup
        if len(df) >= 20:  # Minimal untuk SMA 20
            # SMA
            df['sma_20'] = ta.sma(df['close'], length=20)
            
            # Bollinger Bands
            try:
                bb = ta.bbands(df['close'], length=20)
                df['bb_upper'] = bb['BBU_20_2.0']
                df['bb_middle'] = bb['BBM_20_2.0']
                df['bb_lower'] = bb['BBL_20_2.0']
            except Exception as e:
                logger.warning(f"Gagal menghitung Bollinger Bands: {str(e)}")
        
        if len(df) >= 50:  # Minimal untuk SMA 50
            df['sma_50'] = ta.sma(df['close'], length=50)
        
        if len(df) >= 200:  # Minimal untuk SMA 200
            df['sma_200'] = ta.sma(df['close'], length=200)
        
        # EMA dan indikator lain yang membutuhkan data lebih sedikit
        if len(df) >= 26:  # Minimal untuk EMA 26 dan MACD
            # EMA
            df['ema_12'] = ta.ema(df['close'], length=12)
            df['ema_26'] = ta.ema(df['close'], length=26)
            
            # MACD
            try:
                macd = ta.macd(df['close'])
                if 'MACD_12_26_9' in macd and 'MACDs_12_26_9' in macd and 'MACDh_12_26_9' in macd:
                    df['macd'] = macd['MACD_12_26_9']
                    df['macd_signal'] = macd['MACDs_12_26_9']
                    df['macd_histogram'] = macd['MACDh_12_26_9']
                else:
                    logger.warning("Kolom MACD tidak lengkap dalam hasil perhitungan")
            except Exception as e:
                logger.warning(f"Gagal menghitung MACD: {str(e)}")
        
        if len(df) >= 14:  # Minimal untuk RSI
            # RSI
            df['rsi_14'] = ta.rsi(df['close'], length=14)
        
        # Konversi NaN ke None untuk SQLAlchemy
        for col in indicator_columns:
            if col in df.columns:
                df[col] = df[col].replace({np.nan: None})
        
        logger.info(f"Berhasil menghitung indikator untuk {len(df)} baris data")
        return df
    except Exception as e:
        logger.error(f"Error saat menghitung indikator: {str(e)}")
        logger.error(traceback.format_exc())
        # Return df tanpa indikator jika gagal
        return df

def fetch_stock_data(ticker, interval, period, max_retries=3, retry_delay=5):
    """Mengambil data saham dari Yahoo Finance dengan sistem retry"""
    for attempt in range(1, max_retries + 1):
        try:
            logger.info(f"Mengambil {ticker} dengan period={period}, interval={interval}")
            stock = yf.Ticker(ticker)
            df = stock.history(period=period, interval=interval)
            
            # Periksa apakah data kosong
            if df.empty:
                logger.warning(f"Data kosong untuk {ticker} dengan period={period}, interval={interval}")
                return None
            
            # Reset index untuk mengubah datetime index menjadi kolom
            df = df.reset_index()
            
            # Periksa apakah kolom 'Date' atau 'Datetime' mana yang ada
            date_column = None
            if 'Date' in df.columns:
                date_column = 'Date'
            elif 'Datetime' in df.columns:
                date_column = 'Datetime'
            else:
                logger.error(f"Tidak ada kolom tanggal/waktu dalam data {ticker}")
                return None
            
            # Rename kolom untuk konsistensi
            column_mapping = {
                date_column: 'date',
                'Open': 'open',
                'High': 'high',
                'Low': 'low',
                'Close': 'close',
                'Volume': 'volume'
            }
            # Hanya rename kolom yang ada
            column_mapping = {k: v for k, v in column_mapping.items() if k in df.columns}
            df = df.rename(columns=column_mapping)
            
            # Pastikan tidak ada duplikasi pada kolom date
            if df.duplicated('date').any():
                logger.warning(f"Terdapat duplikasi tanggal untuk {ticker}, menghapus duplikasi...")
                df = df.drop_duplicates('date', keep='first')
            
            # Pastikan kolom tanggal dalam format yang benar
            df['date'] = pd.to_datetime(df['date'])
            
            # Sortir berdasarkan tanggal untuk memastikan urutan yang benar
            df = df.sort_values('date')
            
            # Hitung indikator teknikal
            df = calculate_indicators(df)
            
            logger.info(f"Berhasil mengambil {len(df)} baris data {ticker} dengan interval {interval}")
            return df
        except Exception as e:
            retries += 1
            logger.warning(f"Percobaan {retries}/{max_retries} gagal untuk {ticker}: {str(e)}")
            if retries < max_retries:
                time.sleep(retry_delay)
            else:
                logger.error(f"Gagal mengambil data {ticker} setelah {max_retries} percobaan")
                logger.error(traceback.format_exc())
                return None

# Inisialisasi variabel global untuk informasi skema DB
DB_SCHEMA_INFO = {'has_interval_column': False}

def save_to_database(ticker, df, interval, session):
    """Menyimpan data saham ke database"""
    try:
        from app.models.stocks import StockPrice, StockIndicator
        saved_count = 0
        stock_id = get_stock_id(ticker, session)
        
        if not stock_id:
            logger.error(f"Tidak bisa mendapatkan stock_id untuk {ticker}")
            return 0
        
        # Deteksi skema database
        has_interval_column = DB_SCHEMA_INFO.get('has_interval_column', False)
        logger.info(f"Status skema DB: has_interval_column={has_interval_column}")
        
        if df is None or df.empty:
            logger.warning(f"Tidak ada data untuk {ticker} dengan interval={interval}")
            return 0
        
        for _, row in df.iterrows():
            date = row['date']
            
            # Periksa apakah data sudah ada, dengan handling untuk database lama yang belum punya kolom interval
            if has_interval_column:
                # Database baru dengan kolom interval
                existing_price = session.query(StockPrice).filter(
                    StockPrice.stock_id == stock_id,
                    StockPrice.date == date,
                    StockPrice.interval == interval
                ).first()
            else:
                # Database lama tanpa kolom interval
                existing_price = session.query(StockPrice).filter(
                    StockPrice.stock_id == stock_id,
                    StockPrice.date == date
                ).first()
            
            if existing_price:
                # Update data yang sudah ada
                existing_price.open = row['open']
                existing_price.high = row['high']
                existing_price.low = row['low']
                existing_price.close = row['close']
                existing_price.volume = row['volume']
                
                # Update interval jika database memiliki kolom tersebut
                if has_interval_column:
                    existing_price.interval = interval
            else:
                # Buat data baru
                if has_interval_column:
                    # Database baru dengan kolom interval
                    new_price = StockPrice(
                        stock_id=stock_id,
                        date=date,
                        interval=interval,
                        open=row['open'],
                        high=row['high'],
                        low=row['low'],
                        close=row['close'],
                        volume=row['volume']
                    )
                else:
                    # Database lama tanpa kolom interval
                    new_price = StockPrice(
                        stock_id=stock_id,
                        date=date,
                        open=row['open'],
                        high=row['high'],
                        low=row['low'],
                        close=row['close'],
                        volume=row['volume']
                    )
                session.add(new_price)
            
            # Periksa apakah indikator sudah ada
            if has_interval_column:
                existing_indicator = session.query(StockIndicator).filter(
                    StockIndicator.stock_id == stock_id,
                    StockIndicator.date == date,
                    StockIndicator.interval == interval
                ).first()
            else:
                existing_indicator = session.query(StockIndicator).filter(
                    StockIndicator.stock_id == stock_id,
                    StockIndicator.date == date
                ).first()
            
            if existing_indicator:
                # Update indikator yang sudah ada
                existing_indicator.sma_20 = row.get('sma_20')
                existing_indicator.sma_50 = row.get('sma_50')
                existing_indicator.sma_200 = row.get('sma_200')
                existing_indicator.ema_12 = row.get('ema_12')
                existing_indicator.ema_26 = row.get('ema_26')
                existing_indicator.rsi_14 = row.get('rsi_14')
                existing_indicator.macd = row.get('macd')
                existing_indicator.macd_signal = row.get('macd_signal')
                existing_indicator.macd_histogram = row.get('macd_histogram')
                existing_indicator.bb_upper = row.get('bb_upper')
                existing_indicator.bb_middle = row.get('bb_middle')
                existing_indicator.bb_lower = row.get('bb_lower')
            else:
                # Buat indikator baru
                new_indicator = StockIndicator(
                    stock_id=stock_id,
                    date=date,
                    interval=interval,
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
                    bb_lower=row.get('bb_lower')
                )
                session.add(new_indicator)
            
            saved_count += 1
            
            # Commit setiap 100 baris untuk menghindari transaksi terlalu besar
            if saved_count % 100 == 0:
                session.commit()
        
        # Commit transaksi terakhir
        session.commit()
        logger.info(f"Berhasil menyimpan {saved_count} baris data {ticker} dengan interval {interval}")
        return saved_count
    
    except Exception as e:
        session.rollback()
        logger.error(f"Error saat menyimpan data {ticker}: {str(e)}")
        logger.error(traceback.format_exc())
        return 0

def parse_args():
    """Parse command line arguments"""
    import argparse
    parser = argparse.ArgumentParser(description="Mengambil data historis saham dari Yahoo Finance dan menyimpan ke database")
    
    # Argumen untuk ticker spesifik
    parser.add_argument("--ticker", type=str, help="Ticker saham spesifik untuk diambil, contoh: BBCA.JK")
    
    # Argumen untuk interval dan periode
    parser.add_argument("--interval", type=str, choices=["1d", "1h", "15m", "5m"], 
                        help="Interval data (1d, 1h, 15m, 5m)")
    parser.add_argument("--period", type=str, 
                        help="Periode data (1d, 5d, 1mo, 3mo, 6mo, 1y, 2y, 5y, 10y, ytd, max)")
    
    # Argumen untuk jumlah hari
    parser.add_argument("--days", type=int, help="Jumlah hari data yang diambil (alternatif untuk period)")
    
    # Argumen untuk memproses semua saham
    parser.add_argument("--all", action="store_true", help="Proses semua saham dalam daftar")
    
    return parser.parse_args()

def main():
    """Fungsi utama untuk mengambil dan menyimpan data historis"""
    logger.info("===== MEMULAI PROSES PENGAMBILAN DATA HISTORIS SAHAM =====")
    
    # Parse command line arguments
    args = parse_args()
    
    # Setup database
    engine, SessionLocal = setup_database()
    
    # Buat session database
    session = SessionLocal()
    
    try:
        # Tentukan daftar saham yang akan diproses
        if args.ticker:
            stock_list = [args.ticker]
            logger.info(f"Mengambil data untuk ticker spesifik: {args.ticker}")
        elif args.all:
            stock_list = get_stock_list()
            logger.info(f"Mengambil data untuk semua {len(stock_list)} saham")
        else:
            # Default menggunakan beberapa saham populer
            stock_list = ["BBCA.JK", "BBRI.JK", "TLKM.JK", "ASII.JK", "WIKA.JK"]
            logger.info(f"Mengambil data untuk {len(stock_list)} saham populer")
        
        # Tentukan interval dan periode yang akan diproses
        intervals_to_process = []
        if args.interval and (args.period or args.days):
            # Jika interval dan period/days dispesifikasi, gunakan itu saja
            period = args.period
            if args.days:
                # Konversi days ke period format yfinance
                period = f"{args.days}d"
            intervals_to_process.append({"interval": args.interval, "period": period})
            logger.info(f"Menggunakan interval {args.interval} dengan periode {period}")
        else:
            # Gunakan default
            intervals_to_process = INTERVALS_AND_PERIODS
            logger.info(f"Menggunakan {len(intervals_to_process)} interval default")
        
        # Untuk setiap saham
        for ticker in tqdm(stock_list, desc="Memproses saham"):
            logger.info(f"=== Memproses {ticker} ===")
            
            # Untuk setiap interval
            for config in intervals_to_process:
                interval = config["interval"]
                period = config["period"]
                
                # Ambil data saham
                df = fetch_stock_data(ticker, interval, period)
                
                # Simpan ke database
                if df is not None and not df.empty:
                    rows_saved = save_to_database(ticker, df, interval, session)
                    logger.info(f"Berhasil menyimpan {rows_saved} baris data {ticker} interval {interval}")
                else:
                    logger.warning(f"Tidak ada data untuk disimpan untuk {ticker} interval {interval}")
            
            # Jeda antara saham untuk menghindari rate limiting
            time.sleep(1)
    
    except Exception as e:
        logger.error(f"Error saat memproses data: {str(e)}")
        logger.error(traceback.format_exc())
    
    finally:
        # Tutup session
        session.close()
        logger.info("===== PROSES PENGAMBILAN DATA HISTORIS SAHAM SELESAI =====")

if __name__ == "__main__":
    main()
