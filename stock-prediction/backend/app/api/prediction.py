"""
API routes for stock prediction
"""
from fastapi import APIRouter, HTTPException, Depends, Query
from sqlalchemy.orm import Session
from typing import List, Optional, Dict, Any
from datetime import datetime, timedelta

from app.core.database import get_db
from app.core.stock_predictor import get_prediction_service, StockPredictionService
from app.models.stocks import Stock

router = APIRouter(prefix="/api", tags=["prediction"])

@router.get("/prediction/{ticker}")
async def get_stock_prediction(
    ticker: str,
    days: int = Query(365, description="Number of days of historical data to use"),
    db: Session = Depends(get_db)
):
    """
    Generate prediction for a stock including entry/exit points, stop loss, and take profit targets
    
    Parameters:
    - ticker: Stock ticker symbol (e.g., 'BBCA.JK')
    - days: Number of days of historical data to use (default: 365)
    """
    # Check if ticker exists
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
    
    # Get prediction service
    prediction_service = get_prediction_service(db)
    
    # Generate prediction
    prediction_result = prediction_service.generate_prediction(ticker, days)
    
    # Add stock info
    prediction_result["stock_info"] = {
        "name": stock.name,
        "sector": stock.sector,
        "last_updated": stock.last_updated.strftime("%Y-%m-%d %H:%M:%S"),
    }
    
    return prediction_result

@router.get("/backtest/{ticker}")
async def backtest_strategy(
    ticker: str,
    strategy: str = Query("combined", description="Trading strategy to backtest"),
    days: int = Query(365, description="Number of days to backtest"),
    db: Session = Depends(get_db)
):
    """
    Backtest a trading strategy on historical data
    
    Parameters:
    - ticker: Stock ticker symbol (e.g., 'BBCA.JK')
    - strategy: Trading strategy to backtest (combined, ma_crossover, rsi, macd, bollinger)
    - days: Number of days to backtest (default: 365)
    """
    # Check if ticker exists
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
    
    # Check if strategy is valid
    valid_strategies = ["combined", "ma_crossover", "rsi", "macd", "bollinger"]
    if strategy not in valid_strategies:
        raise HTTPException(status_code=400, detail=f"Invalid strategy. Valid options: {', '.join(valid_strategies)}")
    
    # Get prediction service
    prediction_service = get_prediction_service(db)
    
    # Run backtest
    backtest_result = prediction_service.backtest_strategy(ticker, days, strategy)
    
    # Add stock info
    backtest_result["stock_info"] = {
        "name": stock.name,
        "sector": stock.sector,
    }
    
    return backtest_result


@router.get("/compare/{ticker}")
async def compare_strategies(
    ticker: str,
    days: int = Query(365, description="Number of days to analyze"),
    db: Session = Depends(get_db)
):
    """
    Compare all available trading strategies for a stock
    
    Parameters:
    - ticker: Stock ticker symbol (e.g., 'BBCA.JK')
    - days: Number of days to analyze (default: 365)
    """
    # Check if ticker exists
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
    
    # Get prediction service
    prediction_service = get_prediction_service(db)
    
    # List of strategies to compare
    strategies = ["combined", "ma_crossover", "rsi", "macd", "bollinger"]
    
    # Run backtest for each strategy
    results = []
    for strategy in strategies:
        result = prediction_service.backtest_strategy(ticker, days, strategy)
        
        # Extract summary data
        if "statistics" in result:
            summary = {
                "strategy": strategy,
                "win_rate": result["statistics"]["win_rate"],
                "avg_profit": result["statistics"]["avg_profit"],
                "total_trades": result["statistics"]["total_trades"],
            }
            results.append(summary)
    
    # Sort by win rate (descending)
    results.sort(key=lambda x: x["win_rate"], reverse=True)
    
    return {
        "ticker": ticker,
        "name": stock.name,
        "period": f"{days} days",
        "strategy_comparison": results,
        "best_strategy": results[0]["strategy"] if results else None,
    }


@router.get("/top-picks")
async def get_top_stock_picks(
    sector: Optional[str] = Query(None, description="Filter by sector"),
    limit: int = Query(10, description="Number of stocks to return"),
    db: Session = Depends(get_db)
):
    """
    Get top stock picks based on technical analysis
    
    Parameters:
    - sector: Filter by sector (optional)
    - limit: Number of stocks to return (default: 10)
    """
    # Get prediction service
    prediction_service = get_prediction_service(db)
    
    # Query stocks
    query = db.query(Stock).filter(Stock.is_active == True)
    
    # Apply sector filter if provided
    if sector:
        query = query.filter(Stock.sector == sector)
    
    # Get stocks
    stocks = query.all()
    
    # Generate predictions for each stock (limit to reasonable number)
    max_to_analyze = min(30, len(stocks))
    analyzed_stocks = []
    
    for stock in stocks[:max_to_analyze]:
        try:
            prediction = prediction_service.generate_prediction(stock.ticker, days=90)
            
            # Only include stocks with BUY signals or high confidence
            if prediction.get("action") == "BUY" or prediction.get("confidence", 0) > 70:
                analyzed_stocks.append({
                    "ticker": stock.ticker,
                    "name": stock.name,
                    "sector": stock.sector,
                    "action": prediction.get("action"),
                    "confidence": prediction.get("confidence", 0),
                    "recommendation": prediction.get("recommendation"),
                    "latest_price": prediction.get("latest_price"),
                    "target_price": prediction.get("price_targets", {}).get("target_1"),
                    "potential_gain": round((prediction.get("price_targets", {}).get("target_1", 0) / prediction.get("latest_price", 1) - 1) * 100, 2) if prediction.get("latest_price") else 0,
                })
        except Exception as e:
            # Skip stocks with errors in analysis
            continue
    
    # Sort by confidence and potential gain
    analyzed_stocks.sort(key=lambda x: (x["confidence"], x.get("potential_gain", 0)), reverse=True)
    
    # Return top picks
    return {
        "count": len(analyzed_stocks),
        "top_picks": analyzed_stocks[:limit]
    }
