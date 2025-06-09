"""
Database configuration and session management for PostgreSQL
"""
from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
import os
from dotenv import load_dotenv
import logging
import time

# Configure logging
logger = logging.getLogger(__name__)

# Load environment variables
load_dotenv()

# PostgreSQL connection parameters with defaults
PG_HOST = os.getenv("POSTGRES_HOST", "localhost")
PG_PORT = os.getenv("POSTGRES_PORT", "5432")
PG_DB = os.getenv("POSTGRES_DB", "stock_prediction")
PG_USER = os.getenv("POSTGRES_USER", "zahaam") 
PG_PASSWORD = os.getenv("POSTGRES_PASSWORD", "zahaampassword")

# Build PostgreSQL connection string
DATABASE_URL = os.getenv("DATABASE_URL")
if not DATABASE_URL:
    DATABASE_URL = f"postgresql://{PG_USER}:{PG_PASSWORD}@{PG_HOST}:{PG_PORT}/{PG_DB}"

logger.info(f"Connecting to PostgreSQL database at {PG_HOST}:{PG_PORT}/{PG_DB}")

# Load connection pool settings from environment variables
PG_POOL_SIZE = int(os.getenv("PG_POOL_SIZE", 10))
PG_MAX_OVERFLOW = int(os.getenv("PG_MAX_OVERFLOW", 20))
PG_POOL_TIMEOUT = int(os.getenv("PG_POOL_TIMEOUT", 30))
PG_POOL_RECYCLE = int(os.getenv("PG_POOL_RECYCLE", 1800))
PG_ECHO = os.getenv("DEBUG", "False").lower() == "true"

# Create engine with connection pooling optimized for production
engine = create_engine(
    DATABASE_URL,
    pool_size=PG_POOL_SIZE,  # Maximum number of connections to keep open
    max_overflow=PG_MAX_OVERFLOW,  # Maximum number of connections that can be created beyond pool_size
    pool_timeout=PG_POOL_TIMEOUT,  # Seconds to wait before timing out on getting a connection from the pool
    pool_recycle=PG_POOL_RECYCLE,  # Recycle connections after 30 minutes
    echo=PG_ECHO,  # Set to True for query debugging based on DEBUG env var
    connect_args={
        "connect_timeout": 10,  # Seconds to wait for establishing connection
    }
)

# Connection retry mechanism
def get_connection_with_retry(max_retries=5, retry_delay=2):
    """Attempt to connect to database with retries"""
    retries = 0
    last_exception = None
    
    while retries < max_retries:
        try:
            connection = engine.connect()
            connection.close()
            logger.info("Database connection established successfully")
            return True
        except Exception as e:
            last_exception = e
            retries += 1
            logger.warning(f"Database connection attempt {retries} failed: {e}")
            time.sleep(retry_delay)
    
    logger.error(f"Failed to connect to database after {max_retries} attempts: {last_exception}")
    return False

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
