"""
API routes for user watchlists
"""
from fastapi import APIRouter, HTTPException, Depends, Query, Path
from typing import List, Dict, Any, Optional
from sqlalchemy.orm import Session
from datetime import datetime
import pandas as pd

from app.core.database import get_db
from app.models.watchlist import WatchlistItem
from app.models.stocks import Stock, StockPrice
from app.models.user_strategies import User
from app.core.stock_predictor import get_prediction_service

router = APIRouter(prefix="/api", tags=["watchlist"])

# Mock user ID for demo (in production, get from authentication)
MOCK_USER_ID = 1

@router.get("/watchlist")
async def get_watchlist(
    db: Session = Depends(get_db)
):
    """
    Get all watchlist items for the current user
    """
    # In production, get user ID from auth token
    user_id = MOCK_USER_ID
    
    # Check if user exists
    user = db.query(User).filter(User.id == user_id).first()
    if not user:
        # Create mock user for demo
        user = User(
            id=MOCK_USER_ID,
            username="demo_user",
            email="demo@example.com",
            hashed_password="hashed_demo_password",
        )
        db.add(user)
        db.commit()
        db.refresh(user)
    
    # Get watchlist items
    watchlist_items = db.query(WatchlistItem).filter(
        WatchlistItem.user_id == user_id
    ).all()
    
    result_items = []
    
    for item in watchlist_items:
        # Get stock info
        stock = db.query(Stock).filter(Stock.ticker == item.ticker).first()
        
        if not stock:
            stock_info = {
                "ticker": item.ticker,
                "name": f"Unknown Stock ({item.ticker})",
                "sector": "Unknown",
                "current_price": None,
                "last_updated": None
            }
        else:
            # Get the latest price
            latest_price = (
                db.query(StockPrice)
                .filter(StockPrice.stock_id == stock.id)
                .order_by(StockPrice.date.desc())
                .first()
            )
            
            stock_info = {
                "ticker": item.ticker,
                "name": stock.name,
                "sector": stock.sector,
                "current_price": latest_price.close if latest_price else None,
                "last_updated": stock.last_updated.strftime("%Y-%m-%d %H:%M:%S") if stock.last_updated else None
            }
        
        # Add watchlist item details
        watchlist_item = {
            "id": item.id,
            "ticker": item.ticker,
            "added_at": item.added_at.strftime("%Y-%m-%d %H:%M:%S"),
            "notes": item.notes,
            "is_favorite": item.is_favorite,
            "alert_price_high": item.alert_price_high,
            "alert_price_low": item.alert_price_low,
            "stock_info": stock_info
        }
        
        result_items.append(watchlist_item)
    
    return {
        "watchlist": result_items,
        "count": len(result_items)
    }

@router.post("/watchlist")
async def add_to_watchlist(
    ticker: str,
    notes: Optional[str] = None,
    is_favorite: bool = False,
    alert_price_high: Optional[float] = None,
    alert_price_low: Optional[float] = None,
    db: Session = Depends(get_db)
):
    """
    Add a stock to the user's watchlist
    """
    user_id = MOCK_USER_ID
    
    # Check if user exists
    user = db.query(User).filter(User.id == user_id).first()
    if not user:
        raise HTTPException(status_code=404, detail="User not found")
    
    # Check if stock exists
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
    
    # Check if already in watchlist
    existing_item = db.query(WatchlistItem).filter(
        WatchlistItem.user_id == user_id,
        WatchlistItem.ticker == ticker
    ).first()
    
    if existing_item:
        raise HTTPException(status_code=400, detail="Stock already in watchlist")
    
    # Create new watchlist item
    new_item = WatchlistItem(
        user_id=user_id,
        ticker=ticker,
        notes=notes,
        is_favorite=is_favorite,
        alert_price_high=alert_price_high,
        alert_price_low=alert_price_low
    )
    
    db.add(new_item)
    db.commit()
    db.refresh(new_item)
    
    return {
        "message": f"Added {ticker} to watchlist",
        "id": new_item.id,
        "ticker": ticker
    }

@router.put("/watchlist/{item_id}")
async def update_watchlist_item(
    item_id: int = Path(...),
    notes: Optional[str] = None,
    is_favorite: Optional[bool] = None,
    alert_price_high: Optional[float] = None,
    alert_price_low: Optional[float] = None,
    db: Session = Depends(get_db)
):
    """
    Update a watchlist item
    """
    user_id = MOCK_USER_ID
    
    # Get the watchlist item
    item = db.query(WatchlistItem).filter(
        WatchlistItem.id == item_id,
        WatchlistItem.user_id == user_id
    ).first()
    
    if not item:
        raise HTTPException(status_code=404, detail="Watchlist item not found")
    
    # Update fields if provided
    if notes is not None:
        item.notes = notes
    
    if is_favorite is not None:
        item.is_favorite = is_favorite
    
    if alert_price_high is not None:
        item.alert_price_high = alert_price_high
    
    if alert_price_low is not None:
        item.alert_price_low = alert_price_low
    
    db.commit()
    db.refresh(item)
    
    return {
        "message": f"Updated watchlist item {item.ticker}",
        "id": item.id,
        "ticker": item.ticker
    }

@router.delete("/watchlist/{item_id}")
async def remove_from_watchlist(
    item_id: int = Path(...),
    db: Session = Depends(get_db)
):
    """
    Remove a stock from the user's watchlist
    """
    user_id = MOCK_USER_ID
    
    # Get the watchlist item
    item = db.query(WatchlistItem).filter(
        WatchlistItem.id == item_id,
        WatchlistItem.user_id == user_id
    ).first()
    
    if not item:
        raise HTTPException(status_code=404, detail="Watchlist item not found")
    
    ticker = item.ticker
    
    # Delete the item
    db.delete(item)
    db.commit()
    
    return {
        "message": f"Removed {ticker} from watchlist",
        "id": item_id,
        "ticker": ticker
    }

@router.get("/watchlist/predictions")
async def get_watchlist_predictions(
    strategy_id: Optional[int] = Query(None, description="Strategy ID to use for predictions"),
    db: Session = Depends(get_db)
):
    """
    Get predictions for all stocks in the user's watchlist
    """
    user_id = MOCK_USER_ID
    
    # Get watchlist items
    watchlist_items = db.query(WatchlistItem).filter(
        WatchlistItem.user_id == user_id
    ).all()
    
    if not watchlist_items:
        return {
            "predictions": [],
            "count": 0
        }
    
    # Get prediction service
    prediction_service = get_prediction_service(db)
    
    results = []
    
    for item in watchlist_items:
        try:
            # Get stock info
            stock = db.query(Stock).filter(Stock.ticker == item.ticker).first()
            
            if not stock:
                continue
                
            # Generate prediction
            prediction = prediction_service.generate_prediction(item.ticker, days=90)
            
            # Create result
            result = {
                "ticker": item.ticker,
                "name": stock.name,
                "sector": stock.sector,
                "latest_price": prediction.get("latest_price"),
                "action": prediction.get("action"),
                "recommendation": prediction.get("recommendation"),
                "entry_point": prediction.get("entry_point"),
                "exit_point": prediction.get("exit_point"),
                "notes": item.notes,
                "is_favorite": item.is_favorite
            }
            
            results.append(result)
            
        except Exception as e:
            # Skip stocks with errors
            continue
    
    # Sort by favorites first, then by action (BUY first)
    results.sort(key=lambda x: (not x["is_favorite"], x["action"] != "BUY"))
    
    return {
        "predictions": results,
        "count": len(results)
    }

@router.get("/watchlist/alerts")
async def get_watchlist_alerts(
    db: Session = Depends(get_db)
):
    """
    Get price alerts for stocks in the user's watchlist
    """
    user_id = MOCK_USER_ID
    
    # Get watchlist items with alerts
    watchlist_items = db.query(WatchlistItem).filter(
        WatchlistItem.user_id == user_id,
        (WatchlistItem.alert_price_high.isnot(None)) | 
        (WatchlistItem.alert_price_low.isnot(None))
    ).all()
    
    if not watchlist_items:
        return {
            "alerts": [],
            "count": 0
        }
    
    alerts = []
    
    for item in watchlist_items:
        # Get stock info
        stock = db.query(Stock).filter(Stock.ticker == item.ticker).first()
        
        if not stock:
            continue
            
        # Get the latest price
        latest_price = (
            db.query(StockPrice)
            .filter(StockPrice.stock_id == stock.id)
            .order_by(StockPrice.date.desc())
            .first()
        )
        
        if not latest_price:
            continue
        
        current_price = latest_price.close
        triggered_alerts = []
        
        # Check high price alert
        if (item.alert_price_high is not None and 
            current_price >= item.alert_price_high):
            triggered_alerts.append({
                "type": "high",
                "target_price": item.alert_price_high,
                "current_price": current_price,
                "message": f"{item.ticker} reached high price alert of {item.alert_price_high}"
            })
            
        # Check low price alert
        if (item.alert_price_low is not None and 
            current_price <= item.alert_price_low):
            triggered_alerts.append({
                "type": "low",
                "target_price": item.alert_price_low,
                "current_price": current_price,
                "message": f"{item.ticker} reached low price alert of {item.alert_price_low}"
            })
        
        if triggered_alerts:
            alerts.append({
                "ticker": item.ticker,
                "name": stock.name,
                "current_price": current_price,
                "alerts": triggered_alerts
            })
    
    return {
        "alerts": alerts,
        "count": len(alerts)
    }
