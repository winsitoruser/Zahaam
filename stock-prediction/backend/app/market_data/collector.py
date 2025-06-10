"""
Market data collector for Zahaam stock prediction system.

This module collects market data, stock prices, and indicators from
financial data providers like Yahoo Finance, Alpha Vantage, etc.
"""

import os
import logging
import time
from datetime import datetime, timedelta
import json
import requests
import yfinance as yf
import pandas as pd
from typing import Dict, List, Optional, Any, Union
from influxdb_client import InfluxDBClient, Point
from influxdb_client.client.write_api import SYNCHRONOUS

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class MarketDataCollector:
    """Collector for stock market data from various sources"""
    
    def __init__(self):
        """Initialize the market data collector with API keys and configuration"""
        self.yahoo_api_key = os.environ.get("YAHOO_FINANCE_API_KEY")
        
        self.influxdb_url = os.environ.get("INFLUXDB_URL", "http://localhost:8086")
        self.influxdb_token = os.environ.get("INFLUXDB_TOKEN", "zahaam-token")
        self.influxdb_org = os.environ.get("INFLUXDB_ORG", "zahaam")
        self.influxdb_bucket = os.environ.get("INFLUXDB_BUCKET", "stock_metrics")
        
        # Initialize InfluxDB client for metrics
        self.influxdb_client = InfluxDBClient(
            url=self.influxdb_url,
            token=self.influxdb_token,
            org=self.influxdb_org
        )
        self.write_api = self.influxdb_client.write_api(write_options=SYNCHRONOUS)
        
        # Determine which tickers to track
        self.major_indices = ["^GSPC", "^DJI", "^IXIC", "^RUT"]  # S&P 500, Dow Jones, Nasdaq, Russell 2000
        self.sector_etfs = ["XLK", "XLF", "XLV", "XLE", "XLY", "XLI", "XLP", "XLRE", "XLU", "XLB", "XLC"]
        
        # Top stocks by market cap (this would typically be dynamically generated)
        self.top_stocks = [
            "AAPL", "MSFT", "GOOGL", "AMZN", "META", 
            "TSLA", "NVDA", "BRK-B", "JPM", "V", 
            "PG", "UNH", "HD", "MA", "BAC"
        ]
        
        logger.info(f"MarketDataCollector initialized")
    
    def collect_stock_data(self, ticker: str, period: str = "1d", interval: str = "1m") -> pd.DataFrame:
        """
        Collect historical stock data for a ticker
        
        Args:
            ticker: Stock ticker symbol
            period: Time period to collect (1d, 5d, 1mo, 3mo, 6mo, 1y, 2y, 5y, 10y, ytd, max)
            interval: Data interval (1m, 2m, 5m, 15m, 30m, 60m, 90m, 1h, 1d, 5d, 1wk, 1mo, 3mo)
        
        Returns:
            DataFrame with stock data
        """
        try:
            start_time = time.time()
            stock = yf.Ticker(ticker)
            data = stock.history(period=period, interval=interval)
            
            if data.empty:
                logger.warning(f"No data returned for {ticker}")
                return pd.DataFrame()
            
            # Log successful collection
            duration = time.time() - start_time
            logger.info(f"Collected {len(data)} data points for {ticker} ({period}, {interval}) in {duration:.2f}s")
            
            # Record metrics for data collection
            point = Point("market_data_collection") \
                .tag("ticker", ticker) \
                .tag("period", period) \
                .tag("interval", interval) \
                .field("data_points", len(data)) \
                .field("execution_time", duration) \
                .time(datetime.utcnow())
            self.write_api.write(bucket=self.influxdb_bucket, record=point)
            
            return data
            
        except Exception as e:
            logger.error(f"Error collecting data for {ticker}: {str(e)}")
            return pd.DataFrame()
    
    def collect_multiple_stocks(self, tickers: List[str], period: str = "1d", interval: str = "1m") -> Dict[str, pd.DataFrame]:
        """Collect data for multiple stocks"""
        results = {}
        for ticker in tickers:
            try:
                data = self.collect_stock_data(ticker, period, interval)
                if not data.empty:
                    results[ticker] = data
                # Respect rate limits
                time.sleep(0.2)
            except Exception as e:
                logger.error(f"Error in collect_multiple_stocks for {ticker}: {str(e)}")
        
        return results
    
    def collect_market_indices(self) -> Dict[str, pd.DataFrame]:
        """Collect data for major market indices"""
        logger.info("Collecting market indices data")
        return self.collect_multiple_stocks(self.major_indices, period="1d", interval="1m")
    
    def collect_sector_data(self) -> Dict[str, pd.DataFrame]:
        """Collect data for sector ETFs"""
        logger.info("Collecting sector ETFs data")
        return self.collect_multiple_stocks(self.sector_etfs, period="1d", interval="5m")
    
    def collect_top_stocks(self) -> Dict[str, pd.DataFrame]:
        """Collect data for top stocks by market cap"""
        logger.info("Collecting top stocks data")
        return self.collect_multiple_stocks(self.top_stocks, period="1d", interval="5m")
    
    def get_stock_info(self, ticker: str) -> Dict:
        """Get general information about a stock"""
        try:
            stock = yf.Ticker(ticker)
            info = stock.info
            
            # Log successful collection
            logger.info(f"Collected info for {ticker}")
            
            return info
        except Exception as e:
            logger.error(f"Error getting info for {ticker}: {str(e)}")
            return {}
    
    def calculate_technical_indicators(self, data: pd.DataFrame) -> pd.DataFrame:
        """Calculate technical indicators for a stock"""
        if data.empty:
            return data
            
        try:
            # Calculate SMA
            data['SMA20'] = data['Close'].rolling(window=20).mean()
            data['SMA50'] = data['Close'].rolling(window=50).mean()
            data['SMA200'] = data['Close'].rolling(window=200).mean()
            
            # Calculate EMA
            data['EMA20'] = data['Close'].ewm(span=20, adjust=False).mean()
            data['EMA50'] = data['Close'].ewm(span=50, adjust=False).mean()
            
            # Calculate RSI
            delta = data['Close'].diff()
            gain = (delta.where(delta > 0, 0)).rolling(window=14).mean()
            loss = (-delta.where(delta < 0, 0)).rolling(window=14).mean()
            rs = gain / loss
            data['RSI'] = 100 - (100 / (1 + rs))
            
            # Calculate MACD
            data['EMA12'] = data['Close'].ewm(span=12, adjust=False).mean()
            data['EMA26'] = data['Close'].ewm(span=26, adjust=False).mean()
            data['MACD'] = data['EMA12'] - data['EMA26']
            data['MACD_Signal'] = data['MACD'].ewm(span=9, adjust=False).mean()
            data['MACD_Hist'] = data['MACD'] - data['MACD_Signal']
            
            # Calculate Bollinger Bands
            data['BB_Middle'] = data['Close'].rolling(window=20).mean()
            data['BB_StdDev'] = data['Close'].rolling(window=20).std()
            data['BB_Upper'] = data['BB_Middle'] + (data['BB_StdDev'] * 2)
            data['BB_Lower'] = data['BB_Middle'] - (data['BB_StdDev'] * 2)
            
            return data
        except Exception as e:
            logger.error(f"Error calculating technical indicators: {str(e)}")
            return data
    
    def store_market_data(self, ticker: str, data: pd.DataFrame, data_type: str = "price") -> bool:
        """Store collected market data to InfluxDB"""
        if data.empty:
            logger.warning(f"No data to store for {ticker}")
            return False
            
        try:
            # Convert DataFrame to InfluxDB points
            points = []
            
            for timestamp, row in data.iterrows():
                point = Point(f"stock_{data_type}") \
                    .tag("ticker", ticker) \
                    .time(timestamp)
                
                # Add all numeric columns as fields
                for column, value in row.items():
                    if pd.notnull(value) and isinstance(value, (int, float)):
                        point = point.field(column.lower(), float(value))
                
                points.append(point)
            
            # Write all points to InfluxDB
            self.write_api.write(bucket=self.influxdb_bucket, record=points)
            
            logger.info(f"Stored {len(points)} data points for {ticker} in InfluxDB")
            return True
            
        except Exception as e:
            logger.error(f"Error storing data for {ticker}: {str(e)}")
            return False
    
    def update_ai_database(self, ticker: str, data: pd.DataFrame) -> bool:
        """Update the AI database with latest market data for training models"""
        # This would connect to the PostgreSQL AI database
        # For now, just log that we would store the data
        logger.info(f"Would update AI database with {len(data)} rows for {ticker}")
        return True
    
    def run_collection_cycle(self):
        """Run a full collection cycle for all market data"""
        logger.info("Starting market data collection cycle")
        
        # 1. Collect major indices data
        indices_data = self.collect_market_indices()
        for ticker, data in indices_data.items():
            if not data.empty:
                data_with_indicators = self.calculate_technical_indicators(data)
                self.store_market_data(ticker, data_with_indicators, "index")
                self.update_ai_database(ticker, data_with_indicators)
        
        # 2. Collect sector ETFs data
        sector_data = self.collect_sector_data()
        for ticker, data in sector_data.items():
            if not data.empty:
                data_with_indicators = self.calculate_technical_indicators(data)
                self.store_market_data(ticker, data_with_indicators, "sector")
                self.update_ai_database(ticker, data_with_indicators)
        
        # 3. Collect top stocks data
        stocks_data = self.collect_top_stocks()
        for ticker, data in stocks_data.items():
            if not data.empty:
                data_with_indicators = self.calculate_technical_indicators(data)
                self.store_market_data(ticker, data_with_indicators, "stock")
                self.update_ai_database(ticker, data_with_indicators)
        
        logger.info("Completed market data collection cycle")
    
    def collect_daily_historical_data(self, tickers: List[str], period: str = "1y") -> None:
        """
        Collect historical daily data for a list of tickers
        This is typically run less frequently (e.g., once per day)
        """
        logger.info(f"Collecting historical data for {len(tickers)} tickers")
        
        for ticker in tickers:
            try:
                data = self.collect_stock_data(ticker=ticker, period=period, interval="1d")
                if not data.empty:
                    data_with_indicators = self.calculate_technical_indicators(data)
                    self.store_market_data(ticker, data_with_indicators, "historical")
                    self.update_ai_database(ticker, data_with_indicators)
                    
                # Respect rate limits
                time.sleep(1)
                
            except Exception as e:
                logger.error(f"Error collecting historical data for {ticker}: {str(e)}")


# Main execution point when the module is run
if __name__ == "__main__":
    logger.info("Starting Zahaam Market Data Collector")
    collector = MarketDataCollector()
    
    # Main collection loop
    while True:
        try:
            collector.run_collection_cycle()
            
            # Run collection every 5 minutes for intraday data
            logger.info("Sleeping for 5 minutes before next collection cycle")
            time.sleep(300)
        except KeyboardInterrupt:
            logger.info("Market data collector stopped by user")
            break
        except Exception as e:
            logger.error(f"Error in collection cycle: {str(e)}")
            # Sleep for 1 minute and try again
            time.sleep(60)
