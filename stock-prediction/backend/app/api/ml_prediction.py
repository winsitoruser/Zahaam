"""
API endpoints for machine learning stock prediction features
"""

from fastapi import APIRouter, Depends, HTTPException, Query
from sqlalchemy.orm import Session
from typing import Dict, Any, List, Optional
from datetime import datetime, timedelta
import logging

from app.core.database import get_db
from app.ml.models import get_stock_predictor
from app.models.stocks import Stock, StockPrice
from app.core.security import get_current_active_user, get_current_active_superuser_or_open_access
from app.utils.cache_manager import cached as cache_response

# Configure logging
logger = logging.getLogger(__name__)

router = APIRouter()

@router.get("/predict/{ticker}", response_model=Dict[str, Any])
@cache_response(ttl=3600)  # Cache for 1 hour
async def predict_stock(
    ticker: str,
    days: int = Query(7, description="Number of days to predict ahead"),
    model_type: str = Query("random_forest", description="ML model type to use"),
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Generate stock price predictions using machine learning models
    
    - **ticker**: Stock ticker symbol
    - **days**: Number of days to predict ahead (default: 7)
    - **model_type**: Machine learning model to use (default: random_forest)
    """
    try:
        # Check if stock exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            raise HTTPException(status_code=404, detail=f"Stock {ticker} not found")
        
        # Get historical data
        end_date = datetime.now()
        start_date = end_date - timedelta(days=365)
        
        prices = (db.query(StockPrice)
                 .filter(StockPrice.stock_id == stock.id)
                 .filter(StockPrice.date >= start_date)
                 .filter(StockPrice.date <= end_date)
                 .order_by(StockPrice.date)
                 .all())
        
        if not prices:
            raise HTTPException(status_code=404, detail=f"No price data found for {ticker}")
        
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
                'adjclose': price.close  # Using close as adjclose
            }
            for price in prices
        ])
        
        # Get predictor
        predictor = get_stock_predictor(ticker, model_type)
        
        # Check if model exists, train if not
        try:
            predictions = predictor.predict_next_days(df, days)
            
            # Add confidence based on metrics (if available)
            if predictor.metadata and 'metrics' in predictor.metadata:
                metrics = predictor.metadata['metrics']
                # Simple confidence calculation based on direction accuracy and R2
                direction_acc = metrics.get('direction_accuracy', 0)
                r2 = metrics.get('r2', 0)
                confidence = min(max(int((direction_acc * 70 + max(r2, 0) * 30)), 10), 90)
                predictions['confidence'] = confidence
                predictions['metrics'] = metrics
            
            return {
                'status': 'success',
                'data': predictions
            }
        except FileNotFoundError:
            # If model doesn't exist, train a new one
            logger.info(f"No model found for {ticker}, training new model")
            predictor.train(df)
            predictions = predictor.predict_next_days(df, days)
            
            # Add confidence (will be lower for newly trained models)
            if predictor.metadata and 'metrics' in predictor.metadata:
                metrics = predictor.metadata['metrics']
                direction_acc = metrics.get('direction_accuracy', 0)
                r2 = metrics.get('r2', 0)
                confidence = min(max(int((direction_acc * 70 + max(r2, 0) * 30)), 10), 90)
                predictions['confidence'] = confidence
                predictions['metrics'] = metrics
            else:
                predictions['confidence'] = 50  # Default 50% confidence
            
            return {
                'status': 'success',
                'data': predictions,
                'note': 'Model was newly trained'
            }
    
    except Exception as e:
        logger.error(f"Error predicting {ticker}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Prediction error: {str(e)}")


@router.post("/train/{ticker}", response_model=Dict[str, Any])
async def train_model(
    ticker: str,
    model_type: str = Query("random_forest", description="ML model type to use"),
    force: bool = Query(False, description="Force retraining even if model exists"),
    days: int = Query(365, description="Days of historical data to use"),
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_superuser_or_open_access)
):
    """
    Train or retrain a prediction model for a stock (admin only)
    
    - **ticker**: Stock ticker symbol
    - **model_type**: Machine learning model to use
    - **force**: Force retraining even if model exists
    - **days**: Days of historical data to use for training
    """
    try:
        # Check if stock exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            raise HTTPException(status_code=404, detail=f"Stock {ticker} not found")
        
        # Get historical data
        end_date = datetime.now()
        start_date = end_date - timedelta(days=days)
        
        prices = (db.query(StockPrice)
                 .filter(StockPrice.stock_id == stock.id)
                 .filter(StockPrice.date >= start_date)
                 .filter(StockPrice.date <= end_date)
                 .order_by(StockPrice.date)
                 .all())
        
        if not prices or len(prices) < 60:  # Need at least 60 data points
            raise HTTPException(
                status_code=400, 
                detail=f"Insufficient price data for {ticker}. Need at least 60 days."
            )
        
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
        
        # Get predictor
        predictor = get_stock_predictor(ticker, model_type)
        
        # Train model
        training_result = predictor.train(df)
        
        return {
            'status': 'success',
            'message': f"Model for {ticker} trained successfully",
            'metrics': training_result.get('metrics'),
            'feature_importance': training_result.get('feature_importance')
        }
    
    except Exception as e:
        logger.error(f"Error training model for {ticker}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Training error: {str(e)}")


@router.get("/model-info/{ticker}", response_model=Dict[str, Any])
async def get_model_info(
    ticker: str,
    model_type: str = Query("random_forest", description="ML model type to check"),
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Get information about a trained model
    
    - **ticker**: Stock ticker symbol
    - **model_type**: Machine learning model type
    """
    try:
        # Check if stock exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            raise HTTPException(status_code=404, detail=f"Stock {ticker} not found")
        
        # Get predictor
        predictor = get_stock_predictor(ticker, model_type)
        
        # Check if model exists
        if not predictor.model:
            return {
                'status': 'not_found',
                'message': f"No trained model found for {ticker}"
            }
        
        # Return metadata
        return {
            'status': 'success',
            'ticker': ticker,
            'model_type': model_type,
            'last_trained': predictor.metadata.get('last_train_date', 'Unknown'),
            'metrics': predictor.metadata.get('metrics', {}),
            'feature_importance': predictor.get_feature_importance()
        }
    
    except Exception as e:
        logger.error(f"Error getting model info for {ticker}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error: {str(e)}")
