"""
Maintenance tasks for Celery
"""
import logging
from datetime import datetime, timedelta

from app.core.celery_app import celery_app
from app.core.database import SessionLocal
from app.utils.cache_manager import CacheManager
from app.utils.db_optimizer import analyze_db_tables, create_indices, vacuum_db_tables

logger = logging.getLogger(__name__)

@celery_app.task(name="app.tasks.maintenance_tasks.clear_expired_cache")
def clear_expired_cache():
    """Clear expired cache entries"""
    logger.info("Running task: clear_expired_cache")
    try:
        count = CacheManager.clear_expired()
        logger.info(f"Cleared {count} expired cache entries")
        return {
            "status": "success", 
            "cleared_entries": count
        }
    except Exception as e:
        logger.error(f"Error clearing expired cache: {str(e)}")
        return {"status": "error", "message": str(e)}


@celery_app.task(name="app.tasks.maintenance_tasks.optimize_database")
def optimize_database(perform_vacuum: bool = False):
    """
    Run database optimization tasks
    
    Args:
        perform_vacuum: Whether to perform vacuum operation (resource intensive)
    """
    logger.info("Running task: optimize_database")
    
    results = {
        "analyze": False,
        "indices": False,
        "vacuum": False
    }
    
    try:
        # Run database table analysis
        results["analyze"] = analyze_db_tables()
        logger.info("Database tables analyzed")
        
        # Create or update indices
        results["indices"] = create_indices()
        logger.info("Database indices created/updated")
        
        # Vacuum database if requested
        if perform_vacuum:
            results["vacuum"] = vacuum_db_tables(full=False)
            logger.info("Database vacuum completed")
        
        return {
            "status": "success",
            "results": results
        }
        
    except Exception as e:
        logger.error(f"Error optimizing database: {str(e)}")
        return {"status": "error", "message": str(e)}


@celery_app.task(name="app.tasks.maintenance_tasks.cleanup_old_data")
def cleanup_old_data(days_to_keep: int = 90):
    """
    Clean up old temporary data
    
    Args:
        days_to_keep: Number of days worth of temporary data to keep
    """
    logger.info(f"Running task: cleanup_old_data ({days_to_keep} days)")
    db = SessionLocal()
    
    try:
        # Calculate cutoff date
        cutoff_date = datetime.now() - timedelta(days=days_to_keep)
        
        # Clean up task records
        # Note: This assumes you have a TaskRecord model
        # If not, you'll need to implement based on your data model
        try:
            deleted_count = db.execute(
                "DELETE FROM task_records WHERE created_at < :cutoff", 
                {"cutoff": cutoff_date}
            ).rowcount
            logger.info(f"Deleted {deleted_count} old task records")
        except Exception as e:
            logger.warning(f"Error cleaning task records: {str(e)}")
        
        # Clean up log entries (if applicable)
        try:
            deleted_count = db.execute(
                "DELETE FROM log_entries WHERE timestamp < :cutoff",
                {"cutoff": cutoff_date}
            ).rowcount
            logger.info(f"Deleted {deleted_count} old log entries")
        except Exception as e:
            logger.warning(f"Error cleaning log entries: {str(e)}")
        
        # Commit changes
        db.commit()
        db.close()
        
        return {
            "status": "success",
            "message": f"Cleaned up data older than {days_to_keep} days"
        }
        
    except Exception as e:
        logger.error(f"Error cleaning up old data: {str(e)}")
        if 'db' in locals():
            db.rollback()
            db.close()
        return {"status": "error", "message": str(e)}


@celery_app.task(name="app.tasks.maintenance_tasks.send_system_stats")
def send_system_stats():
    """Send system statistics report"""
    logger.info("Running task: send_system_stats")
    
    try:
        db = SessionLocal()
        
        # Collect database statistics
        try:
            # Count records in major tables
            stock_count = db.execute("SELECT COUNT(*) FROM stocks").scalar()
            price_count = db.execute("SELECT COUNT(*) FROM stock_prices").scalar()
            user_count = db.execute("SELECT COUNT(*) FROM users").scalar()
            portfolio_count = db.execute("SELECT COUNT(*) FROM portfolios").scalar()
            
            # Get DB size
            db_size = db.execute(
                "SELECT pg_size_pretty(pg_database_size(current_database()))"
            ).scalar()
            
            stats = {
                "database": {
                    "stock_count": stock_count,
                    "price_count": price_count,
                    "user_count": user_count,
                    "portfolio_count": portfolio_count,
                    "size": db_size
                }
            }
        except Exception as e:
            logger.warning(f"Error collecting DB stats: {str(e)}")
            stats = {"database": "Error collecting stats"}
        
        # Collect cache statistics
        try:
            cache_stats = {
                "size": CacheManager.get_cache_size(),
                "item_count": CacheManager.get_item_count(),
                "hit_rate": CacheManager.get_hit_rate()
            }
            stats["cache"] = cache_stats
        except Exception as e:
            logger.warning(f"Error collecting cache stats: {str(e)}")
            stats["cache"] = "Error collecting stats"
        
        # Close DB connection
        db.close()
        
        # Here you would normally send this data by email or to a monitoring service
        # For now we'll just log it
        logger.info(f"System stats: {stats}")
        
        return {
            "status": "success",
            "message": "System stats collected",
            "stats": stats
        }
        
    except Exception as e:
        logger.error(f"Error sending system stats: {str(e)}")
        if 'db' in locals():
            db.close()
        return {"status": "error", "message": str(e)}
