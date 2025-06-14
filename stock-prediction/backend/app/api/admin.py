"""
Admin-related API endpoints for system monitoring and maintenance
"""

from fastapi import APIRouter, Depends, BackgroundTasks, HTTPException, status
from typing import Dict, Any, List
from sqlalchemy.orm import Session
from app.core.database import get_db
from app.utils.db_optimizer import (
    check_db_health, 
    analyze_db_tables, 
    vacuum_db_tables,
    create_indices
)
from app.core.security import get_current_active_superuser_or_open_access as get_current_active_superuser
from app.core.scheduler import JobScheduler, update_active_stocks
from app.utils.cache_manager import CacheManager
import psutil
import os
import time
from datetime import datetime

router = APIRouter()

@router.get("/health", response_model=Dict[str, Any])
async def health_check(db: Session = Depends(get_db)):
    """
    Check system health including database and server status
    """
    start_time = time.time()
    
    # Get DB health
    db_health = check_db_health()
    
    # Get system metrics
    system_health = {
        "cpu_percent": psutil.cpu_percent(),
        "memory_percent": psutil.virtual_memory().percent,
        "disk_percent": psutil.disk_usage('/').percent,
    }
    
    response_time = time.time() - start_time
    
    return {
        "status": "ok" if db_health["status"] == "healthy" else "warning",
        "api_response_time_ms": round(response_time * 1000, 2),
        "database": db_health,
        "system": system_health,
        "timestamp": time.time()
    }

@router.post("/db/optimize", response_model=Dict[str, Any])
async def optimize_database(
    background_tasks: BackgroundTasks,
    perform_vacuum: bool = False,
    current_user = Depends(get_current_active_superuser)
):
    """
    Trigger database optimization tasks (admin only)
    """
    # Schedule optimization tasks in background
    background_tasks.add_task(analyze_db_tables)
    background_tasks.add_task(create_indices)
    
    if perform_vacuum:
        # Vacuum is a heavy operation, run in background
        background_tasks.add_task(vacuum_db_tables, full=False)
    
    return {
        "status": "scheduled",
        "message": "Database optimization tasks have been scheduled",
        "tasks": ["analyze_tables", "create_indices"] + (["vacuum_tables"] if perform_vacuum else [])
    }

@router.get("/db/stats", response_model=Dict[str, Any])
async def database_statistics(
    db: Session = Depends(get_db),
    current_user = Depends(get_current_active_superuser)
):
    """
    Get detailed database statistics (admin only)
    """
    try:
        # Table statistics
        result = db.execute("""
            SELECT 
                relname AS table_name,
                n_live_tup AS row_count,
                pg_size_pretty(pg_total_relation_size(quote_ident(relname))) AS total_size,
                pg_size_pretty(pg_total_relation_size(quote_ident(relname)) - pg_relation_size(quote_ident(relname))) AS index_size
            FROM pg_stat_user_tables
            ORDER BY n_live_tup DESC;
        """)
        
        tables = []
        for row in result:
            tables.append({
                "name": row[0],
                "rows": row[1],
                "total_size": row[2],
                "index_size": row[3]
            })
            
        # Index statistics
        result = db.execute("""
            SELECT
                indexrelname AS index_name,
                relname AS table_name,
                pg_size_pretty(pg_relation_size(quote_ident(indexrelname::text))) AS index_size,
                idx_scan AS index_scans
            FROM pg_stat_user_indexes
            ORDER BY idx_scan DESC, pg_relation_size(quote_ident(indexrelname::text)) DESC;
        """)
        
        indices = []
        for row in result:
            indices.append({
                "name": row[0],
                "table": row[1],
                "size": row[2],
                "scans": row[3]
            })
            
        return {
            "tables": tables,
            "indices": indices,
            "total_tables": len(tables),
            "timestamp": time.time()
        }
    except Exception as e:
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail=f"Database query error: {str(e)}"
        )


@router.get("/scheduler/status", response_model=Dict[str, Any])
async def scheduler_status(current_user = Depends(get_current_active_superuser)):
    """
    Get status of background job scheduler (admin only)
    """
    scheduler = JobScheduler.get_instance()
    jobs = JobScheduler.list_jobs()
    
    # Format job times for response
    formatted_jobs = []
    for job in jobs:
        next_run = job["next_run"].strftime("%Y-%m-%d %H:%M:%S") if job["next_run"] else None
        last_run = job["last_run"].strftime("%Y-%m-%d %H:%M:%S") if job["last_run"] else None
        
        formatted_jobs.append({
            "name": job["name"],
            "next_run": next_run,
            "last_run": last_run
        })
    
    return {
        "status": "running",
        "jobs": formatted_jobs,
        "total_jobs": len(jobs),
        "cache_stats": CacheManager.get_stats(),
        "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    }


@router.post("/scheduler/run-job/{job_name}", response_model=Dict[str, Any])
async def run_scheduler_job(
    job_name: str,
    background_tasks: BackgroundTasks,
    current_user = Depends(get_current_active_superuser)
):
    """
    Manually trigger a scheduled job (admin only)
    """
    if job_name == "update_stocks":
        # Run stock update in background
        background_tasks.add_task(update_active_stocks)
        return {"status": "scheduled", "message": f"Job '{job_name}' has been scheduled to run", "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
    elif job_name == "clear_cache":
        count = CacheManager.clear_expired()
        return {"status": "completed", "message": f"Cleared {count} expired cache entries", "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
    elif job_name == "optimize_db":
        # Run database optimization in background
        background_tasks.add_task(analyze_db_tables)
        background_tasks.add_task(create_indices)
        return {"status": "scheduled", "message": f"Job '{job_name}' has been scheduled to run", "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
    else:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Job '{job_name}' not found"
        )


@router.get("/cache/stats", response_model=Dict[str, Any])
async def cache_stats(current_user = Depends(get_current_active_superuser)):
    """
    Get cache statistics (admin only)
    """
    stats = CacheManager.get_stats()
    return {
        **stats,
        "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    }


@router.post("/cache/clear", response_model=Dict[str, Any])
async def clear_cache(
    clear_all: bool = False,
    current_user = Depends(get_current_active_superuser)
):
    """
    Clear cache entries (admin only)
    """
    if clear_all:
        # Clear all cache
        CacheManager.clear()
        return {"status": "success", "message": "All cache entries cleared", "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
    else:
        # Clear only expired cache
        count = CacheManager.clear_expired()
        return {"status": "success", "message": f"Cleared {count} expired cache entries", "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
