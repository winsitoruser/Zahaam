"""
Machine Learning tasks for Celery
"""
import logging
from datetime import datetime
import pandas as pd
from sqlalchemy.orm import Session

from app.core.celery_app import celery_app
from app.core.database import SessionLocal
from app.models.stocks import Stock, StockPrice
from app.ml.models import get_stock_predictor

logger = logging.getLogger(__name__)

@celery_app.task(name="app.tasks.ml_tasks.train_model")
def train_model(ticker: str, model_type: str = "random_forest", force: bool = False):
    """
    Train a prediction model for a specific stock
    
    Args:
        ticker: Stock ticker symbol
        model_type: ML model type to use
        force: Force retraining even if model exists
    """
    logger.info(f"Training {model_type} model for {ticker}")
    try:
        db = SessionLocal()
        
        # Check if stock exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            logger.error(f"Stock {ticker} not found in database")
            db.close()
            return {"status": "error", "message": f"Stock {ticker} not found"}
        
        # Get historical data
        prices = (db.query(StockPrice)
                 .filter(StockPrice.stock_id == stock.id)
                 .order_by(StockPrice.date)
                 .all())
        
        if not prices:
            logger.error(f"No price data found for {ticker}")
            db.close()
            return {"status": "error", "message": f"No price data for {ticker}"}
        
        # Convert to DataFrame
        df = pd.DataFrame([
            {
                'date': price.date,
                'open': price.open,
                'high': price.high,
                'low': price.low,
                'close': price.close,
                'volume': price.volume,
                'adjclose': price.close
            }
            for price in prices
        ])
        
        # Train model
        predictor = get_stock_predictor(ticker, model_type)
        
        # Check if model exists and force is False
        if not force and predictor.model is not None:
            db.close()
            return {
                "status": "skipped", 
                "message": f"Model for {ticker} already exists and force=False"
            }
        
        # Train model
        metrics = predictor.train(df)
        
        # Update stock model info in database
        stock.last_model_update = datetime.now()
        db.commit()
        
        db.close()
        return {
            "status": "success", 
            "message": f"Model for {ticker} trained successfully",
            "metrics": metrics
        }
        
    except Exception as e:
        logger.error(f"Error training model for {ticker}: {str(e)}")
        if 'db' in locals():
            db.close()
        return {"status": "error", "message": str(e)}


@celery_app.task(name="app.tasks.ml_tasks.batch_predict")
def batch_predict(tickers: list, days: int = 7, model_type: str = "random_forest"):
    """
    Generate predictions for multiple stocks in batch
    
    Args:
        tickers: List of stock ticker symbols
        days: Number of days to predict ahead
        model_type: ML model type to use
    """
    logger.info(f"Batch predicting {len(tickers)} stocks for {days} days")
    results = {}
    
    for ticker in tickers:
        try:
            # This could call the predict function directly or reuse existing code
            # For now, we'll just track that we processed this ticker
            results[ticker] = {"status": "queued"}
            
            # Start individual prediction task
            predict.delay(ticker, days, model_type)
            
        except Exception as e:
            logger.error(f"Error queueing prediction for {ticker}: {str(e)}")
            results[ticker] = {"status": "error", "message": str(e)}
    
    return {
        "status": "success",
        "message": f"Batch prediction started for {len(tickers)} stocks",
        "results": results
    }


@celery_app.task(name="app.tasks.ml_tasks.predict")
def predict(ticker: str, days: int = 7, model_type: str = "random_forest"):
    """
    Generate prediction for a single stock
    
    Args:
        ticker: Stock ticker symbol
        days: Number of days to predict ahead
        model_type: ML model type to use
    """
    logger.info(f"Predicting {ticker} for {days} days using {model_type}")
    try:
        db = SessionLocal()
        
        # Check if stock exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            logger.error(f"Stock {ticker} not found in database")
            db.close()
            return {"status": "error", "message": f"Stock {ticker} not found"}
        
        # Get historical data
        prices = (db.query(StockPrice)
                 .filter(StockPrice.stock_id == stock.id)
                 .order_by(StockPrice.date)
                 .all())
        
        if not prices:
            logger.error(f"No price data found for {ticker}")
            db.close()
            return {"status": "error", "message": f"No price data for {ticker}"}
        
        # Convert to DataFrame
        df = pd.DataFrame([
            {
                'date': price.date,
                'open': price.open,
                'high': price.high,
                'low': price.low,
                'close': price.close,
                'volume': price.volume,
                'adjclose': price.close
            }
            for price in prices
        ])
        
        # Get predictor
        predictor = get_stock_predictor(ticker, model_type)
        
        try:
            predictions = predictor.predict_next_days(df, days)
            db.close()
            return {
                "status": "success",
                "ticker": ticker,
                "data": predictions
            }
        except FileNotFoundError:
            # If model doesn't exist, train a new one
            logger.info(f"No model found for {ticker}, training new model")
            train_model(ticker, model_type)
            
            # Try prediction again
            predictions = predictor.predict_next_days(df, days)
            db.close()
            return {
                "status": "success",
                "ticker": ticker,
                "data": predictions
            }
        
    except Exception as e:
        logger.error(f"Error predicting {ticker}: {str(e)}")
        if 'db' in locals():
            db.close()
        return {"status": "error", "message": str(e)}


@celery_app.task(name="app.tasks.ml_tasks.train_ml_models")
def train_ml_models():
    """Train or update ML models for all active stocks"""
    logger.info("Running scheduled job: train_ml_models")
    db = SessionLocal()
    
    try:
        # Get all active stocks
        stocks = db.query(Stock).filter(Stock.is_active == True).all()
        logger.info(f"Found {len(stocks)} active stocks to train models for")
        
        # Queue training tasks for each stock
        for stock in stocks:
            # First check if model is outdated or missing
            last_update = stock.last_model_update
            needs_update = last_update is None or (datetime.now() - last_update).days >= 7
            
            if needs_update:
                train_model.delay(stock.ticker)
        
        db.close()
        return {"status": "success", "message": f"Queued training for {len(stocks)} stocks"}
        
    except Exception as e:
        logger.error(f"Error in train_ml_models job: {str(e)}")
        if 'db' in locals():
            db.close()
        return {"status": "error", "message": str(e)}


@celery_app.task(name="app.tasks.ml_tasks.monitor_model_performance")
def monitor_model_performance():
    """Monitor performance of ML models"""
    logger.info("Running scheduled job: monitor_model_performance")
    db = SessionLocal()
    
    try:
        # Get all active stocks
        stocks = db.query(Stock).filter(Stock.is_active == True).all()
        
        results = {
            "evaluated": 0,
            "needs_retraining": [],
            "performance": {}
        }
        
        for stock in stocks:
            try:
                # This would normally evaluate the model performance against recent data
                # and flag models that need retraining
                
                # For now, just check if model exists
                predictor = get_stock_predictor(stock.ticker)
                
                if predictor.model is not None:
                    # Check if model performance metrics are available
                    if predictor.metadata and 'metrics' in predictor.metadata:
                        metrics = predictor.metadata['metrics']
                        
                        # Track performance
                        results["performance"][stock.ticker] = {
                            "r2": metrics.get("r2", 0),
                            "direction_accuracy": metrics.get("direction_accuracy", 0)
                        }
                        
                        # Check if model performance is below threshold
                        if metrics.get("direction_accuracy", 0) < 0.55:
                            results["needs_retraining"].append(stock.ticker)
                            # Queue retraining task
                            train_model.delay(stock.ticker, force=True)
                    
                    results["evaluated"] += 1
            
            except Exception as e:
                logger.error(f"Error monitoring model for {stock.ticker}: {str(e)}")
        
        db.close()
        return {
            "status": "success",
            "message": f"Monitored {results['evaluated']} models, found {len(results['needs_retraining'])} needing retraining",
            "results": results
        }
        
    except Exception as e:
        logger.error(f"Error in monitor_model_performance job: {str(e)}")
        if 'db' in locals():
            db.close()
        return {"status": "error", "message": str(e)}
