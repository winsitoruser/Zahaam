"""
Machine Learning job functions for background processing
"""

import logging
from typing import List, Dict, Any, Optional
from datetime import datetime, timedelta
from sqlalchemy.orm import Session

from app.core.database import SessionLocal
from app.models.stocks import Stock, StockPrice
from app.ml.models import get_stock_predictor
from app.utils.cache_manager import CacheManager

logger = logging.getLogger(__name__)

def train_ml_models():
    """
    Train or update ML models for top active stocks
    """
    logger.info("Running scheduled job: train_ml_models")
    db = SessionLocal()
    
    try:
        # Get top active stocks by trading volume
        stocks = db.query(Stock).filter(Stock.is_active == True).order_by(Stock.volume.desc()).limit(10).all()
        
        if not stocks:
            logger.warning("No active stocks found for ML training")
            return 0
        
        trained_count = 0
        for stock in stocks:
            try:
                logger.info(f"Training ML model for {stock.ticker}")
                
                # Get historical data (1 year)
                end_date = datetime.now()
                start_date = end_date - timedelta(days=365)
                
                prices = (db.query(StockPrice)
                         .filter(StockPrice.stock_id == stock.id)
                         .filter(StockPrice.date >= start_date)
                         .filter(StockPrice.date <= end_date)
                         .order_by(StockPrice.date)
                         .all())
                
                if not prices or len(prices) < 60:  # Need at least 60 data points
                    logger.warning(f"Insufficient price data for {stock.ticker}. Need at least 60 days.")
                    continue
                
                # Convert to DataFrame
                import pandas as pd
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
                
                # Get predictor and train
                predictor = get_stock_predictor(stock.ticker)
                training_result = predictor.train(df)
                
                # Log results
                metrics = training_result.get('metrics', {})
                rmse = metrics.get('rmse', 'N/A')
                direction_acc = metrics.get('direction_accuracy', 'N/A') 
                logger.info(f"Model for {stock.ticker} trained successfully. RMSE: {rmse}, Direction Accuracy: {direction_acc}")
                
                # Clear cache for this stock's predictions
                CacheManager.delete(f"predict_stock_{stock.ticker}")
                
                trained_count += 1
                
            except Exception as e:
                logger.error(f"Error training model for {stock.ticker}: {str(e)}")
        
        logger.info(f"Successfully trained {trained_count} models")
        return trained_count
    
    except Exception as e:
        logger.error(f"Error in train_ml_models job: {str(e)}")
        return 0
    
    finally:
        db.close()


def monitor_model_performance():
    """
    Monitor ML model performance and log alerts if accuracy drops
    """
    logger.info("Running scheduled job: monitor_model_performance")
    db = SessionLocal()
    
    try:
        # Get all stocks with active models
        stocks = db.query(Stock).filter(Stock.is_active == True).all()
        
        import os
        from app.ml.models import MODEL_DIR
        
        monitored_count = 0
        for stock in stocks:
            try:
                # Check if model exists
                model_path = os.path.join(MODEL_DIR, f"{stock.ticker}_random_forest_model.joblib")
                metadata_path = os.path.join(MODEL_DIR, f"{stock.ticker}_random_forest_metadata.pkl")
                
                if not (os.path.exists(model_path) and os.path.exists(metadata_path)):
                    continue
                
                # Load predictor
                predictor = get_stock_predictor(stock.ticker)
                
                if not predictor.metadata or 'metrics' not in predictor.metadata:
                    continue
                
                # Check metrics
                metrics = predictor.metadata['metrics']
                direction_accuracy = metrics.get('direction_accuracy', 0)
                r2_score = metrics.get('r2', 0)
                
                # Alert on poor performance
                if direction_accuracy < 0.55:
                    logger.warning(f"Model for {stock.ticker} has low direction accuracy: {direction_accuracy:.2f}")
                    
                # Check model age
                last_trained = predictor.metadata.get('last_train_date', None)
                if last_trained:
                    last_trained_date = datetime.strptime(last_trained, '%Y-%m-%d %H:%M:%S')
                    days_since_training = (datetime.now() - last_trained_date).days
                    
                    if days_since_training > 30:
                        logger.warning(f"Model for {stock.ticker} was last trained {days_since_training} days ago")
                
                monitored_count += 1
                
            except Exception as e:
                logger.error(f"Error monitoring model for {stock.ticker}: {str(e)}")
        
        logger.info(f"Monitored {monitored_count} models")
        return monitored_count
    
    except Exception as e:
        logger.error(f"Error in monitor_model_performance job: {str(e)}")
        return 0
    
    finally:
        db.close()
