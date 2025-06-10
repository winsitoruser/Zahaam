"""
API routes for stock search functionality
"""
from fastapi import APIRouter, Depends, HTTPException, Query
from sqlalchemy.orm import Session
from sqlalchemy import or_, func
from typing import List, Dict, Any, Optional

from app.core.database import get_db
from app.models.stocks import Stock
from app.core.security import get_current_active_user

router = APIRouter(tags=["stock_search"])

@router.get("/search")
async def search_stocks(
    query: str = Query(..., description="Search query (ticker or name)"),
    limit: int = Query(20, description="Maximum number of results to return"),
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Search for stocks by ticker or name
    
    Parameters:
    - query: Search query (ticker or name)
    - limit: Maximum number of results to return (default: 20)
    """
    # Clean and prepare query
    search_query = query.strip().upper()
    
    # If query is empty, return top stocks
    if not search_query:
        stocks = db.query(Stock).filter(Stock.is_active == True).limit(limit).all()
        return {
            "results": [
                {"ticker": stock.ticker, "name": stock.name, "sector": stock.sector}
                for stock in stocks
            ]
        }
    
    # Search by ticker or name
    stocks = db.query(Stock).filter(
        Stock.is_active == True,
        or_(
            func.upper(Stock.ticker).contains(search_query),
            func.upper(Stock.name).contains(search_query)
        )
    ).limit(limit).all()
    
    return {
        "results": [
            {"ticker": stock.ticker, "name": stock.name, "sector": stock.sector}
            for stock in stocks
        ]
    }

@router.get("/top-traded")
async def get_top_traded_stocks(
    limit: int = Query(10, description="Number of stocks to return"),
    db: Session = Depends(get_db)
):
    """
    Get top traded stocks based on recent volume
    
    Parameters:
    - limit: Number of stocks to return (default: 10)
    """
    from sqlalchemy import desc
    from app.models.stocks import StockPrice
    from datetime import datetime, timedelta
    
    # Get recent date range
    end_date = datetime.now()
    start_date = end_date - timedelta(days=7)
    
    # Get stocks with highest average volume
    result = db.execute("""
        SELECT s.ticker, s.name, s.sector, AVG(sp.volume) as avg_volume
        FROM stock_prices sp
        JOIN stocks s ON sp.stock_id = s.id
        WHERE sp.date >= :start_date
        AND sp.date <= :end_date
        AND s.is_active = true
        GROUP BY s.id, s.ticker, s.name, s.sector
        ORDER BY avg_volume DESC
        LIMIT :limit
    """, {
        "start_date": start_date,
        "end_date": end_date,
        "limit": limit
    })
    
    stocks = []
    for row in result:
        stocks.append({
            "ticker": row[0],
            "name": row[1],
            "sector": row[2],
            "avg_volume": int(row[3]) if row[3] else 0
        })
    
    return {"results": stocks}

@router.get("/by-sector")
async def get_stocks_by_sector(
    sector: Optional[str] = None,
    db: Session = Depends(get_db)
):
    """
    Get stocks filtered by sector
    
    Parameters:
    - sector: Sector name (optional, if not provided returns all sectors)
    """
    if sector:
        # Get stocks from a specific sector
        stocks = db.query(Stock).filter(
            Stock.sector == sector,
            Stock.is_active == True
        ).all()
        
        return {
            "sector": sector,
            "stocks": [
                {"ticker": stock.ticker, "name": stock.name}
                for stock in stocks
            ]
        }
    else:
        # Get all sectors with their stocks
        stocks = db.query(Stock).filter(Stock.is_active == True).all()
        sectors = {}
        
        for stock in stocks:
            if stock.sector not in sectors:
                sectors[stock.sector] = []
            
            sectors[stock.sector].append({
                "ticker": stock.ticker,
                "name": stock.name
            })
        
        return {"sectors": sectors}
