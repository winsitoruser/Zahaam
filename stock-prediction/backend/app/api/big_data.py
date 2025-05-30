"""
API routes for big data and machine learning integration
"""
from fastapi import APIRouter, Depends, HTTPException, Query, BackgroundTasks
from sqlalchemy.orm import Session
from typing import List, Optional, Dict, Any
from datetime import datetime, timedelta

from ..core.database import get_db
from ..core.data_processor import DataProcessor
from ..core.ml_engine import MLEngine
from ..models.stocks import Stock, StockPrice, StockIndicator
from app.models.big_data import (
    DataSource, MLModel, StockPrediction, MarketDataSnapshot,
    NewsArticle, DataProcessingJob
)
from app.core.data_sources import get_data_source_manager
from app.core.data_processor import get_data_processor
from app.core.ml_engine import get_ml_engine

router = APIRouter(tags=["big_data"])

@router.get("/data/sources")
async def get_data_sources(db: Session = Depends(get_db)):
    """Get list of available data sources"""
    # Get all configured data sources from database
    sources = db.query(DataSource).all()
    
    # Get data source manager to check which ones are currently available
    dsm = get_data_source_manager()
    available_sources = await dsm.get_available_sources()
    
    return {
        "sources": [
            {
                "id": source.id,
                "name": source.name,
                "description": source.description,
                "is_active": source.is_active,
                "is_available": source.name in available_sources,
                "last_checked": source.last_checked.strftime("%Y-%m-%d %H:%M:%S") if source.last_checked else None,
                "status": source.status
            }
            for source in sources
        ]
    }

@router.post("/data/process/{ticker}")
async def process_stock_data(
    ticker: str,
    days: int = 365,
    background_tasks: BackgroundTasks = None,
    db: Session = Depends(get_db)
):
    """
    Process stock data for a ticker from all available sources
    
    - Fetches data from multiple sources
    - Cleans and processes the data
    - Calculates technical indicators
    - Saves to database
    
    Can be run in background or synchronously
    """
    processor = get_data_processor(db)
    
    if background_tasks:
        # Process in background
        background_tasks.add_task(processor.process_stock_data, ticker, days)
        return {
            "message": f"Started processing data for {ticker}",
            "ticker": ticker,
            "days": days
        }
    else:
        # Process synchronously
        df, success = await processor.process_stock_data(ticker, days)
        if not success:
            raise HTTPException(status_code=500, detail=f"Failed to process data for {ticker}")
        
        return {
            "message": f"Successfully processed data for {ticker}",
            "ticker": ticker,
            "days": days,
            "rows_processed": len(df) if not df.empty else 0
        }

@router.post("/data/process/market")
async def process_market_data(
    background_tasks: BackgroundTasks = None,
    db: Session = Depends(get_db)
):
    """Process market-wide data"""
    processor = get_data_processor(db)
    
    if background_tasks:
        # Process in background
        background_tasks.add_task(processor.process_market_data)
        return {
            "message": "Started processing market data"
        }
    else:
        # Process synchronously
        success = await processor.process_market_data()
        if not success:
            raise HTTPException(status_code=500, detail="Failed to process market data")
        
        return {
            "message": "Successfully processed market data"
        }

@router.get("/data/market/latest")
async def get_latest_market_data(db: Session = Depends(get_db)):
    """Get the latest market data snapshot"""
    snapshot = (db.query(MarketDataSnapshot)
              .order_by(MarketDataSnapshot.date.desc())
              .first())
    
    if not snapshot:
        raise HTTPException(status_code=404, detail="No market data snapshot found")
    
    return {
        "date": snapshot.date.strftime("%Y-%m-%d"),
        "indices": {
            "sp500": snapshot.sp500,
            "nasdaq": snapshot.nasdaq,
            "dow_jones": snapshot.dow_jones,
            "vix": snapshot.vix
        },
        "sector_performance": snapshot.sector_performance
    }

@router.post("/ml/train/{ticker}")
async def train_model(
    ticker: str,
    model_type: str = "lstm",
    days: int = 500,
    background_tasks: BackgroundTasks = None,
    db: Session = Depends(get_db)
):
    """
    Train a machine learning model for stock prediction
    
    Args:
        ticker: Stock ticker symbol
        model_type: Type of model to train (linear, forest, gbm, lstm)
        days: Number of days of historical data to use
    """
    ml_engine = get_ml_engine(db)
    
    if background_tasks:
        # Train in background
        background_tasks.add_task(ml_engine.train_ml_model, ticker, model_type, days)
        return {
            "message": f"Started training {model_type} model for {ticker}",
            "ticker": ticker,
            "model_type": model_type
        }
    else:
        # Train synchronously
        model_id, success = await ml_engine.train_ml_model(ticker, model_type, days)
        if not success:
            raise HTTPException(status_code=500, detail=f"Failed to train model for {ticker}")
        
        return {
            "message": f"Successfully trained {model_type} model for {ticker}",
            "ticker": ticker,
            "model_type": model_type,
            "model_id": model_id
        }

@router.get("/ml/models")
async def get_ml_models(
    ticker: Optional[str] = None,
    active_only: bool = True,
    db: Session = Depends(get_db)
):
    """Get list of trained ML models"""
    query = db.query(MLModel)
    
    if ticker:
        query = query.filter(MLModel.name.like(f"{ticker}_%"))
    
    if active_only:
        query = query.filter(MLModel.is_active == True)
    
    models = query.order_by(MLModel.created_at.desc()).all()
    
    return {
        "models": [
            {
                "id": model.id,
                "name": model.name,
                "description": model.description,
                "model_type": model.model_type,
                "target": model.target,
                "version": model.version,
                "created_at": model.created_at.strftime("%Y-%m-%d %H:%M:%S"),
                "is_active": model.is_active,
                "metrics": model.metrics
            }
            for model in models
        ]
    }

@router.get("/ml/predict/{ticker}")
async def predict_stock(
    ticker: str,
    model_id: Optional[str] = None,
    interval: str = '1d',
    prediction_horizon: Optional[int] = None,
    db: Session = Depends(get_db)
):
    """
    Generate prediction for a stock using trained model
    
    Args:
        ticker: Stock ticker symbol
        model_id: ID of the model to use, or None to use the best available model
        interval: Data interval (1m, 5m, 15m, 30m, 1h, 1d, 1wk, 1mo)
        prediction_horizon: Number of intervals to predict into the future
        
    If model_id is not provided, uses the best available model for the ticker and timeframe
    """
    ml_engine = get_ml_engine(db)
    
    result = await ml_engine.generate_prediction(ticker, model_id, interval, prediction_horizon)
    
    if "error" in result:
        raise HTTPException(status_code=500, detail=result["error"])
    
    return result

@router.get("/ml/predictions/{ticker}")
async def get_stock_predictions(
    ticker: str,
    days: int = 30,
    interval: Optional[str] = None,
    db: Session = Depends(get_db)
):
    """Get historical predictions for a stock"""
    # Find the stock
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail=f"Stock {ticker} not found")
    
    # Get date range
    end_date = datetime.now()
    start_date = end_date - timedelta(days=days)
    
    # Build query for predictions
    query = (db.query(StockPrediction)
            .filter(StockPrediction.stock_id == stock.id)
            .filter(StockPrediction.prediction_date >= start_date)
            .filter(StockPrediction.prediction_date <= end_date))
            
    # Filter by interval if specified
    if interval:
        # Join with MLModel to filter by interval in parameters
        query = query.join(MLModel, StockPrediction.model_id == MLModel.id)
        query = query.filter(MLModel.parameters.contains({"interval": interval}))
    
    # Get predictions
    predictions = query.order_by(StockPrediction.prediction_date).all()
    
    # Get actual prices for comparison
    actual_prices = (db.query(StockPrice)
                   .filter(StockPrice.stock_id == stock.id)
                   .filter(StockPrice.date >= start_date)
                   .filter(StockPrice.date <= end_date)
                   .order_by(StockPrice.date)
                   .all())
    
    # Create a map of date to actual price
    price_map = {price.date.strftime("%Y-%m-%d"): price.close for price in actual_prices}
    
    # Get models for interval display
    model_map = {}
    model_ids = [p.model_id for p in predictions]
    if model_ids:
        models = db.query(MLModel).filter(MLModel.id.in_(model_ids)).all()
        model_map = {m.id: m for m in models}
    
    return {
        "ticker": ticker,
        "predictions": [
            {
                "prediction_date": prediction.prediction_date.strftime("%Y-%m-%d %H:%M:%S"),
                "target_date": prediction.target_date.strftime("%Y-%m-%d %H:%M:%S"),
                "predicted_price": prediction.predicted_price,
                "actual_price": price_map.get(prediction.target_date.strftime("%Y-%m-%d")),
                "upper_bound": prediction.upper_bound,
                "lower_bound": prediction.lower_bound,
                "signal": prediction.signal,
                "signal_strength": prediction.signal_strength,
                "model_id": prediction.model_id,
                "interval": model_map.get(prediction.model_id).parameters.get("interval", "1d") if prediction.model_id in model_map else "1d",
                "prediction_horizon": model_map.get(prediction.model_id).parameters.get("prediction_horizon", 5) if prediction.model_id in model_map else 5,
                "interval_display": self._format_interval_display(model_map.get(prediction.model_id)) if prediction.model_id in model_map else "5 days"
            }
            for prediction in predictions
        ]
    }

def _format_interval_display(self, model: Optional[MLModel]) -> str:
    """Format interval and horizon for display"""
    if not model or not model.parameters:
        return "5 days"
        
    interval = model.parameters.get("interval", "1d")
    horizon = model.parameters.get("prediction_horizon", 5)
    
    if interval == '1m':
        unit = "minute" if horizon == 1 else "minutes"
        return f"{horizon} {unit}"
    elif interval == '5m':
        return f"{5 * horizon} minutes"
    elif interval == '15m':
        return f"{15 * horizon} minutes"
    elif interval == '30m':
        return f"{30 * horizon} minutes"
    elif interval == '1h':
        unit = "hour" if horizon == 1 else "hours"
        return f"{horizon} {unit}"
    elif interval == '1d':
        unit = "day" if horizon == 1 else "days"
        return f"{horizon} {unit}"
    elif interval == '1wk':
        unit = "week" if horizon == 1 else "weeks"
        return f"{horizon} {unit}"
    elif interval == '1mo':
        unit = "month" if horizon == 1 else "months"
        return f"{horizon} {unit}"
    else:
        return f"{horizon} intervals of {interval}"

@router.get("/signals/dashboard")
async def get_trading_signals_dashboard(db: Session = Depends(get_db)):
    """Get trading signals dashboard with recent buy/sell signals"""
    # Get recent predictions with strong signals
    recent_predictions = (db.query(StockPrediction)
                       .filter(StockPrediction.prediction_date >= datetime.now() - timedelta(days=7))
                       .filter(StockPrediction.signal_strength >= 0.6)
                       .order_by(StockPrediction.prediction_date.desc())
                       .limit(100)
                       .all())
    
    # Group by signal type
    buy_signals = []
    sell_signals = []
    
    for prediction in recent_predictions:
        stock = db.query(Stock).filter(Stock.id == prediction.stock_id).first()
        if not stock:
            continue
            
        signal_data = {
            "ticker": stock.ticker,
            "name": stock.name,
            "sector": stock.sector,
            "prediction_date": prediction.prediction_date.strftime("%Y-%m-%d"),
            "target_date": prediction.target_date.strftime("%Y-%m-%d"),
            "current_price": None,  # Will be filled below
            "predicted_price": prediction.predicted_price,
            "signal_strength": prediction.signal_strength,
            "model_id": prediction.model_id
        }
        
        # Get current price
        latest_price = (db.query(StockPrice)
                      .filter(StockPrice.stock_id == stock.id)
                      .order_by(StockPrice.date.desc())
                      .first())
        
        if latest_price:
            signal_data["current_price"] = latest_price.close
            
        if prediction.signal == "BUY":
            buy_signals.append(signal_data)
        elif prediction.signal == "SELL":
            sell_signals.append(signal_data)
    
    # Get market overview
    market_snapshot = (db.query(MarketDataSnapshot)
                    .order_by(MarketDataSnapshot.date.desc())
                    .first())
    
    market_data = {}
    if market_snapshot:
        market_data = {
            "date": market_snapshot.date.strftime("%Y-%m-%d"),
            "indices": {
                "sp500": market_snapshot.sp500,
                "nasdaq": market_snapshot.nasdaq,
                "dow_jones": market_snapshot.dow_jones,
                "vix": market_snapshot.vix
            },
            "sector_performance": market_snapshot.sector_performance
        }
    
    return {
        "buy_signals": buy_signals[:10],  # Top 10 buy signals
        "sell_signals": sell_signals[:10],  # Top 10 sell signals
        "market_overview": market_data,
        "updated_at": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    }

@router.get("/ml/timeframe/predictions/{ticker}")
async def get_multi_timeframe_predictions(
    ticker: str,
    db: Session = Depends(get_db)
):
    """
    Generate predictions for multiple timeframes for a stock
    
    Returns predictions for 1 hour, 3 hours, 24 hours, 7 days, and 30 days
    """
    # Define timeframes we want to retrieve with proper API mapping
    timeframe_configs = [
        {"api_timeframe": "1h", "interval": "1h", "horizon": 1},   # 1 hour
        {"api_timeframe": "3h", "interval": "1h", "horizon": 3},   # 3 hours
        {"api_timeframe": "24h", "interval": "1h", "horizon": 24},  # 24 hours
        {"api_timeframe": "7d", "interval": "1d", "horizon": 7},   # 7 days
        {"api_timeframe": "30d", "interval": "1d", "horizon": 30}  # 30 days
    ]
    
    ml_engine = get_ml_engine(db)
    data_processor = get_data_processor(db)
    
    # Process stock data to ensure we have the latest data
    processed_data, success = await data_processor.process_stock_data(ticker, days=365)
    if not success:
        raise HTTPException(status_code=500, detail=f"Failed to process data for {ticker}")
    
    # Placeholder for all timeframe predictions
    all_predictions = []
    
    # Fetch current time for reference
    current_time = datetime.now()
    
    # Get predictions for each timeframe
    for config in timeframe_configs:
        timeframe = config["api_timeframe"]
        interval = config["interval"]
        prediction_horizon = config["horizon"]
        
        # Check if we have a recent prediction for this timeframe
        recent_prediction = (
            db.query(StockPrediction)
            .filter(
                StockPrediction.ticker == ticker,
                StockPrediction.timeframe == timeframe,
                StockPrediction.created_at >= current_time - datetime.timedelta(hours=6)  # Consider predictions made in last 6 hours as recent
            )
            .order_by(StockPrediction.created_at.desc())
            .first()
        )
        
        # If no recent prediction, generate a new one
        prediction_data = None
        if not recent_prediction:
            try:
                # Generate prediction for this timeframe
                prediction_result = await ml_engine.generate_prediction(
                    ticker=ticker,
                    model_type='lstm',  # Using LSTM model by default
                    interval=interval,
                    prediction_horizon=prediction_horizon
                )
                
                # If prediction successful, get it from database
                if prediction_result and isinstance(prediction_result, dict) and "id" in prediction_result:
                    prediction_id = prediction_result["id"]
                    recent_prediction = db.query(StockPrediction).filter(StockPrediction.id == prediction_id).first()
            except Exception as e:
                print(f"Error generating prediction for {ticker} on timeframe {timeframe}: {str(e)}")
                # Continue with other timeframes even if one fails
                continue
        
        # If we have a prediction, format and add to results
        if recent_prediction:
            # Format prediction data for frontend
            prediction_data = {
                'timeframe': timeframe,
                'historical_data': [],
                'prediction_dates': [],
                'predicted_values': [],
                'upper_bound': [],
                'lower_bound': [],
                'signal': recent_prediction.signal,
                'signal_strength': recent_prediction.signal_strength or 0.5,
                'confidence': recent_prediction.confidence or 85
            }
            
            # Add historical data points (last 30 points for context)
            if processed_data is not None and not processed_data.empty:
                last_points = processed_data.tail(30).to_dict('records')
                for point in last_points:
                    prediction_data['historical_data'].append({
                        'date': point.get('date', '').isoformat() if hasattr(point.get('date', ''), 'isoformat') else str(point.get('date', '')),
                        'open': float(point.get('open', 0)),
                        'high': float(point.get('high', 0)),
                        'low': float(point.get('low', 0)),
                        'close': float(point.get('close', 0)),
                        'volume': float(point.get('volume', 0))
                    })
            
            # Add prediction dates and values
            if recent_prediction.prediction_data:
                for date_str, value in recent_prediction.prediction_data.items():
                    prediction_data['prediction_dates'].append(date_str)
                    prediction_data['predicted_values'].append(float(value))
            
            # Add confidence intervals if available
            if recent_prediction.confidence_intervals:
                for date_str, interval in recent_prediction.confidence_intervals.items():
                    if date_str in recent_prediction.prediction_data:  # Make sure date exists in predictions
                        if 'upper' in interval:
                            prediction_data['upper_bound'].append(float(interval['upper']))
                        if 'lower' in interval:
                            prediction_data['lower_bound'].append(float(interval['lower']))
            
            # If bounds aren't available, create them (±2%)
            if not prediction_data['upper_bound'] and prediction_data['predicted_values']:
                prediction_data['upper_bound'] = [val * 1.02 for val in prediction_data['predicted_values']]
            if not prediction_data['lower_bound'] and prediction_data['predicted_values']:
                prediction_data['lower_bound'] = [val * 0.98 for val in prediction_data['predicted_values']]
            
            all_predictions.append(prediction_data)
    
    # Return formatted response
    return {
        "ticker": ticker,
        "predictions": all_predictions,
        "timestamp": current_time.isoformat(),
        "count": len(all_predictions)
    }

@router.get("/ml/signals/dashboard")
async def get_trading_signals_dashboard(db: Session = Depends(get_db)):
    """
    Get trading signals dashboard with signals from multiple timeframes for top stocks
    Returns signals, strength and consensus for various stocks
    """
    ml_engine = get_ml_engine(db)
    
    # List of top stocks to analyze
    top_stocks = ['BBRI', 'BBCA', 'TLKM', 'ASII', 'UNVR', 'BMRI', 'INDF', 'SMGR', 'PGAS', 'ICBP']
    
    # Get signals for each stock
    signals = []
    for ticker in top_stocks:
        try:
            # Get signal strength metrics
            signal_metrics = ml_engine.calculate_signal_strength(ticker)
            
            # Get latest stock price
            latest_price = db.query(StockPrice).filter(
                StockPrice.ticker == ticker
            ).order_by(StockPrice.date.desc()).first()
            
            # Add price info to signal metrics
            if latest_price:
                signal_metrics['current_price'] = latest_price.close
                signal_metrics['price_change'] = latest_price.change
                signal_metrics['price_change_percent'] = latest_price.change_percent
            
            signals.append(signal_metrics)
        except Exception as e:
            print(f"Error calculating signal for {ticker}: {str(e)}")
    
    # Sort by consensus score (descending)
    signals.sort(key=lambda x: x.get('consensus_score', 0), reverse=True)
    
    # Group signals by overall signal type
    buy_signals = [s for s in signals if s.get('overall_signal') == 'BUY']
    sell_signals = [s for s in signals if s.get('overall_signal') == 'SELL']
    hold_signals = [s for s in signals if s.get('overall_signal') == 'HOLD']
    
    return {
        "signals": signals,
        "buy_signals": buy_signals,
        "sell_signals": sell_signals,
        "hold_signals": hold_signals,
        "signal_count": {
            "buy": len(buy_signals),
            "sell": len(sell_signals),
            "hold": len(hold_signals)
        },
        "timestamp": datetime.now().isoformat()
    }

@router.get("/jobs/status")
async def get_job_status(
    job_type: Optional[str] = None,
    limit: int = 10,
    db: Session = Depends(get_db)
):
    """Get status of background data processing jobs"""
    query = db.query(DataProcessingJob)
    
    if job_type:
        query = query.filter(DataProcessingJob.job_type == job_type)
    
    jobs = query.order_by(DataProcessingJob.started_at.desc()).limit(limit).all()
    
    return {
        "jobs": [
            {
                "id": job.id,
                "job_type": job.job_type,
                "status": job.status,
                "started_at": job.started_at.strftime("%Y-%m-%d %H:%M:%S"),
                "completed_at": job.completed_at.strftime("%Y-%m-%d %H:%M:%S") if job.completed_at else None,
                "parameters": job.parameters,
                "results": job.results,
                "error": job.error_message
            }
            for job in jobs
        ]
    }
