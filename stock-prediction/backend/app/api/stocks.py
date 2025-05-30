"""
API routes for stock data
"""
from fastapi import APIRouter, HTTPException, Depends, BackgroundTasks
from sqlalchemy.orm import Session
from typing import List, Optional, Dict, Any
import pandas as pd
from datetime import datetime, timedelta

from app.core.database import get_db
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.core.stock_fetcher import fetch_and_save_single_stock, fetch_and_save_all_stocks, get_latest_stock_data

router = APIRouter(tags=["stocks"])

@router.get("/stocks/db")
async def get_stocks_from_db(db: Session = Depends(get_db)):
    """Get list of available Indonesian stocks from database"""
    stocks = db.query(Stock).filter(Stock.is_active == True).all()
    return {
        "stocks": [
            {"ticker": stock.ticker, "name": stock.name, "sector": stock.sector}
            for stock in stocks
        ]
    }

@router.get("/stocks/sectors/db")
async def get_stocks_by_sectors_from_db(db: Session = Depends(get_db)):
    """Get Indonesian stocks grouped by sectors from database"""
    stocks = db.query(Stock).filter(Stock.is_active == True).all()
    sectors = {}
    for stock in stocks:
        sector = stock.sector
        if sector not in sectors:
            sectors[sector] = []
        sectors[sector].append({
            "ticker": stock.ticker,
            "name": stock.name
        })
    
    return {"sectors": sectors}


@router.get("/stocks/latest-prices")
async def get_latest_stock_prices(db: Session = Depends(get_db)):
    """Get latest price data for all stocks in the database
    
    Returns a dictionary with ticker symbols as keys and price data as values
    """
    try:
        # Get all active stocks
        stocks = db.query(Stock).filter(Stock.is_active == True).all()
        result = {}
        
        # Get cutoff date (1 day ago)
        cutoff_date = datetime.now() - timedelta(days=1)
        
        for stock in stocks:
            ticker = stock.ticker.replace('.JK', '') if '.JK' in stock.ticker else stock.ticker
            
            # Get latest price data from the database
            latest_price = db.query(StockPrice).filter(
                StockPrice.stock_id == stock.id,
                StockPrice.date >= cutoff_date
            ).order_by(StockPrice.date.desc()).first()
            
            # If we have data, add it to the result
            if latest_price:
                # Get previous price for calculating change
                prev_price = db.query(StockPrice).filter(
                    StockPrice.stock_id == stock.id,
                    StockPrice.date < latest_price.date
                ).order_by(StockPrice.date.desc()).first()
                
                # Calculate change percentage
                prev_close = prev_price.close if prev_price else latest_price.close
                change_percent = ((latest_price.close - prev_close) / prev_close * 100) if prev_close > 0 else 0
                
                # Estimate market cap (price * outstanding shares)
                # Using default value if shares data not available
                shares = stock.outstanding_shares if hasattr(stock, 'outstanding_shares') and stock.outstanding_shares else 10000000000
                market_cap = latest_price.close * shares
                
                # Estimate P/E ratio (price / earnings per share)
                # Using default value if earnings data not available
                eps = stock.earnings_per_share if hasattr(stock, 'earnings_per_share') and stock.earnings_per_share else latest_price.close / 15
                pe_ratio = latest_price.close / eps if eps > 0 else 15.0
                
                result[ticker] = {
                    "lastPrice": latest_price.close,
                    "change": round(change_percent, 2),
                    "volume": latest_price.volume,
                    "open": latest_price.open,
                    "high": latest_price.high,
                    "low": latest_price.low,
                    "date": latest_price.date.isoformat(),
                    "marketCap": market_cap,
                    "peRatio": round(pe_ratio, 2)
                }
        
        return result
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error fetching latest stock prices: {str(e)}")

@router.get("/stock/{ticker}/db")
async def get_stock_data_from_db(
    ticker: str, 
    days: int = 365, 
    auto_refresh: bool = True,
    db: Session = Depends(get_db)
):
    """
    Get stock data for a given ticker from the database
    
    Following the data flow pattern:
    1. API → DB → Frontend
    2. Always returns data from DB (latest version available)
    3. If auto_refresh=True and data is outdated, triggers background refresh for future requests
    
    Parameters:
    - ticker: Stock ticker symbol (e.g., 'BBCA.JK')
    - days: Number of days of historical data to return (default: 365)
    - auto_refresh: Whether to automatically trigger refresh for outdated data (default: True)
    """
    # Find the stock in the database
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
        
    # Check if data is outdated and trigger background refresh if needed
    if auto_refresh:
        # Use get_latest_stock_data with fetch_if_outdated=True to potentially trigger a refresh
        await get_latest_stock_data(db, ticker, fetch_if_outdated=True)
    
    # Get the start date for the query
    end_date = datetime.now()
    start_date = end_date - timedelta(days=days)
    
    # Query stock prices
    prices = (
        db.query(StockPrice)
        .filter(StockPrice.stock_id == stock.id)
        .filter(StockPrice.date >= start_date)
        .filter(StockPrice.date <= end_date)
        .order_by(StockPrice.date)
        .all()
    )
    
    # Query indicators
    indicators = (
        db.query(StockIndicator)
        .filter(StockIndicator.stock_id == stock.id)
        .filter(StockIndicator.date >= start_date)
        .filter(StockIndicator.date <= end_date)
        .order_by(StockIndicator.date)
        .all()
    )
    
    # Create a map of date to indicator for easy lookup
    indicator_map = {indicator.date.strftime('%Y-%m-%d'): indicator for indicator in indicators}
    
    # Build price data with indicators
    price_data = []
    for price in prices:
        date_str = price.date.strftime('%Y-%m-%d')
        indicator = indicator_map.get(date_str)
        
        price_dict = {
            "Date": date_str,
            "Open": price.open,
            "High": price.high,
            "Low": price.low,
            "Close": price.close,
            "Volume": price.volume,
        }
        
        # Add indicators if available
        if indicator:
            price_dict.update({
                "sma_20": indicator.sma_20,
                "sma_50": indicator.sma_50,
                "sma_200": indicator.sma_200,
                "ema_12": indicator.ema_12,
                "ema_26": indicator.ema_26,
                "rsi_14": indicator.rsi_14,
                "macd": indicator.macd,
                "macd_signal": indicator.macd_signal,
                "macd_histogram": indicator.macd_histogram,
                "bb_upper": indicator.bb_upper,
                "bb_middle": indicator.bb_middle,
                "bb_lower": indicator.bb_lower,
                "signal": indicator.signal,
                "signal_strength": indicator.signal_strength,
                "notes": indicator.notes,
            })
        
        price_data.append(price_dict)
    
    # Get latest price for current stats
    latest_price = prices[-1] if prices else None
    latest_indicator = indicators[-1] if indicators else None
    
    # Prepare company info
    company_info = {
        "name": stock.name,
        "sector": stock.sector,
        "currentPrice": latest_price.close if latest_price else None,
        "previousClose": prices[-2].close if len(prices) > 1 else None,
        "open": latest_price.open if latest_price else None,
        "dayLow": latest_price.low if latest_price else None,
        "dayHigh": latest_price.high if latest_price else None,
        "volume": latest_price.volume if latest_price else None,
    }
    
    # Prepare indicators summary
    indicators_summary = {}
    if latest_indicator:
        indicators_summary = {
            "sma_20": latest_indicator.sma_20,
            "sma_50": latest_indicator.sma_50,
            "rsi_14": latest_indicator.rsi_14,
            "signal": latest_indicator.signal,
            "notes": latest_indicator.notes,
        }
    
    return {
        "ticker": ticker,
        "data": price_data,
        "indicators": indicators_summary,
        "company": company_info,
        "last_updated": stock.last_updated.strftime("%Y-%m-%d %H:%M:%S")
    }

@router.post("/stock/refresh/{ticker}")
async def refresh_stock_data(
    ticker: str,
    background_tasks: BackgroundTasks,
    db: Session = Depends(get_db)
):
    """
    Refresh stock data for a specific ticker
    
    Following the data flow pattern:
    1. API → DB → Frontend
    2. Returns current DB data immediately
    3. Triggers background refresh for future requests
    """
    # Check if ticker exists
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
    
    # Add the fetch job to background tasks
    background_tasks.add_task(fetch_and_save_single_stock, db, ticker)
    
    # Get current data from DB to return immediately
    latest_price, latest_indicator = await get_latest_stock_data(db, ticker, fetch_if_outdated=False)
    
    return {
        "message": f"Stock data refresh started for {ticker}",
        "ticker": ticker,
        "currentPrice": latest_price.close if latest_price else None,
        "lastUpdated": stock.last_updated.strftime('%Y-%m-%d %H:%M:%S') if stock.last_updated else None
    }

@router.post("/stocks/refresh/all")
async def refresh_all_stocks(
    background_tasks: BackgroundTasks,
    db: Session = Depends(get_db)
):
    """
    Refresh data for all active stocks
    
    Following the data flow pattern:
    1. API → DB → Frontend
    2. Returns current stock list from DB immediately
    3. Triggers background refresh for future requests
    
    Warning: The background refresh can take a long time
    """
    # Get list of active stock tickers
    stocks = db.query(Stock).filter(Stock.is_active == True).all()
    ticker_list = [stock.ticker for stock in stocks]
    
    # Add the fetch job to background tasks
    background_tasks.add_task(fetch_and_save_all_stocks, db, ticker_list)
    
    return {
        "message": f"Started refreshing data for {len(ticker_list)} stocks",
        "count": len(ticker_list),
        "stocks": [
            {
                "ticker": stock.ticker,
                "name": stock.name,
                "lastUpdated": stock.last_updated.strftime('%Y-%m-%d %H:%M:%S') if stock.last_updated else None
            } for stock in stocks[:10]  # Return first 10 as preview
        ],
        "totalStocks": len(ticker_list)
    }

@router.get("/stock/analysis/{ticker}")
async def get_stock_analysis(
    ticker: str,
    days: int = 30,
    db: Session = Depends(get_db)
):
    """
    Get technical analysis for a given stock
    
    Parameters:
    - ticker: Stock ticker symbol (e.g., 'BBCA.JK')
    - days: Number of days for the analysis (default: 30)
    """
    # Find the stock in the database
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
    
    # Get the start date for the query
    end_date = datetime.now()
    start_date = end_date - timedelta(days=days)
    
    # Get latest indicators
    latest_indicator = (
        db.query(StockIndicator)
        .filter(StockIndicator.stock_id == stock.id)
        .order_by(StockIndicator.date.desc())
        .first()
    )
    
    if not latest_indicator:
        raise HTTPException(status_code=404, detail="No technical indicators available for this stock")
    
    # Get buy/sell signals
    signals = (
        db.query(StockIndicator)
        .filter(StockIndicator.stock_id == stock.id)
        .filter(StockIndicator.date >= start_date)
        .filter(StockIndicator.date <= end_date)
        .filter(StockIndicator.signal.in_(["BUY", "SELL"]))
        .order_by(StockIndicator.date.desc())
        .all()
    )
    
    # Get corresponding prices
    signal_dates = [signal.date for signal in signals]
    prices = (
        db.query(StockPrice)
        .filter(StockPrice.stock_id == stock.id)
        .filter(StockPrice.date.in_(signal_dates))
        .all()
    )
    
    # Create a map of date to price for easy lookup
    price_map = {price.date: price for price in prices}
    
    # Create list of signal data
    signal_data = []
    for indicator in signals:
        price = price_map.get(indicator.date)
        close_price = price.close if price else None
        
        signal_data.append({
            "date": indicator.date.strftime("%Y-%m-%d"),
            "signal": indicator.signal,
            "strength": indicator.signal_strength,
            "price": close_price,
            "notes": indicator.notes,
        })
    
    # Build analysis response
    return {
        "ticker": ticker,
        "name": stock.name,
        "sector": stock.sector,
        "indicators": {
            "sma_20": latest_indicator.sma_20,
            "sma_50": latest_indicator.sma_50,
            "sma_200": latest_indicator.sma_200,
            "rsi_14": latest_indicator.rsi_14,
            "macd": {
                "macd_line": latest_indicator.macd,
                "signal_line": latest_indicator.macd_signal,
                "histogram": latest_indicator.macd_histogram
            },
            "bollinger_bands": {
                "upper": latest_indicator.bb_upper,
                "middle": latest_indicator.bb_middle,
                "lower": latest_indicator.bb_lower
            }
        },
        "signals": signal_data,
        "last_signal": {
            "type": latest_indicator.signal,
            "strength": latest_indicator.signal_strength,
            "notes": latest_indicator.notes
        },
        "last_updated": stock.last_updated.strftime("%Y-%m-%d %H:%M:%S")
    }
