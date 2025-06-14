"""
Stock data tasks for Celery
"""
import logging
from datetime import datetime, timedelta
import yfinance as yf
import pandas as pd
from typing import List, Dict, Any, Optional

from app.core.celery_app import celery_app
from app.core.database import SessionLocal
from app.models.stocks import Stock, StockPrice
from app.utils.cache_manager import CacheManager

logger = logging.getLogger(__name__)

@celery_app.task(name="app.tasks.stock_tasks.update_stock")
def update_stock(ticker: str, period: str = "7d") -> Dict[str, Any]:
    """
    Update price data for a specific stock
    
    Args:
        ticker: Stock ticker symbol
        period: Data period to fetch (default: 7d)
    """
    logger.info(f"Updating stock data for {ticker}")
    db = SessionLocal()
    updated_count = 0
    
    try:
        # Check if stock exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            logger.error(f"Stock {ticker} not found in database")
            db.close()
            return {"status": "error", "message": f"Stock {ticker} not found"}
        
        # Get latest data from Yahoo Finance
        ticker_obj = yf.Ticker(ticker)
        hist = ticker_obj.history(period=period)
        
        if hist.empty:
            logger.warning(f"No data returned for {ticker}")
            db.close()
            return {"status": "warning", "message": f"No data returned for {ticker}"}
        
        # Convert index to datetime
        hist.index = pd.to_datetime(hist.index)
        
        # Get latest date in DB
        latest_price = db.query(StockPrice).filter(
            StockPrice.stock_id == stock.id
        ).order_by(StockPrice.date.desc()).first()
        
        latest_date = latest_price.date if latest_price else None
        
        # Only add new data
        for date, row in hist.iterrows():
            # Convert to datetime date
            date = date.to_pydatetime().date()
            
            # Skip if we already have this date
            if latest_date and date <= latest_date:
                continue
            
            # Add new price data
            price = StockPrice(
                stock_id=stock.id,
                date=date,
                open=row['Open'],
                high=row['High'],
                low=row['Low'],
                close=row['Close'],
                volume=row['Volume']
            )
            db.add(price)
            updated_count += 1
        
        # Only commit if we have updates
        if updated_count > 0:
            # Update last_updated timestamp
            stock.last_updated = datetime.now()
            db.commit()
            
            # Clear cache for this stock
            CacheManager.delete(f"get_stock_data_{ticker}")
            CacheManager.delete(f"technical_signals_{ticker}")
            
            logger.info(f"Added {updated_count} new price records for {ticker}")
        else:
            logger.info(f"No new data for {ticker}")
        
        db.close()
        return {
            "status": "success", 
            "ticker": ticker,
            "updated_count": updated_count
        }
        
    except Exception as e:
        logger.error(f"Error updating {ticker}: {str(e)}")
        if 'db' in locals():
            db.rollback()
            db.close()
        return {"status": "error", "message": str(e)}


@celery_app.task(name="app.tasks.stock_tasks.update_active_stocks")
def update_active_stocks() -> Dict[str, Any]:
    """Update price data for all active stocks"""
    logger.info("Running scheduled job: update_active_stocks")
    db = SessionLocal()
    
    try:
        # Get all active stocks
        stocks = db.query(Stock).filter(Stock.is_active == True).all()
        logger.info(f"Found {len(stocks)} active stocks to update")
        
        # Close DB connection before starting individual tasks
        db.close()
        
        # Queue update tasks for each stock
        results = []
        for stock in stocks:
            # Add to Celery queue
            task = update_stock.delay(stock.ticker)
            results.append({
                "ticker": stock.ticker,
                "task_id": task.id
            })
        
        return {
            "status": "success",
            "message": f"Queued updates for {len(stocks)} stocks",
            "tasks": results
        }
        
    except Exception as e:
        logger.error(f"Error in update_active_stocks job: {str(e)}")
        if 'db' in locals():
            db.close()
        return {"status": "error", "message": str(e)}


@celery_app.task(name="app.tasks.stock_tasks.bulk_stock_update")
def bulk_stock_update(tickers: List[str], period: str = "7d") -> Dict[str, Any]:
    """
    Update price data for multiple stocks
    
    Args:
        tickers: List of stock ticker symbols
        period: Data period to fetch (default: 7d)
    """
    logger.info(f"Bulk updating {len(tickers)} stocks")
    
    results = {}
    for ticker in tickers:
        # Queue individual update tasks
        task = update_stock.delay(ticker, period)
        results[ticker] = {"task_id": task.id}
    
    return {
        "status": "success",
        "message": f"Queued updates for {len(tickers)} stocks",
        "results": results
    }


@celery_app.task(name="app.tasks.stock_tasks.add_new_stock")
def add_new_stock(
    ticker: str, 
    name: Optional[str] = None, 
    sector: Optional[str] = None
) -> Dict[str, Any]:
    """
    Add a new stock to the database and fetch its historical data
    
    Args:
        ticker: Stock ticker symbol
        name: Stock company name (optional)
        sector: Stock sector (optional)
    """
    logger.info(f"Adding new stock: {ticker}")
    db = SessionLocal()
    
    try:
        # Check if stock already exists
        existing = db.query(Stock).filter(Stock.ticker == ticker).first()
        if existing:
            logger.info(f"Stock {ticker} already exists in database")
            db.close()
            
            # Still update its data
            update_stock.delay(ticker, period="max")
            
            return {
                "status": "exists", 
                "message": f"Stock {ticker} already exists",
                "stock_id": existing.id
            }
        
        # Fetch stock info from Yahoo Finance
        ticker_obj = yf.Ticker(ticker)
        info = ticker_obj.info
        
        if not info or 'regularMarketPrice' not in info:
            logger.error(f"Could not fetch info for {ticker}")
            db.close()
            return {"status": "error", "message": f"Invalid ticker: {ticker}"}
        
        # Create new stock
        new_stock = Stock(
            ticker=ticker,
            name=name or info.get('shortName', ticker),
            sector=sector or info.get('sector', 'Unknown'),
            is_active=True,
            last_updated=datetime.now()
        )
        
        db.add(new_stock)
        db.commit()
        db.refresh(new_stock)
        
        # Close DB connection before starting fetch task
        stock_id = new_stock.id
        db.close()
        
        # Queue task to fetch historical data
        update_stock.delay(ticker, period="max")
        
        return {
            "status": "success",
            "message": f"Added stock {ticker}",
            "stock_id": stock_id
        }
        
    except Exception as e:
        logger.error(f"Error adding stock {ticker}: {str(e)}")
        if 'db' in locals():
            db.rollback()
            db.close()
        return {"status": "error", "message": str(e)}
