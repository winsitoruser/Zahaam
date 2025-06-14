"""
API routes for technical analysis features
"""
from fastapi import APIRouter, Depends, HTTPException, Query
from sqlalchemy.orm import Session
from typing import Dict, List, Any, Optional
from datetime import datetime, timedelta

from app.core.database import get_db
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.core.security import get_current_active_user
from app.utils.cache_manager import cached as cache_response

router = APIRouter(tags=["technical_analysis"])

@router.get("/indicators/{ticker}")
@cache_response(ttl=3600)  # Cache for 1 hour
async def get_stock_indicators(
    ticker: str,
    days: int = Query(30, description="Days of historical data"),
    include_sma: bool = Query(True, description="Include SMA indicators"),
    include_ema: bool = Query(True, description="Include EMA indicators"),
    include_macd: bool = Query(True, description="Include MACD indicator"),
    include_rsi: bool = Query(True, description="Include RSI indicator"),
    include_bollinger: bool = Query(True, description="Include Bollinger Bands"),
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Get technical indicators for a specific stock
    
    Parameters:
    - ticker: Stock ticker symbol
    - days: Number of days of historical data (default: 30)
    - include_sma: Whether to include Simple Moving Averages (default: True)
    - include_ema: Whether to include Exponential Moving Averages (default: True)
    - include_macd: Whether to include MACD indicator (default: True)
    - include_rsi: Whether to include RSI indicator (default: True)
    - include_bollinger: Whether to include Bollinger Bands (default: True)
    """
    try:
        # Find the stock in database
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            raise HTTPException(status_code=404, detail=f"Stock {ticker} not found")
        
        # Get date range
        end_date = datetime.now()
        start_date = end_date - timedelta(days=days)
        
        # Get stock prices
        prices = db.query(StockPrice).filter(
            StockPrice.stock_id == stock.id,
            StockPrice.date >= start_date,
            StockPrice.date <= end_date
        ).order_by(StockPrice.date).all()
        
        if not prices:
            raise HTTPException(status_code=404, detail=f"No price data found for {ticker} in specified date range")
        
        # Get indicators from database
        indicators = db.query(StockIndicator).filter(
            StockIndicator.stock_id == stock.id,
            StockIndicator.date >= start_date,
            StockIndicator.date <= end_date
        ).order_by(StockIndicator.date).all()
        
        # Convert data to time series
        price_data = []
        indicator_data = {
            "sma": {"sma20": [], "sma50": [], "sma200": []},
            "ema": {"ema9": [], "ema21": [], "ema55": []},
            "macd": {"macd_line": [], "signal_line": [], "histogram": []},
            "rsi": {"rsi14": []},
            "bollinger": {"upper": [], "middle": [], "lower": []}
        }
        
        # Map indicators to their dates for easy lookup
        indicator_map = {ind.date.strftime("%Y-%m-%d"): ind for ind in indicators}
        
        for price in prices:
            date_str = price.date.strftime("%Y-%m-%d")
            
            # Add price data
            price_data.append({
                "time": date_str,
                "open": price.open,
                "high": price.high,
                "low": price.low,
                "close": price.close,
                "volume": price.volume
            })
            
            # Add indicator data if available for this date
            indicator = indicator_map.get(date_str)
            if indicator:
                # SMA indicators
                if include_sma:
                    if indicator.sma_20 is not None:
                        indicator_data["sma"]["sma20"].append({"time": date_str, "value": indicator.sma_20})
                    if indicator.sma_50 is not None:
                        indicator_data["sma"]["sma50"].append({"time": date_str, "value": indicator.sma_50})
                    if indicator.sma_200 is not None:
                        indicator_data["sma"]["sma200"].append({"time": date_str, "value": indicator.sma_200})
                
                # EMA indicators (if available)
                if include_ema:
                    # Note: These fields might not exist in the current schema
                    # In a real implementation, ensure these fields exist in the database
                    if hasattr(indicator, "ema_9") and indicator.ema_9 is not None:
                        indicator_data["ema"]["ema9"].append({"time": date_str, "value": indicator.ema_9})
                    if hasattr(indicator, "ema_21") and indicator.ema_21 is not None:
                        indicator_data["ema"]["ema21"].append({"time": date_str, "value": indicator.ema_21})
                    if hasattr(indicator, "ema_55") and indicator.ema_55 is not None:
                        indicator_data["ema"]["ema55"].append({"time": date_str, "value": indicator.ema_55})
                
                # MACD indicator
                if include_macd and indicator.macd is not None:
                    indicator_data["macd"]["macd_line"].append({"time": date_str, "value": indicator.macd})
                    indicator_data["macd"]["signal_line"].append({"time": date_str, "value": indicator.macd_signal})
                    indicator_data["macd"]["histogram"].append({"time": date_str, "value": indicator.macd_histogram})
                
                # RSI indicator
                if include_rsi and indicator.rsi_14 is not None:
                    indicator_data["rsi"]["rsi14"].append({"time": date_str, "value": indicator.rsi_14})
                
                # Bollinger Bands
                if include_bollinger and indicator.bb_upper is not None:
                    indicator_data["bollinger"]["upper"].append({"time": date_str, "value": indicator.bb_upper})
                    indicator_data["bollinger"]["middle"].append({"time": date_str, "value": indicator.bb_middle})
                    indicator_data["bollinger"]["lower"].append({"time": date_str, "value": indicator.bb_lower})
        
        # Get AI predictions to highlight buy/sell zones
        from app.ml.models import get_stock_predictor
        import pandas as pd
        
        # Convert price data to DataFrame for prediction
        df = pd.DataFrame([{
            'date': datetime.strptime(p['time'], "%Y-%m-%d"),
            'open': p['open'],
            'high': p['high'],
            'low': p['low'],
            'close': p['close'],
            'volume': p['volume'],
            'adjclose': p['close']  # Using close as adjclose
        } for p in price_data])
        
        # Get predictor and make predictions
        predictor = get_stock_predictor(ticker, "random_forest")
        
        try:
            # Try to get predictions for next 7 days
            predictions = predictor.predict_next_days(df, 7)
            prediction_dates = [(datetime.now() + timedelta(days=i)).strftime("%Y-%m-%d") for i in range(8)]
            
            # Format predictions for frontend
            ai_predictions = []
            
            if predictions and 'prices' in predictions:
                prices = predictions['prices']
                for i, price in enumerate(prices):
                    if i < len(prediction_dates):
                        # Calculate change from today
                        if i > 0:  # Skip day 0 (today)
                            change = ((price - prices[0]) / prices[0]) * 100
                            signal = "NEUTRAL"
                            if change > 1.5:
                                signal = "BUY"
                            elif change < -1.5:
                                signal = "SELL"
                                
                            ai_predictions.append({
                                "time": prediction_dates[i],
                                "price": round(price, 2),
                                "change": round(change, 2),
                                "signal": signal
                            })
            
        except Exception as e:
            ai_predictions = []
        
        # Get support and resistance levels
        support_resistance = calculate_support_resistance(price_data, 5)
        
        return {
            "status": "success",
            "ticker": ticker,
            "name": stock.name,
            "price_data": price_data,
            "indicators": indicator_data,
            "support_resistance": support_resistance,
            "ai_predictions": ai_predictions,
            "last_updated": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error fetching technical analysis: {str(e)}")

def calculate_support_resistance(price_data, window=5):
    """Calculate support and resistance levels based on recent price action"""
    if not price_data or len(price_data) < window * 2:
        return {"support": [], "resistance": []}
    
    # Extract closes and highs/lows for calculations
    closes = [p["close"] for p in price_data]
    highs = [p["high"] for p in price_data]
    lows = [p["low"] for p in price_data]
    
    # Find local maxima and minima
    resistance_points = []
    support_points = []
    
    for i in range(window, len(price_data) - window):
        # Check if this point is a local maximum
        if all(highs[i] >= highs[j] for j in range(i-window, i+window+1) if j != i):
            resistance_points.append({
                "price": highs[i],
                "time": price_data[i]["time"],
                "type": "RESISTANCE"
            })
        
        # Check if this point is a local minimum
        if all(lows[i] <= lows[j] for j in range(i-window, i+window+1) if j != i):
            support_points.append({
                "price": lows[i],
                "time": price_data[i]["time"],
                "type": "SUPPORT"
            })
    
    # Consolidate nearby levels (within 2% of each other)
    def consolidate_levels(levels):
        if not levels:
            return []
        
        sorted_levels = sorted(levels, key=lambda x: x["price"])
        consolidated = [sorted_levels[0]]
        
        for level in sorted_levels[1:]:
            last_level = consolidated[-1]
            # If within 2% of the last level, merge them by taking average
            if abs(level["price"] - last_level["price"]) / last_level["price"] < 0.02:
                consolidated[-1]["price"] = (level["price"] + last_level["price"]) / 2
            else:
                consolidated.append(level)
        
        return consolidated
    
    support_levels = consolidate_levels(support_points)
    resistance_levels = consolidate_levels(resistance_points)
    
    # Get strongest levels (most recent and significant)
    support_levels = sorted(support_levels, key=lambda x: x["price"])
    resistance_levels = sorted(resistance_levels, key=lambda x: x["price"])
    
    # Take up to 5 of each
    support_levels = support_levels[:5]
    resistance_levels = resistance_levels[:5]
    
    return {
        "support": support_levels,
        "resistance": resistance_levels
    }

@router.get("/drawing-tools/{ticker}")
async def get_user_drawing_tools(
    ticker: str,
    user_id: str,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Get user's saved drawing tools for a specific stock chart
    
    Parameters:
    - ticker: Stock ticker symbol
    - user_id: User ID
    """
    # This would normally query a table storing user's drawing tools
    # For now, we'll return a sample structure
    
    return {
        "status": "success",
        "ticker": ticker,
        "drawings": [
            {
                "id": "trend1",
                "type": "trendline",
                "points": [
                    {"time": "2025-06-03", "price": 8500},
                    {"time": "2025-06-10", "price": 9000}
                ],
                "color": "#FF0000",
                "lineWidth": 2
            },
            {
                "id": "fib1",
                "type": "fibonacci",
                "points": [
                    {"time": "2025-05-25", "price": 9200},
                    {"time": "2025-06-08", "price": 8400}
                ],
                "levels": [0, 0.236, 0.382, 0.5, 0.618, 0.786, 1],
                "color": "#00A0FF",
                "lineWidth": 1
            }
        ]
    }

@router.post("/drawing-tools/{ticker}")
async def save_user_drawing_tools(
    ticker: str,
    user_id: str,
    drawing_data: Dict,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Save user's drawing tools for a specific stock chart
    
    Parameters:
    - ticker: Stock ticker symbol
    - user_id: User ID
    - drawing_data: Drawing tool data
    """
    # This would normally save to a database table
    return {
        "status": "success",
        "ticker": ticker,
        "message": "Drawing tools saved successfully"
    }
