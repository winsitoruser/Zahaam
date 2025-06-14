"""
Database initialization script optimized for PostgreSQL
"""
from app.core.database import engine, Base, SessionLocal, get_connection_with_retry
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.data.indonesian_stocks import INDONESIAN_STOCKS
import logging
import time
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

logger = logging.getLogger(__name__)

def reset_sequences(db_session):
    """Reset PostgreSQL sequences based on max IDs in tables"""
    try:
        # Get a list of all tables with id columns and their data types
        result = db_session.execute(text("""
            SELECT table_name, data_type FROM information_schema.columns 
            WHERE table_schema = 'public' 
            AND column_name = 'id'
        """))
        tables_info = [(row[0], row[1]) for row in result]
        
        for table, data_type in tables_info:
            try:
                # Skip tables where id is not an integer or numeric type
                if 'int' not in data_type.lower() and 'serial' not in data_type.lower() and 'numeric' not in data_type.lower():
                    logger.warning(f"Skipping sequence reset for table {table} - ID column is {data_type} (not integer)")
                    continue
                    
                # Check if table has a sequence
                seq_result = db_session.execute(text(f"""
                    SELECT pg_get_serial_sequence('public.{table}', 'id')
                """))
                sequence_name = seq_result.scalar()
                
                if not sequence_name:
                    logger.warning(f"Table {table} does not have a sequence for id column")
                    continue
                
                # Reset sequence for this table
                db_session.execute(text(f"""
                    SELECT setval('{sequence_name}', 
                    COALESCE((SELECT MAX(id) FROM public.{table}), 0) + 1, false)
                """))
                logger.info(f"Reset sequence for table {table}")
            except Exception as e:
                logger.warning(f"Could not reset sequence for table {table}: {e}")
                # Continue with other tables instead of failing entire process
        
        db_session.commit()
        return True
    except Exception as e:
        logger.error(f"Failed to reset sequences: {e}")
        return False

def init_db(max_retries=5):
    """Initialize the database and create all tables with retry logic"""
    retry_count = 0
    
    while retry_count < max_retries:
        try:
            # Test database connection first
            if not get_connection_with_retry():
                logger.warning(f"Database connection attempt {retry_count + 1} failed, retrying...")
                retry_count += 1
                time.sleep(2)
                continue
            
            logger.info("Creating database tables...")
            Base.metadata.create_all(bind=engine)
            logger.info("Database tables created successfully")
            
            # Create a session
            db = SessionLocal()
            
            try:
                # Reset sequences to ensure IDs are properly assigned
                reset_sequences(db)
                
                # Check if we need to populate the stocks table
                stocks_count = db.query(Stock).count()
                
                if stocks_count == 0:
                    logger.info("Populating stocks table with initial data...")
                    
                    # INDONESIAN_STOCKS is a dictionary in format {ticker: {name, sector}}
                    from datetime import datetime
                    now = datetime.now()
                    
                    # Batch insert for better performance
                    stocks_to_add = []
                    for ticker, info in INDONESIAN_STOCKS.items():
                        stock = Stock(
                            ticker=ticker,
                            name=info.get("name", ticker),
                            sector=info.get("sector", "Unknown"),
                            is_active=True,
                            last_updated=now
                        )
                        stocks_to_add.append(stock)
                    
                    # Add in batches of 100
                    batch_size = 100
                    for i in range(0, len(stocks_to_add), batch_size):
                        batch = stocks_to_add[i:i+batch_size]
                        db.add_all(batch)
                        db.commit()
                    
                    logger.info(f"Added {len(INDONESIAN_STOCKS)} stocks to the database")
                else:
                    logger.info(f"Database already contains {stocks_count} stocks, skipping initialization")
                
                return True
            except SQLAlchemyError as e:
                logger.error(f"Database initialization error: {e}")
                db.rollback()
                retry_count += 1
            finally:
                db.close()
        
        except Exception as e:
            logger.error(f"Failed to initialize database: {e}")
            retry_count += 1
            time.sleep(2)
    
    logger.critical(f"Failed to initialize database after {max_retries} attempts")
    return False

if __name__ == "__main__":
    init_db()
