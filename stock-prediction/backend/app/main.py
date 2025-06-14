from fastapi import FastAPI, HTTPException, Depends, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.staticfiles import StaticFiles
from fastapi.templating import Jinja2Templates
from fastapi.responses import HTMLResponse, JSONResponse
from typing import List, Optional, Dict, Any
import yfinance as yf
import pandas as pd
# Import our numpy compatibility layer first
from app.utils.numpy_compat import npNaN

# Monkey patch numpy for pandas_ta
import numpy
numpy.NaN = npNaN

# Now import pandas_ta
import pandas_ta as ta
import numpy as np
from datetime import datetime, timedelta
import json
import os
import logging

# Import database and models
from app.core.database import engine, Base, SessionLocal, get_db
from app.models.stocks import Stock, StockPrice, StockIndicator
from app.data.indonesian_stocks import INDONESIAN_STOCKS
from app.core.init_db import init_db

# Import API routers
from app.api import stocks, prediction, user_strategies as strategies, watchlist, big_data, notifications, admin, auth, ml_prediction, backtesting, ai_lab, stock_management, market_depth
from app.api.routers import news_sentiment_api

# Import scheduler
from app.core.scheduler import JobScheduler, setup_default_jobs

# Import rate limiter
from app.core.rate_limiter import RateLimitMiddleware

# Import monitoring middleware
from app.utils.monitoring import MonitoringMiddleware

# Import tasks API router
from app.api.tasks_api import router as tasks_router

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Configuration
Settings = importlib.import_module("app.core.config").Settings()

app = FastAPI(
    title="Stock Market Prediction API",
    description="API for stock market prediction and analysis",
    version="0.2.0",
    openapi_url="/api/openapi.json",
    docs_url="/api/docs",
    redoc_url="/api/redoc",
)

# CORS middleware configuration
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Rate limiting middleware - 120 requests per minute per client/endpoint
app.add_middleware(RateLimitMiddleware, window_seconds=60, max_requests=120)

# Metrics monitoring middleware
app.add_middleware(MonitoringMiddleware)

# Initialize database tables and data
@app.on_event("startup")
async def on_startup():
    """Initialize resources on startup"""
    try:
        # Setup database connection
        logger.info("Initializing database connection")
        db = SessionLocal()
        try:
            # Test database connection
            version = db.execute("SELECT version()").scalar()
            logger.info(f"Connected to database: {version}")
        except Exception as e:
            logger.error(f"Error connecting to database: {str(e)}")
        finally:
            db.close()
            
        # Initialize Celery (if running in same process)
        logger.info("Initializing Celery connection")
        try:
            from app.core.celery_app import celery_app
            logger.info(f"Celery initialized with broker: {celery_app.conf.broker_url}")
        except Exception as e:
            logger.error(f"Error initializing Celery: {str(e)}")
            
        # Initialize and start system monitoring
        logger.info("Initializing system monitoring")
        try:
            from app.utils.monitoring import SystemMonitoring
            from app.utils.influxdb_client import InfluxDBMetrics
            
            # Check InfluxDB connection
            influx = InfluxDBMetrics.get_instance()
            if influx.health_check():
                logger.info("InfluxDB connection successful")
                
                # Start system monitoring
                monitor = SystemMonitoring.get_instance()
                monitor.start_monitoring()
                logger.info("System monitoring started")
            else:
                logger.warning("InfluxDB health check failed - monitoring will be limited")
        except Exception as e:
            logger.error(f"Error initializing monitoring: {str(e)}")
            
        # Note: The old scheduler is being replaced by Celery Beat
        # For backward compatibility during transition, we'll keep the scheduler code
        # but disable automatic startup
        logger.info("Setting up background job scheduler (legacy)")
        from app.core.scheduler import JobScheduler, setup_default_jobs
        scheduler = JobScheduler.get_instance()
        # scheduler.start()  # Disabled: now using Celery Beat instead
        # setup_default_jobs()  # Disabled: now defined in celery_app.py
        logger.info("Legacy scheduler initialized but not started (using Celery instead)")
        
    except Exception as e:
        logger.error(f"Error during application startup: {e}")
        # Continue running anyway to allow troubleshooting

# For template rendering
try:
    # Create directory if it doesn't exist
    os.makedirs("templates", exist_ok=True)
    os.makedirs("static", exist_ok=True)
    # Mount static files
    app.mount("/static", StaticFiles(directory="static"), name="static")
    # Template configuration
    templates = Jinja2Templates(directory="templates")
    
    # Create a simple index.html if it doesn't exist
    if not os.path.exists("templates/index.html"):
        with open("templates/index.html", "w") as f:
            f.write("""
<!DOCTYPE html>
<html>
<head>
    <title>Stock Market Prediction API</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1>Indonesian Stock Market Prediction API</h1>
        <p class="lead">API endpoints for accessing stock market data</p>
        
        <div class="card mt-4">
            <div class="card-header">API Documentation</div>
            <div class="card-body">
                <h5>Available Endpoints:</h5>
                <ul>
                    <li><a href="/api/stocks">/api/stocks</a> - Get list of available stocks</li>
                    <li><a href="/api/stocks/details">/api/stocks/details</a> - Get detailed list of stocks</li>
                    <li><a href="/api/stocks/sectors">/api/stocks/sectors</a> - Get stocks grouped by sectors</li>
                    <li>/api/stock/{ticker} - Get historical data for a ticker</li>
                    <li>/api/technical/signal/{ticker} - Get technical signals for a ticker</li>
                    <li>/api/stocks/db - Get stocks from database</li>
                    <li>/api/stock/{ticker}/db - Get stock data from database</li>
                </ul>
                <h5 class="mt-4">Database Operations:</h5>
                <ul>
                    <li>/api/stock/refresh/{ticker} - Refresh data for a specific stock</li>
                    <li>/api/stocks/refresh/all - Refresh data for all stocks</li>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
            """)
            
except Exception as e:
    logger.error(f"Error setting up static files and templates: {str(e)}")

# Register API routers
app.include_router(stock_management.router, prefix="/api/admin/stocks", tags=["Admin", "Stock"])
app.include_router(stocks.router, prefix="/api/stocks", tags=["stocks"])
app.include_router(prediction.router, prefix="/api/prediction", tags=["prediction"])
app.include_router(strategies.router, prefix="/api/strategies", tags=["strategies"])
app.include_router(watchlist.router, prefix="/api/watchlist", tags=["watchlist"])
app.include_router(big_data.router, prefix="/api/bigdata", tags=["big-data"])
app.include_router(notifications.router, prefix="/api/notifications", tags=["notifications"])
app.include_router(admin.router, prefix="/api/admin", tags=["admin"])
app.include_router(auth.router, prefix="/api/auth", tags=["auth"])
app.include_router(ml_prediction.router, prefix="/api/ml", tags=["ml-prediction"])
app.include_router(news_sentiment_api.router)
app.include_router(backtesting.router, prefix="/api/backtesting", tags=["backtesting"])
app.include_router(ai_lab.router, prefix="/api/ai-lab", tags=["ai-lab"])

# Import the tasks API router
from app.api.tasks_api import router as tasks_router
app.include_router(tasks_router, prefix="/api", tags=["tasks"])

# Import and include market depth router
from app.api.market_depth import router as market_depth_router
app.include_router(market_depth_router, prefix="/api", tags=["market_depth"])

# List of Indonesian stock tickers (from data/indonesian_stocks.py)

@app.get("/", response_class=HTMLResponse)
async def read_root(request: Request):
    """Serve the main HTML page"""
    try:
        if os.path.exists("frontend/index.html"):
            with open("frontend/index.html", "r") as f:
                return f.read()
        else:
            return templates.TemplateResponse("index.html", {"request": request})
    except Exception as e:
        logger.error(f"Error reading root page: {str(e)}")
        return HTMLResponse(content="<html><body><h1>Stock Prediction API</h1><p>API is running. Visit /api/stocks for data.</p></body></html>")

@app.get("/api/stocks", response_class=JSONResponse)
async def get_stocks():
    """Get list of available Indonesian stocks from static list"""
    return {"stocks": [stock["ticker"] for stock in INDONESIAN_STOCKS]}


@app.get("/api/stocks/details", response_class=JSONResponse)
async def get_stocks_details():
    """Get detailed list of available Indonesian stocks including name and sector"""
    return {"stocks": INDONESIAN_STOCKS}


@app.get("/api/stocks/sectors", response_class=JSONResponse)
async def get_stocks_by_sectors():
    """Get Indonesian stocks grouped by sectors"""
    sectors = {}
    for stock in INDONESIAN_STOCKS:
        sector = stock["sector"]
        if sector not in sectors:
            sectors[sector] = []
        sectors[sector].append(stock)
    
    return {"sectors": sectors}

@app.get("/api/stock/{ticker}")
async def get_stock_data(ticker: str, period: str = "1y", interval: str = "1d"):
    """
    Get stock data for a given ticker
    
    Parameters:
    - ticker: Stock ticker symbol (e.g., 'BBCA.JK')
    - period: Data period ('1d', '5d', '1mo', '3mo', '6mo', '1y', '2y', '5y', '10y', 'ytd', 'max')
    - interval: Data interval ('1d', '5d', '1wk', '1mo', '3mo')
    """
    try:
        # Get data from yfinance
        stock = yf.Ticker(ticker)
        df = stock.history(period=period, interval=interval)
        
        # Reset index to include Date as a column
        df = df.reset_index()
        
        # Convert datetime to string for JSON serialization
        df['Date'] = df['Date'].dt.strftime('%Y-%m-%d %H:%M:%S')
        
        # Calculate technical indicators
        df['SMA_20'] = ta.sma(df['Close'], length=20)
        df['SMA_50'] = ta.sma(df['Close'], length=50)
        df['RSI'] = ta.rsi(df['Close'])
        
        # Replace NaN values with None for proper JSON serialization
        df = df.replace({np.nan: None})
        
        # Get company info
        info = stock.info
        company_info = {
            'name': info.get('longName', ticker),
            'sector': info.get('sector', 'N/A'),
            'industry': info.get('industry', 'N/A'),
            'marketCap': info.get('marketCap', 0),
            'currentPrice': info.get('currentPrice', 0),
            'previousClose': info.get('previousClose', 0),
            'open': info.get('open', 0),
            'dayLow': info.get('dayLow', 0),
            'dayHigh': info.get('dayHigh', 0),
            'volume': info.get('volume', 0),
            'fiftyTwoWeekLow': info.get('fiftyTwoWeekLow', 0),
            'fiftyTwoWeekHigh': info.get('fiftyTwoWeekHigh', 0),
        }
        
        # Replace any NaN values in company info with None
        for key, value in company_info.items():
            if isinstance(value, float) and np.isnan(value):
                company_info[key] = None
        
        # Prepare response
        response = {
            'ticker': ticker,
            'data': df.to_dict(orient='records'),
            'indicators': {
                'sma_20': df['SMA_20'].iloc[-1] if not df['SMA_20'].isnull().all() else None,
                'sma_50': df['SMA_50'].iloc[-1] if not df['SMA_50'].isnull().all() else None,
                'rsi': df['RSI'].iloc[-1] if not df['RSI'].isnull().all() else None,
            },
            'company': company_info,
            'last_updated': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        }
        
        return response
        
    except Exception as e:
        raise HTTPException(status_code=400, detail=str(e))

@app.get("/api/technical/signal/{ticker}")
async def get_technical_signal(ticker: str, period: str = "1y"):
    """
    Generate technical trading signals for a given stock
    """
    try:
        # Get stock data
        stock = yf.Ticker(ticker)
        df = stock.history(period=period)
        
        # Calculate technical indicators
        # RSI
        rsi = ta.rsi(df['Close'], length=14)
        
        # MACD
        macd = ta.macd(df['Close'])
        
        # Bollinger Bands
        bollinger = ta.bbands(df['Close'], length=20, std=2)
        
        # Moving Averages
        sma_20 = ta.sma(df['Close'], length=20)
        sma_50 = ta.sma(df['Close'], length=50)
        
        # Generate signals
        signals = []
        
        # RSI Signal
        if rsi.iloc[-1] < 30:
            signals.append({"indicator": "RSI", "signal": "Oversold", "value": round(rsi.iloc[-1], 2)})
        elif rsi.iloc[-1] > 70:
            signals.append({"indicator": "RSI", "signal": "Overbought", "value": round(rsi.iloc[-1], 2)})
        
        # MACD Signal
        if macd['MACD_12_26_9'].iloc[-1] > macd['MACDs_12_26_9'].iloc[-1] and macd['MACD_12_26_9'].iloc[-2] <= macd['MACDs_12_26_9'].iloc[-2]:
            signals.append({"indicator": "MACD", "signal": "Bullish Crossover", "value": "Bullish"})
        elif macd['MACD_12_26_9'].iloc[-1] < macd['MACDs_12_26_9'].iloc[-1] and macd['MACD_12_26_9'].iloc[-2] >= macd['MACDs_12_26_9'].iloc[-2]:
            signals.append({"indicator": "MACD", "signal": "Bearish Crossover", "value": "Bearish"})
        
        # Moving Averages Crossover
        if sma_20.iloc[-1] > sma_50.iloc[-1] and sma_20.iloc[-2] <= sma_50.iloc[-2]:
            signals.append({"indicator": "Moving Averages", "signal": "Golden Cross", "value": "Bullish"})
        elif sma_20.iloc[-1] < sma_50.iloc[-1] and sma_20.iloc[-2] >= sma_50.iloc[-2]:
            signals.append({"indicator": "Moving Averages", "signal": "Death Cross", "value": "Bearish"})
        
        # Bollinger Bands Signal
        if df['Close'].iloc[-1] < bollinger['BBL_20_2.0'].iloc[-1]:
            signals.append({"indicator": "Bollinger Bands", "signal": "Below Lower Band", "value": "Oversold"})
        elif df['Close'].iloc[-1] > bollinger['BBU_20_2.0'].iloc[-1]:
            signals.append({"indicator": "Bollinger Bands", "signal": "Above Upper Band", "value": "Overbought"})
        
        return {
            "ticker": ticker,
            "signals": signals,
            "indicators": {
                "rsi": round(rsi.iloc[-1], 2) if not rsi.empty else None,
                "macd": {
                    "macd_line": round(macd['MACD_12_26_9'].iloc[-1], 2) if not macd.empty else None,
                    "signal_line": round(macd['MACDs_12_26_9'].iloc[-1], 2) if not macd.empty else None,
                    "histogram": round(macd['MACDh_12_26_9'].iloc[-1], 2) if not macd.empty else None
                },
                "bollinger_bands": {
                    "upper": round(bollinger['BBU_20_2.0'].iloc[-1], 2) if not bollinger.empty else None,
                    "middle": round(bollinger['BBM_20_2.0'].iloc[-1], 2) if not bollinger.empty else None,
                    "lower": round(bollinger['BBL_20_2.0'].iloc[-1], 2) if not bollinger.empty else None
                },
                "moving_averages": {
                    "sma_20": round(sma_20.iloc[-1], 2) if not sma_20.empty else None,
                    "sma_50": round(sma_50.iloc[-1], 2) if not sma_50.empty else None
                }
            },
            "last_updated": datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        }
        
    except Exception as e:
        raise HTTPException(status_code=400, detail=str(e))

@app.get("/health", response_class=JSONResponse)
async def health_check():
    """Health check endpoint"""
    return {"status": "ok", "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
