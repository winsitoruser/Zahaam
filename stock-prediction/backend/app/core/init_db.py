"""
Database initialization script
"""
from app.core.database import engine, Base, SessionLocal
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.data.indonesian_stocks import INDONESIAN_STOCKS
import logging

logger = logging.getLogger(__name__)

def init_db():
    """Initialize the database and create all tables"""
    logger.info("Creating database tables...")
    Base.metadata.create_all(bind=engine)
    logger.info("Database tables created successfully")
    
    # Check if we need to populate the stocks table
    db = SessionLocal()
    stocks_count = db.query(Stock).count()
    
    if stocks_count == 0:
        logger.info("Populating stocks table with initial data...")
        
        # INDONESIAN_STOCKS is now a dictionary in format {ticker: {name, sector}}
        from datetime import datetime
        now = datetime.now()
        
        for ticker, info in INDONESIAN_STOCKS.items():
            stock = Stock(
                ticker=ticker,
                name=info.get("name", ticker),
                sector=info.get("sector", "Unknown"),
                is_active=True,
                last_updated=now
            )
            db.add(stock)
        
        db.commit()
        logger.info(f"Added {len(INDONESIAN_STOCKS)} stocks to the database")
    else:
        logger.info(f"Database already contains {stocks_count} stocks, skipping initialization")
    
    db.close()

if __name__ == "__main__":
    init_db()
