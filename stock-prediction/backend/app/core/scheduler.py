"""
Job Scheduler for Background Tasks

This module handles periodic tasks such as:
- Stock data updates
- Cache cleanup
- Database maintenance
"""

import threading
import time
import logging
import schedule
from datetime import datetime, timedelta
from typing import Callable, Dict, Any, List, Optional
import yfinance as yf
import pandas as pd
from sqlalchemy.orm import Session

from app.core.database import SessionLocal
from app.models.stocks import Stock, StockPrice
from app.utils.cache_manager import CacheManager
from app.utils.db_optimizer import analyze_db_tables
from app.core.ml_jobs import train_ml_models, monitor_model_performance

logger = logging.getLogger(__name__)

class JobScheduler:
    """Job scheduler for periodic background tasks"""
    
    _instance = None
    _running = False
    _thread = None
    _jobs = []
    
    @classmethod
    def get_instance(cls):
        """Get singleton instance"""
        if cls._instance is None:
            cls._instance = cls()
        return cls._instance
    
    def start(self):
        """Start the scheduler thread"""
        if self._running:
            logger.warning("Scheduler already running")
            return
            
        self._running = True
        self._thread = threading.Thread(target=self._run_scheduler, daemon=True)
        self._thread.start()
        logger.info("Job scheduler started")
    
    def stop(self):
        """Stop the scheduler thread"""
        self._running = False
        if self._thread:
            self._thread.join(timeout=1.0)
        logger.info("Job scheduler stopped")
    
    def _run_scheduler(self):
        """Run the scheduler loop"""
        while self._running:
            schedule.run_pending()
            time.sleep(1)
    
    @classmethod
    def schedule_job(cls, job_func: Callable, schedule_time: str, name: str = None) -> None:
        """
        Schedule a job to run at a specific time
        
        Args:
            job_func: Function to run
            schedule_time: When to run (format: 'HH:MM')
            name: Optional name for the job
        """
        job_name = name or job_func.__name__
        logger.info(f"Scheduling job '{job_name}' to run at {schedule_time}")
        job = schedule.every().day.at(schedule_time).do(job_func)
        job.tag(job_name)
        cls._jobs.append({"name": job_name, "time": schedule_time, "job": job})
    
    @classmethod
    def schedule_interval_job(cls, job_func: Callable, 
                             interval: int, interval_unit: str = 'minutes', 
                             name: str = None) -> None:
        """
        Schedule a job to run at fixed intervals
        
        Args:
            job_func: Function to run
            interval: Interval value
            interval_unit: Unit (seconds, minutes, hours, days)
            name: Optional name for the job
        """
        job_name = name or job_func.__name__
        logger.info(f"Scheduling job '{job_name}' to run every {interval} {interval_unit}")
        
        if interval_unit == 'seconds':
            job = schedule.every(interval).seconds.do(job_func)
        elif interval_unit == 'minutes':
            job = schedule.every(interval).minutes.do(job_func)
        elif interval_unit == 'hours':
            job = schedule.every(interval).hours.do(job_func)
        elif interval_unit == 'days':
            job = schedule.every(interval).days.do(job_func)
        else:
            raise ValueError(f"Invalid interval unit: {interval_unit}")
        
        job.tag(job_name)
        cls._jobs.append({"name": job_name, "interval": interval, 
                        "unit": interval_unit, "job": job})
    
    @classmethod
    def list_jobs(cls) -> List[Dict[str, Any]]:
        """List all scheduled jobs"""
        return [{"name": job["name"], 
                 "next_run": job["job"].next_run,
                 "last_run": job["job"].last_run} for job in cls._jobs]


# Common scheduled tasks

def update_active_stocks():
    """Update data for all active stocks"""
    logger.info("Running scheduled job: update_active_stocks")
    db = SessionLocal()
    try:
        # Get all active stocks
        stocks = db.query(Stock).filter(Stock.is_active == True).all()
        
        updated_count = 0
        for stock in stocks:
            try:
                # Get latest data from Yahoo Finance
                ticker = yf.Ticker(stock.ticker)
                hist = ticker.history(period="7d")
                
                if hist.empty:
                    logger.warning(f"No data returned for {stock.ticker}")
                    continue
                    
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
                
                # Update last_updated timestamp
                stock.last_updated = datetime.now()
                db.commit()
                
                # Clear cache for this stock
                CacheManager.delete(f"get_stock_data_{stock.ticker}")
                
            except Exception as e:
                logger.error(f"Error updating {stock.ticker}: {str(e)}")
                db.rollback()
                
        logger.info(f"Updated {updated_count} price records for {len(stocks)} active stocks")
        return updated_count
    
    except Exception as e:
        logger.error(f"Error in update_active_stocks job: {str(e)}")
        return 0
    finally:
        db.close()


def clear_expired_cache():
    """Clear expired cache entries"""
    logger.info("Running scheduled job: clear_expired_cache")
    count = CacheManager.clear_expired()
    logger.info(f"Cleared {count} expired cache entries")
    return count


def optimize_database():
    """Run database optimization tasks"""
    logger.info("Running scheduled job: optimize_database")
    try:
        result = analyze_db_tables()
        logger.info("Database tables analyzed" if result else "Failed to analyze database tables")
        return result
    except Exception as e:
        logger.error(f"Error in optimize_database job: {str(e)}")
        return False


# Setup scheduler on import
def setup_default_jobs():
    """Setup default scheduler jobs"""
    try:
        scheduler = JobScheduler.get_instance()
        
        # Schedule jobs
        # Update stocks at 16:30 (after market close)
        JobScheduler.schedule_job(update_active_stocks, "16:30", "update_stocks")
        
        # Clear expired cache every 30 minutes
        JobScheduler.schedule_interval_job(clear_expired_cache, 30, "minutes", "clear_cache")
        
        # Optimize database at 01:00 AM (low traffic)
        JobScheduler.schedule_job(optimize_database, "01:00", "optimize_db")
        
        # Train ML models weekly on Sunday at 02:00 AM
        JobScheduler.schedule_job(train_ml_models, "02:00", "train_ml_models")
        
        # Monitor model performance daily at 03:00 AM
        JobScheduler.schedule_job(monitor_model_performance, "03:00", "monitor_models")
        
        return True
    except Exception as e:
        logger.error(f"Error setting up default jobs: {str(e)}")
        return False
