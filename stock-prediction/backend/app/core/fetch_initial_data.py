"""
Script to fetch initial stock data for all Indonesian stocks
"""
import asyncio
import logging
from sqlalchemy.orm import Session
from app.core.database import SessionLocal
from app.models.stocks import Stock
from app.core.stock_fetcher import fetch_and_save_all_stocks

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

async def main():
    """Main entry point for fetching initial stock data"""
    db = SessionLocal()
    try:
        # Check if we have any price data
        count = db.query(Stock).count()
        logger.info(f"Found {count} stocks in database")
        
        # Fetch data for all stocks
        logger.info("Starting to fetch data for all stocks...")
        results = await fetch_and_save_all_stocks(db)
        
        logger.info(f"Stock data fetching completed. Results: {results}")
    finally:
        db.close()

if __name__ == "__main__":
    # Run the main function
    asyncio.run(main())
