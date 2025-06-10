"""
API endpoints for managing asynchronous tasks with Celery
"""
from fastapi import APIRouter, Depends, HTTPException, BackgroundTasks, Query
from typing import Dict, List, Any, Optional
import logging

from app.core.celery_app import celery_app, get_task_info
from app.core.database import get_db
from app.core.security import get_current_active_user, get_current_active_superuser

from app.tasks.ml_tasks import train_model, predict, batch_predict
from app.tasks.stock_tasks import update_stock, bulk_stock_update, add_new_stock

logger = logging.getLogger(__name__)
router = APIRouter(tags=["tasks"])

@router.post("/tasks/stock-update/{ticker}")
async def trigger_stock_update(
    ticker: str,
    period: str = "7d",
    user = Depends(get_current_active_user)
):
    """
    Trigger an asynchronous stock data update task
    
    Parameters:
    - ticker: Stock ticker symbol
    - period: Data period to fetch (default: 7d)
    """
    try:
        # Queue the Celery task
        task = update_stock.delay(ticker, period)
        
        return {
            "status": "success",
            "message": f"Stock update task for {ticker} has been queued",
            "task_id": task.id
        }
    except Exception as e:
        logger.error(f"Error triggering stock update for {ticker}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error triggering task: {str(e)}")


@router.post("/tasks/bulk-stock-update")
async def trigger_bulk_update(
    tickers: List[str],
    period: str = "7d",
    user = Depends(get_current_active_user)
):
    """
    Trigger an asynchronous bulk stock data update
    
    Parameters:
    - tickers: List of stock ticker symbols
    - period: Data period to fetch (default: 7d)
    """
    try:
        if not tickers:
            raise HTTPException(status_code=400, detail="No tickers provided")
        
        # Queue the Celery task
        task = bulk_stock_update.delay(tickers, period)
        
        return {
            "status": "success",
            "message": f"Bulk update task for {len(tickers)} stocks has been queued",
            "task_id": task.id
        }
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error triggering bulk stock update: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error triggering task: {str(e)}")


@router.post("/tasks/train-model/{ticker}")
async def trigger_model_training(
    ticker: str,
    model_type: str = "random_forest",
    force: bool = False,
    user = Depends(get_current_active_user)
):
    """
    Trigger an asynchronous ML model training task
    
    Parameters:
    - ticker: Stock ticker symbol
    - model_type: Type of ML model to train
    - force: Force retraining even if model exists
    """
    try:
        # Queue the Celery task
        task = train_model.delay(ticker, model_type, force)
        
        return {
            "status": "success",
            "message": f"Model training task for {ticker} has been queued",
            "task_id": task.id
        }
    except Exception as e:
        logger.error(f"Error triggering model training for {ticker}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error triggering task: {str(e)}")


@router.post("/tasks/predict/{ticker}")
async def trigger_prediction(
    ticker: str,
    days: int = 7,
    model_type: str = "random_forest",
    user = Depends(get_current_active_user)
):
    """
    Trigger an asynchronous prediction task
    
    Parameters:
    - ticker: Stock ticker symbol
    - days: Number of days to predict
    - model_type: Type of ML model to use
    """
    try:
        # Queue the Celery task
        task = predict.delay(ticker, days, model_type)
        
        return {
            "status": "success",
            "message": f"Prediction task for {ticker} has been queued",
            "task_id": task.id
        }
    except Exception as e:
        logger.error(f"Error triggering prediction for {ticker}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error triggering task: {str(e)}")


@router.post("/tasks/batch-predict")
async def trigger_batch_prediction(
    tickers: List[str],
    days: int = 7,
    model_type: str = "random_forest",
    user = Depends(get_current_active_user)
):
    """
    Trigger an asynchronous batch prediction task
    
    Parameters:
    - tickers: List of stock ticker symbols
    - days: Number of days to predict
    - model_type: Type of ML model to use
    """
    try:
        if not tickers:
            raise HTTPException(status_code=400, detail="No tickers provided")
        
        # Queue the Celery task
        task = batch_predict.delay(tickers, days, model_type)
        
        return {
            "status": "success",
            "message": f"Batch prediction task for {len(tickers)} stocks has been queued",
            "task_id": task.id
        }
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error triggering batch prediction: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error triggering task: {str(e)}")


@router.get("/tasks/status/{task_id}")
async def check_task_status(
    task_id: str,
    user = Depends(get_current_active_user)
):
    """
    Check the status of an asynchronous task
    
    Parameters:
    - task_id: Task ID to check
    """
    try:
        task_result = get_task_info(task_id)
        
        if task_result.state == 'PENDING':
            response = {
                "status": "pending",
                "task_id": task_id,
                "message": "Task is pending execution"
            }
        elif task_result.state == 'STARTED':
            response = {
                "status": "started",
                "task_id": task_id,
                "message": "Task has started execution"
            }
        elif task_result.state == 'RETRY':
            response = {
                "status": "retry",
                "task_id": task_id,
                "message": "Task is being retried"
            }
        elif task_result.state == 'FAILURE':
            response = {
                "status": "failure",
                "task_id": task_id,
                "message": "Task execution failed",
                "error": str(task_result.result) if task_result.result else "Unknown error"
            }
        elif task_result.state == 'SUCCESS':
            response = {
                "status": "success",
                "task_id": task_id,
                "result": task_result.result
            }
        else:
            response = {
                "status": "unknown",
                "task_id": task_id,
                "state": task_result.state
            }
        
        return response
        
    except Exception as e:
        logger.error(f"Error checking task status for {task_id}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error checking task status: {str(e)}")


@router.get("/tasks/active")
async def get_active_tasks(
    user = Depends(get_current_active_superuser)
):
    """
    Get a list of active tasks (admin only)
    """
    try:
        # Get active tasks from Celery inspection
        i = celery_app.control.inspect()
        active_tasks = i.active()
        
        return {
            "status": "success",
            "active_tasks": active_tasks
        }
    except Exception as e:
        logger.error(f"Error getting active tasks: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error getting active tasks: {str(e)}")
