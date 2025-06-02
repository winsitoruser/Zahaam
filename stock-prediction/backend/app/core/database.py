"""
Database configuration and session management
"""
from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
import os
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Get Database URL from environment or use default
# Jika PostgreSQL tidak tersedia, gunakan SQLite sebagai fallback
DATABASE_URL = os.getenv("DATABASE_URL")

# Jika DATABASE_URL tidak di-set, coba PostgreSQL dulu
if not DATABASE_URL:
    try:
        import psycopg2
        # Test koneksi ke PostgreSQL
        conn = psycopg2.connect(
            host=os.getenv("POSTGRES_HOST", "localhost"),
            database=os.getenv("POSTGRES_DB", "stock_prediction"),
            user=os.getenv("POSTGRES_USER", "zahaam"),
            password=os.getenv("POSTGRES_PASSWORD", "zahaampassword"),
            port=os.getenv("POSTGRES_PORT", "5432")
        )
        conn.close()
        # Jika berhasil, gunakan PostgreSQL
        DATABASE_URL = f"postgresql://{os.getenv('POSTGRES_USER', 'zahaam')}:{os.getenv('POSTGRES_PASSWORD', 'zahaampassword')}@{os.getenv('POSTGRES_HOST', 'localhost')}:{os.getenv('POSTGRES_PORT', '5432')}/{os.getenv('POSTGRES_DB', 'stock_prediction')}"
    except (ImportError, Exception) as e:
        print(f"PostgreSQL tidak tersedia: {e}")
        # Fallback ke SQLite
        DATABASE_URL = "sqlite:///./stock_prediction.db"
        print(f"Menggunakan SQLite sebagai fallback: {DATABASE_URL}")
else:
    print(f"Menggunakan database dari environment: {DATABASE_URL}")


# Check if this is SQLite (for backwards compatibility)
if DATABASE_URL.startswith('sqlite'):
    engine = create_engine(
        DATABASE_URL, connect_args={"check_same_thread": False}
    )
else:
    # PostgreSQL engine
    engine = create_engine(DATABASE_URL)

# Create SessionLocal class
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

# Create Base class
Base = declarative_base()

# Database dependency
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
