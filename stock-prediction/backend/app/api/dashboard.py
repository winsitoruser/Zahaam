"""
API routes for dashboard data
"""
from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from typing import Dict, List, Optional, Any
from datetime import datetime, timedelta

from app.core.database import get_db
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.models.watchlist import WatchlistItem, Watchlist
from app.models.user_strategies import Strategy
from app.models.news_sentiment import MarketSentimentSummary
from app.services.news_sentiment_db import NewsSentimentDBService

router = APIRouter(prefix="/dashboard", tags=["dashboard"])

@router.get("/summary")
async def get_dashboard_summary(
    user_id: str,
    db: Session = Depends(get_db)
):
    """
    Get dashboard summary data including:
    - Account summary
    - Watchlist summary
    - Today's stock signals
    - Market events (dividends, RUPS)
    - Market sentiment
    """
    # Get user's watchlist stats
    watchlists = db.query(Watchlist).filter(Watchlist.user_id == user_id).all()
    watchlist_stats = {
        "total_watchlists": len(watchlists),
        "total_stocks": 0,
        "recent_items": []
    }
    
    recent_items = []
    for watchlist in watchlists:
        items = db.query(WatchlistItem).filter(WatchlistItem.watchlist_id == watchlist.id).all()
        watchlist_stats["total_stocks"] += len(items)
        
        # Get 5 most recently added stocks
        for item in items[:5]:
            stock = db.query(Stock).filter(Stock.id == item.stock_id).first()
            if stock:
                recent_items.append({
                    "ticker": stock.ticker,
                    "name": stock.name,
                    "added_date": item.created_at.strftime("%Y-%m-%d") if item.created_at else "N/A"
                })
    
    # Sort by date (newest first) and take top 5
    recent_items.sort(key=lambda x: x["added_date"], reverse=True)
    watchlist_stats["recent_items"] = recent_items[:5]
    
    # Get today's stock signals
    today = datetime.now().date()
    yesterday = today - timedelta(days=1)
    signals = db.query(StockIndicator).filter(
        StockIndicator.date >= yesterday,
        StockIndicator.signal.in_(["BUY", "SELL"])
    ).all()
    
    signal_summary = {
        "buy_signals": 0,
        "sell_signals": 0,
        "neutral_signals": 0,
        "top_signals": []
    }
    
    stock_ids = []
    for signal in signals:
        if signal.signal == "BUY":
            signal_summary["buy_signals"] += 1
        elif signal.signal == "SELL":
            signal_summary["sell_signals"] += 1
        else:
            signal_summary["neutral_signals"] += 1
        
        stock_ids.append(signal.stock_id)
    
    # Get stock information for the signals
    if stock_ids:
        stocks = db.query(Stock).filter(Stock.id.in_(stock_ids)).all()
        stock_dict = {stock.id: stock for stock in stocks}
        
        # Get top 5 signals by strength
        top_signals = sorted(signals, key=lambda x: x.signal_strength or 0, reverse=True)[:5]
        
        for signal in top_signals:
            stock = stock_dict.get(signal.stock_id)
            if stock:
                signal_summary["top_signals"].append({
                    "ticker": stock.ticker,
                    "name": stock.name,
                    "signal": signal.signal,
                    "strength": signal.signal_strength,
                    "date": signal.date.strftime("%Y-%m-%d")
                })
    
    # Get upcoming market events (dividends, RUPS)
    # This would come from a calendar table, which we'll simulate for now
    # In a real implementation, this would query a CalendarEvent table
    today = datetime.now().date()
    next_week = today + timedelta(days=7)
    
    # Sample events - in a real implementation these would come from the database
    market_events = [
        {
            "ticker": "BBCA",
            "name": "Bank Central Asia Tbk",
            "event_type": "DIVIDEND",
            "date": (today + timedelta(days=2)).strftime("%Y-%m-%d"),
            "details": "Cash dividend Rp 58 per share"
        },
        {
            "ticker": "TLKM",
            "name": "Telekomunikasi Indonesia Tbk",
            "event_type": "RUPS",
            "date": (today + timedelta(days=5)).strftime("%Y-%m-%d"),
            "details": "Annual General Meeting"
        }
    ]
    
    # Get market sentiment
    sentiment_service = NewsSentimentDBService()
    market_sentiment = sentiment_service.get_latest_market_sentiment(db)
    
    if not market_sentiment:
        market_sentiment_data = {
            "score": 0,
            "label": "Neutral",
            "article_count": 0,
            "date": today.strftime("%Y-%m-%d")
        }
    else:
        market_sentiment_data = {
            "score": market_sentiment.overall_sentiment,
            "label": market_sentiment.sentiment_label,
            "article_count": market_sentiment.article_count,
            "date": market_sentiment.date.strftime("%Y-%m-%d")
        }
    
    return {
        "account_summary": {
            "last_login": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            "membership_status": "Active",
            "days_remaining": 30  # Would come from actual subscription data
        },
        "watchlist_summary": watchlist_stats,
        "signal_summary": signal_summary,
        "market_events": market_events,
        "market_sentiment": market_sentiment_data
    }

@router.get("/calendar")
async def get_market_calendar(
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
    db: Session = Depends(get_db)
):
    """
    Get market calendar events (dividends, RUPS, etc.)
    
    Parameters:
    - start_date: Start date for events (format: YYYY-MM-DD)
    - end_date: End date for events (format: YYYY-MM-DD)
    """
    # Parse dates or use defaults
    try:
        start = datetime.strptime(start_date, "%Y-%m-%d").date() if start_date else datetime.now().date()
        end = datetime.strptime(end_date, "%Y-%m-%d").date() if end_date else start + timedelta(days=30)
    except ValueError:
        raise HTTPException(status_code=400, detail="Invalid date format. Use YYYY-MM-DD")
    
    # In a real implementation, query a CalendarEvent table
    # For now, return sample data
    events = [
        {
            "ticker": "BBCA",
            "name": "Bank Central Asia Tbk",
            "event_type": "DIVIDEND",
            "date": (start + timedelta(days=2)).strftime("%Y-%m-%d"),
            "details": "Cash dividend Rp 58 per share"
        },
        {
            "ticker": "TLKM",
            "name": "Telekomunikasi Indonesia Tbk",
            "event_type": "RUPS",
            "date": (start + timedelta(days=5)).strftime("%Y-%m-%d"),
            "details": "Annual General Meeting"
        },
        {
            "ticker": "ASII",
            "name": "Astra International Tbk",
            "event_type": "DIVIDEND_EX",
            "date": (start + timedelta(days=10)).strftime("%Y-%m-%d"),
            "details": "Ex-dividend date"
        },
        {
            "ticker": "UNVR",
            "name": "Unilever Indonesia Tbk",
            "event_type": "EARNINGS",
            "date": (start + timedelta(days=15)).strftime("%Y-%m-%d"),
            "details": "Q2 2025 Earnings Report"
        }
    ]
    
    # Filter based on date range
    filtered_events = [
        event for event in events
        if start <= datetime.strptime(event["date"], "%Y-%m-%d").date() <= end
    ]
    
    return {
        "start_date": start.strftime("%Y-%m-%d"),
        "end_date": end.strftime("%Y-%m-%d"),
        "events": filtered_events
    }

@router.get("/market-overview")
async def get_market_overview(db: Session = Depends(get_db)):
    """
    Get market overview data including:
    - Market indices
    - Top gainers and losers
    - Sector performance
    - Market breadth
    """
    # Get market indices (JCI, LQ45)
    # In a real implementation, these would come from an Indices table
    indices = [
        {
            "code": "JCI",
            "name": "Jakarta Composite Index",
            "last_price": 7865.42,
            "change_percent": 0.75,
            "last_updated": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        },
        {
            "code": "LQ45",
            "name": "LQ45 Index",
            "last_price": 968.23,
            "change_percent": 0.62,
            "last_updated": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        }
    ]
    
    # Get top gainers and losers
    # Query for latest stock prices
    today = datetime.now().date()
    yesterday = today - timedelta(days=1)
    
    stock_prices = db.query(StockPrice, Stock).join(
        Stock, StockPrice.stock_id == Stock.id
    ).filter(
        StockPrice.date >= yesterday
    ).all()
    
    # Calculate price changes
    price_changes = []
    for price, stock in stock_prices:
        # Get previous day price
        prev_price = db.query(StockPrice).filter(
            StockPrice.stock_id == stock.id,
            StockPrice.date < price.date
        ).order_by(StockPrice.date.desc()).first()
        
        if prev_price:
            # Calculate change percentage
            change_percent = ((price.close - prev_price.close) / prev_price.close * 100)
            
            price_changes.append({
                "ticker": stock.ticker,
                "name": stock.name,
                "last_price": price.close,
                "change_percent": round(change_percent, 2),
                "volume": price.volume
            })
    
    # Sort by change percentage
    price_changes.sort(key=lambda x: x["change_percent"], reverse=True)
    
    # Get top 10 gainers and losers
    top_gainers = price_changes[:10]
    top_losers = sorted(price_changes, key=lambda x: x["change_percent"])[:10]
    
    # Get sector performance
    # Group stocks by sector and calculate average performance
    sectors = {}
    for price_change in price_changes:
        stock = db.query(Stock).filter(Stock.ticker == price_change["ticker"]).first()
        if stock and stock.sector:
            if stock.sector not in sectors:
                sectors[stock.sector] = {
                    "name": stock.sector,
                    "change_sum": 0,
                    "count": 0
                }
            
            sectors[stock.sector]["change_sum"] += price_change["change_percent"]
            sectors[stock.sector]["count"] += 1
    
    # Calculate average change by sector
    sector_performance = []
    for sector, data in sectors.items():
        avg_change = data["change_sum"] / data["count"] if data["count"] > 0 else 0
        sector_performance.append({
            "name": sector,
            "change_percent": round(avg_change, 2),
            "stock_count": data["count"]
        })
    
    # Sort by performance
    sector_performance.sort(key=lambda x: x["change_percent"], reverse=True)
    
    # Calculate market breadth
    advancing = len([p for p in price_changes if p["change_percent"] > 0])
    declining = len([p for p in price_changes if p["change_percent"] < 0])
    unchanged = len([p for p in price_changes if p["change_percent"] == 0])
    
    market_breadth = {
        "advancing": advancing,
        "declining": declining,
        "unchanged": unchanged,
        "total": len(price_changes),
        "advance_decline_ratio": round(advancing / declining if declining > 0 else advancing, 2)
    }
    
    return {
        "indices": indices,
        "top_gainers": top_gainers,
        "top_losers": top_losers,
        "sector_performance": sector_performance,
        "market_breadth": market_breadth,
        "last_updated": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    }
