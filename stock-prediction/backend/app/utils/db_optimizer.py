"""
PostgreSQL Database Optimizer

This module provides functions to analyze and optimize the PostgreSQL database performance.
"""
import logging
from sqlalchemy import text, create_engine
from app.core.database import SessionLocal, engine, DATABASE_URL
import psycopg2
import time

logger = logging.getLogger(__name__)

def analyze_db_tables():
    """Analyze all tables in the database for better query planning"""
    try:
        db = SessionLocal()
        # Get list of all tables
        result = db.execute(text("SELECT tablename FROM pg_tables WHERE schemaname = 'public'"))
        tables = [row[0] for row in result]
        
        for table in tables:
            logger.info(f"Analyzing table: {table}")
            db.execute(text(f"ANALYZE {table}"))
            
        db.commit()
        logger.info(f"Successfully analyzed {len(tables)} tables")
        return True
    except Exception as e:
        logger.error(f"Error analyzing database tables: {e}")
        return False
    finally:
        db.close()

def vacuum_db_tables(full=False):
    """
    Vacuum database tables to reclaim storage and update statistics
    Note: Can't use SQLAlchemy for vacuum operations as they require special handling
    """
    try:
        # Need to connect directly with psycopg2 for VACUUM operations
        conn_parts = DATABASE_URL.replace('postgresql://', '').split('@')
        user_pass = conn_parts[0].split(':')
        host_db = conn_parts[1].split('/')
        
        conn = psycopg2.connect(
            user=user_pass[0],
            password=user_pass[1],
            host=host_db[0].split(':')[0],
            port=host_db[0].split(':')[1] if ':' in host_db[0] else 5432,
            database=host_db[1]
        )
        
        # Must set autocommit for vacuum operations
        conn.set_session(autocommit=True)
        cursor = conn.cursor()
        
        # Get list of all tables
        cursor.execute("SELECT tablename FROM pg_tables WHERE schemaname = 'public'")
        tables = [row[0] for row in cursor.fetchall()]
        
        for table in tables:
            logger.info(f"Vacuuming table: {table}")
            if full:
                cursor.execute(f"VACUUM FULL ANALYZE {table}")
            else:
                cursor.execute(f"VACUUM ANALYZE {table}")
            
        logger.info(f"Successfully vacuumed {len(tables)} tables")
        cursor.close()
        conn.close()
        return True
    except Exception as e:
        logger.error(f"Error vacuuming database tables: {e}")
        return False

def create_indices():
    """Create optimized indices for frequently accessed tables"""
    try:
        db = SessionLocal()
        
        # Stock price indices for faster time series queries
        db.execute(text("""
            CREATE INDEX IF NOT EXISTS idx_stock_prices_stock_id_date 
            ON stock_prices (stock_id, date);
        """))
        
        # Stock indicator indices
        db.execute(text("""
            CREATE INDEX IF NOT EXISTS idx_stock_indicators_stock_id_date 
            ON stock_indicators (stock_id, date);
        """))
        
        # Optimize common filters on stocks
        db.execute(text("""
            CREATE INDEX IF NOT EXISTS idx_stocks_ticker 
            ON stocks (ticker);
        """))
        
        db.execute(text("""
            CREATE INDEX IF NOT EXISTS idx_stocks_is_active 
            ON stocks (is_active);
        """))
        
        # Add indices for watchlist performance
        db.execute(text("""
            CREATE INDEX IF NOT EXISTS idx_watchlist_items_user_id 
            ON watchlist_items (user_id);
        """))
        
        db.commit()
        logger.info("Successfully created optimization indices")
        return True
    except Exception as e:
        logger.error(f"Error creating indices: {e}")
        db.rollback()
        return False
    finally:
        db.close()

def check_db_health():
    """Check database connection health and performance metrics"""
    try:
        start_time = time.time()
        
        # Test connection pool
        db = SessionLocal()
        result = db.execute(text("SELECT 1"))
        result.fetchone()
        
        # Get basic statistics
        result = db.execute(text("""
            SELECT relname as table_name,
                   n_live_tup as row_count
            FROM pg_stat_user_tables
            ORDER BY n_live_tup DESC;
        """))
        table_stats = [(row[0], row[1]) for row in result]
        
        # Get database size
        result = db.execute(text("""
            SELECT pg_size_pretty(pg_database_size(current_database())) as db_size;
        """))
        db_size = result.fetchone()[0]
        
        # Get connection count
        result = db.execute(text("""
            SELECT count(*) FROM pg_stat_activity 
            WHERE datname = current_database();
        """))
        connections = result.fetchone()[0]
        
        elapsed = time.time() - start_time
        
        db_health = {
            "connection_time_ms": round(elapsed * 1000, 2),
            "table_counts": {t[0]: t[1] for t in table_stats},
            "database_size": db_size,
            "active_connections": connections,
            "status": "healthy" if elapsed < 1.0 else "slow"
        }
        
        logger.info(f"Database health check: {db_health}")
        return db_health
    except Exception as e:
        logger.error(f"Database health check failed: {e}")
        return {"status": "failed", "error": str(e)}
    finally:
        db.close()

if __name__ == "__main__":
    logging.basicConfig(level=logging.INFO)
    check_db_health()
    analyze_db_tables()
    create_indices()
    # Only run vacuum during maintenance windows
    # vacuum_db_tables()
