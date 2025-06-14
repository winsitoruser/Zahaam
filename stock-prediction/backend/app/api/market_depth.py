"""
API routes for market depth data
"""
from fastapi import APIRouter, Depends, HTTPException, Query, BackgroundTasks
from sqlalchemy.orm import Session
from typing import List, Optional, Dict, Any
from datetime import datetime, timedelta
import random
import numpy as np

from ..core.database import get_db
from ..models.stocks import Stock

router = APIRouter(tags=["market_depth"])

@router.get("/market-depth/{ticker}")
async def get_market_depth(
    ticker: str,
    levels: int = 10,
    real_time: bool = False,
    db: Session = Depends(get_db)
):
    """
    Get market depth data for a specific ticker
    
    Args:
        ticker: Stock ticker symbol
        levels: Number of price levels to return on each side (bid/ask)
        real_time: Whether to get real-time data or slightly delayed data
    
    Returns:
        Market depth data with bids and asks
    """
    try:
        # Verify ticker exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            raise HTTPException(status_code=404, detail=f"Stock with ticker {ticker} not found")
        
        # In a production environment, you would fetch real market depth data from an exchange API
        # For this demo, we'll generate realistic mock data based on the last price
        last_price = stock.last_price if stock.last_price else 5000  # Default for demo
        
        # Generate bid and ask data
        bid_data = generate_bid_data(last_price, levels)
        ask_data = generate_ask_data(last_price, levels)
        
        # Calculate total volumes
        total_bid_volume = sum(level['volume'] for level in bid_data)
        total_ask_volume = sum(level['volume'] for level in ask_data)
        
        return {
            "ticker": ticker,
            "timestamp": datetime.now().isoformat(),
            "last_price": last_price,
            "is_real_time": real_time,
            "bids": bid_data,
            "asks": ask_data,
            "total_bid_volume": total_bid_volume,
            "total_ask_volume": total_ask_volume
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error retrieving market depth data: {str(e)}")

def generate_bid_data(last_price: float, levels: int) -> List[Dict[str, Any]]:
    """Generate realistic bid data"""
    bid_data = []
    
    # Price decrements for bids (typically lower than last price)
    # Use smaller decrements for higher price levels
    for i in range(levels):
        # Price steps get larger as we go further from the mid price
        decrement = (i * 5) + random.randint(1, 5)
        price = last_price - decrement
        
        # Volume tends to be higher near the mid price
        volume_factor = 1.0 - (i / (levels * 2))
        volume = int((random.randint(10000, 50000) * volume_factor) / 100) * 100
        
        bid_data.append({
            "price": price,
            "volume": volume,
            "orders": random.randint(3, 20)
        })
    
    return bid_data

def generate_ask_data(last_price: float, levels: int) -> List[Dict[str, Any]]:
    """Generate realistic ask data"""
    ask_data = []
    
    # Price increments for asks (typically higher than last price)
    for i in range(levels):
        # Price steps get larger as we go further from the mid price
        increment = (i * 5) + random.randint(1, 5)
        price = last_price + increment
        
        # Volume tends to be higher near the mid price
        volume_factor = 1.0 - (i / (levels * 2))
        volume = int((random.randint(10000, 50000) * volume_factor) / 100) * 100
        
        ask_data.append({
            "price": price,
            "volume": volume,
            "orders": random.randint(3, 20)
        })
    
    return ask_data
