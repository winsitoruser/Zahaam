"""
Data source integrations for ZAHAAM big data system.
This module provides connectors to various financial data sources.
"""
import logging
import asyncio
import pandas as pd
import numpy as np
import yfinance as yf
import aiohttp
import json
from typing import Dict, List, Any, Optional, Tuple, Union
from datetime import datetime, timedelta
from ratelimit import limits, sleep_and_retry
from app.core.cache_manager import cache_data, clear_cache

logger = logging.getLogger(__name__)

class DataSourceBase:
    """Base class for all data sources"""
    
    def __init__(self, name: str):
        self.name = name
        
    async def get_historical_data(self, 
                           ticker: str, 
                           start_date: Optional[datetime] = None,
                           end_date: Optional[datetime] = None,
                           interval: str = "1d") -> pd.DataFrame:
        """Get historical price data for a ticker"""
        raise NotImplementedError("Subclasses must implement this method")
        
    async def get_company_info(self, ticker: str) -> Dict[str, Any]:
        """Get company information for a ticker"""
        raise NotImplementedError("Subclasses must implement this method")
        
    async def get_market_news(self, ticker: Optional[str] = None) -> List[Dict[str, Any]]:
        """Get market news, optionally filtered by ticker"""
        raise NotImplementedError("Subclasses must implement this method")
    
    async def is_available(self) -> bool:
        """Check if the data source is available"""
        try:
            # Implement a lightweight check
            return True
        except Exception as e:
            logger.warning(f"Data source {self.name} is not available: {str(e)}")
            return False


class YahooFinanceDataSource(DataSourceBase):
    """Yahoo Finance data source implementation"""
    
    def __init__(self):
        super().__init__("Yahoo Finance")
    
    @sleep_and_retry
    @limits(calls=2, period=1)
    async def get_historical_data(self, 
                           ticker: str, 
                           start_date: Optional[datetime] = None,
                           end_date: Optional[datetime] = None,
                           interval: str = "1d") -> pd.DataFrame:
        """Get historical price data from Yahoo Finance"""
        try:
            # Default date range if not specified
            if not end_date:
                end_date = datetime.now()
            if not start_date:
                start_date = end_date - timedelta(days=365)
                
            # Convert dates to strings
            start_str = start_date.strftime('%Y-%m-%d')
            end_str = end_date.strftime('%Y-%m-%d')
            
            # Get data using yfinance
            stock = yf.Ticker(ticker)
            df = stock.history(start=start_str, end=end_str, interval=interval)
            
            # Reset index to include Date as a column
            df = df.reset_index()
            
            logger.info(f"Successfully fetched {len(df)} records from Yahoo Finance for {ticker}")
            return df
            
        except Exception as e:
            logger.error(f"Error fetching data from Yahoo Finance for {ticker}: {str(e)}")
            return pd.DataFrame()  # Return empty dataframe on error
    
    async def get_company_info(self, ticker: str) -> Dict[str, Any]:
        """Get company information from Yahoo Finance"""
        try:
            stock = yf.Ticker(ticker)
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
                'beta': info.get('beta', 0),
                'trailingPE': info.get('trailingPE', 0),
                'forwardPE': info.get('forwardPE', 0),
                'dividendYield': info.get('dividendYield', 0),
                'description': info.get('longBusinessSummary', ''),
                'website': info.get('website', ''),
                'logo': info.get('logo_url', '')
            }
            
            return company_info
            
        except Exception as e:
            logger.error(f"Error fetching company info from Yahoo Finance for {ticker}: {str(e)}")
            return {'name': ticker, 'sector': 'N/A'}
    
    async def get_market_news(self, ticker: Optional[str] = None) -> List[Dict[str, Any]]:
        """Get market news from Yahoo Finance"""
        try:
            if ticker:
                stock = yf.Ticker(ticker)
                news = stock.news
                return news[:10]  # Limit to 10 news items
            else:
                # Get general market news (we could use a market index like ^GSPC for S&P 500)
                market = yf.Ticker("^GSPC")
                news = market.news
                return news[:10]
                
        except Exception as e:
            logger.error(f"Error fetching market news from Yahoo Finance: {str(e)}")
            return []


class AlphaVantageDataSource(DataSourceBase):
    """Alpha Vantage data source implementation"""
    
    def __init__(self, api_key: str):
        super().__init__("Alpha Vantage")
        self.api_key = api_key
        self.base_url = "https://www.alphavantage.co/query"
        
    @sleep_and_retry
    @limits(calls=5, period=60)  # Alpha Vantage has stricter limits
    async def get_historical_data(self, 
                           ticker: str, 
                           start_date: Optional[datetime] = None,
                           end_date: Optional[datetime] = None,
                           interval: str = "1d") -> pd.DataFrame:
        """Get historical price data from Alpha Vantage"""
        try:
            # Map interval to Alpha Vantage format
            interval_map = {
                "1d": "daily",
                "1w": "weekly",
                "1m": "monthly"
            }
            function = f"TIME_SERIES_{interval_map.get(interval, 'daily').upper()}"
            
            # Build request URL
            params = {
                "function": function,
                "symbol": ticker,
                "apikey": self.api_key,
                "outputsize": "full"
            }
            
            # Make request
            async with aiohttp.ClientSession() as session:
                async with session.get(self.base_url, params=params) as response:
                    if response.status != 200:
                        logger.error(f"Error fetching data from Alpha Vantage: {response.status}")
                        return pd.DataFrame()
                    
                    data = await response.json()
                    
                    # Extract time series data
                    if interval == "1d":
                        time_series_key = "Time Series (Daily)"
                    elif interval == "1w":
                        time_series_key = "Weekly Time Series"
                    else:
                        time_series_key = "Monthly Time Series"
                    
                    if time_series_key not in data:
                        logger.error(f"No time series data found in Alpha Vantage response: {data}")
                        return pd.DataFrame()
                    
                    time_series = data[time_series_key]
                    
                    # Convert to dataframe
                    records = []
                    for date, values in time_series.items():
                        record = {
                            "Date": datetime.strptime(date, "%Y-%m-%d"),
                            "Open": float(values["1. open"]),
                            "High": float(values["2. high"]),
                            "Low": float(values["3. low"]),
                            "Close": float(values["4. close"]),
                            "Volume": int(values["5. volume"])
                        }
                        records.append(record)
                    
                    df = pd.DataFrame(records)
                    
                    # Filter by date range if specified
                    if start_date:
                        df = df[df["Date"] >= start_date]
                    if end_date:
                        df = df[df["Date"] <= end_date]
                    
                    # Sort by date
                    df = df.sort_values("Date")
                    
                    logger.info(f"Successfully fetched {len(df)} records from Alpha Vantage for {ticker}")
                    return df
                    
        except Exception as e:
            logger.error(f"Error fetching data from Alpha Vantage for {ticker}: {str(e)}")
            return pd.DataFrame()
    
    async def get_company_info(self, ticker: str) -> Dict[str, Any]:
        """Get company information from Alpha Vantage"""
        try:
            # Build request URL
            params = {
                "function": "OVERVIEW",
                "symbol": ticker,
                "apikey": self.api_key
            }
            
            # Make request
            async with aiohttp.ClientSession() as session:
                async with session.get(self.base_url, params=params) as response:
                    if response.status != 200:
                        logger.error(f"Error fetching company info from Alpha Vantage: {response.status}")
                        return {'name': ticker, 'sector': 'N/A'}
                    
                    data = await response.json()
                    
                    company_info = {
                        'name': data.get('Name', ticker),
                        'sector': data.get('Sector', 'N/A'),
                        'industry': data.get('Industry', 'N/A'),
                        'marketCap': float(data.get('MarketCapitalization', 0)),
                        'beta': float(data.get('Beta', 0)),
                        'pe_ratio': float(data.get('PERatio', 0)),
                        'dividend_yield': float(data.get('DividendYield', 0)),
                        'description': data.get('Description', ''),
                    }
                    
                    return company_info
                    
        except Exception as e:
            logger.error(f"Error fetching company info from Alpha Vantage for {ticker}: {str(e)}")
            return {'name': ticker, 'sector': 'N/A'}
    
    async def get_market_news(self, ticker: Optional[str] = None) -> List[Dict[str, Any]]:
        """Alpha Vantage doesn't provide news API, so this is a placeholder"""
        logger.warning("Alpha Vantage doesn't provide a news API")
        return []


class GoogleFinanceDataSource(DataSourceBase):
    """Google Finance data source implementation (simulated)"""
    
    def __init__(self):
        super().__init__("Google Finance")
        # Google Finance doesn't have a public API, so this is more of a placeholder
        # In a real implementation, this might use web scraping techniques
        
    async def get_historical_data(self, 
                           ticker: str, 
                           start_date: Optional[datetime] = None,
                           end_date: Optional[datetime] = None,
                           interval: str = "1d") -> pd.DataFrame:
        """
        Get historical price data from Google Finance
        
        Note: This is a placeholder. Google Finance doesn't offer a public API.
        In a real implementation, this would use web scraping or an unofficial API.
        """
        logger.warning("Google Finance data source is not fully implemented")
        return pd.DataFrame()  # Return empty dataframe
    
    async def get_company_info(self, ticker: str) -> Dict[str, Any]:
        """Get company information from Google Finance"""
        logger.warning("Google Finance data source is not fully implemented")
        return {'name': ticker, 'sector': 'N/A'}
    
    async def get_market_news(self, ticker: Optional[str] = None) -> List[Dict[str, Any]]:
        """Get market news from Google Finance"""
        logger.warning("Google Finance data source is not fully implemented")
        return []


class MarketStackDataSource(DataSourceBase):
    """MarketStack data source implementation"""
    
    def __init__(self, api_key: str):
        super().__init__("MarketStack")
        self.api_key = api_key
        self.base_url = "http://api.marketstack.com/v1"
        
    @sleep_and_retry
    @limits(calls=5, period=10)
    async def get_historical_data(self, 
                           ticker: str, 
                           start_date: Optional[datetime] = None,
                           end_date: Optional[datetime] = None,
                           interval: str = "1d") -> pd.DataFrame:
        """Get historical price data from MarketStack"""
        try:
            # Default date range if not specified
            if not end_date:
                end_date = datetime.now()
            if not start_date:
                start_date = end_date - timedelta(days=365)
                
            # Convert dates to strings
            start_str = start_date.strftime('%Y-%m-%d')
            end_str = end_date.strftime('%Y-%m-%d')
            
            # Build request URL
            params = {
                "access_key": self.api_key,
                "symbols": ticker,
                "date_from": start_str,
                "date_to": end_str,
                "limit": 1000
            }
            
            # Make request
            async with aiohttp.ClientSession() as session:
                async with session.get(f"{self.base_url}/eod", params=params) as response:
                    if response.status != 200:
                        logger.error(f"Error fetching data from MarketStack: {response.status}")
                        return pd.DataFrame()
                    
                    data = await response.json()
                    
                    if "data" not in data:
                        logger.error(f"No data found in MarketStack response: {data}")
                        return pd.DataFrame()
                    
                    # Convert to dataframe
                    records = []
                    for item in data["data"]:
                        record = {
                            "Date": datetime.fromisoformat(item["date"].split("T")[0]),
                            "Open": item["open"],
                            "High": item["high"],
                            "Low": item["low"],
                            "Close": item["close"],
                            "Volume": item["volume"]
                        }
                        records.append(record)
                    
                    df = pd.DataFrame(records)
                    
                    # Sort by date
                    df = df.sort_values("Date")
                    
                    logger.info(f"Successfully fetched {len(df)} records from MarketStack for {ticker}")
                    return df
                    
        except Exception as e:
            logger.error(f"Error fetching data from MarketStack for {ticker}: {str(e)}")
            return pd.DataFrame()
    
    async def get_company_info(self, ticker: str) -> Dict[str, Any]:
        """MarketStack doesn't provide company info API in basic plan"""
        logger.warning("MarketStack doesn't provide company info API in basic plan")
        return {'name': ticker, 'sector': 'N/A'}
    
    async def get_market_news(self, ticker: Optional[str] = None) -> List[Dict[str, Any]]:
        """MarketStack doesn't provide news API"""
        logger.warning("MarketStack doesn't provide news API")
        return []


class DataSourceManager:
    """Manager class for handling multiple data sources"""
    
    def __init__(self):
        self.sources: Dict[str, DataSourceBase] = {}
        self.primary_source: Optional[str] = None
        
    def add_source(self, source: DataSourceBase, is_primary: bool = False):
        """Add a data source to the manager"""
        self.sources[source.name] = source
        if is_primary or self.primary_source is None:
            self.primary_source = source.name
            
    def get_source(self, name: Optional[str] = None) -> Optional[DataSourceBase]:
        """Get a data source by name, or the primary source if name is None"""
        if name is None:
            if self.primary_source:
                return self.sources.get(self.primary_source)
            return None
        return self.sources.get(name)
    
    def set_primary_source(self, name: str):
        """Set the primary data source"""
        if name in self.sources:
            self.primary_source = name
        else:
            raise ValueError(f"Data source {name} not found")
            
    async def get_available_sources(self) -> List[str]:
        """Get a list of currently available data sources"""
        available = []
        for name, source in self.sources.items():
            if await source.is_available():
                available.append(name)
        return available
    
    @cache_data(ttl_seconds=300)  # Cache for 5 minutes
    async def get_historical_data(self, 
                           ticker: str, 
                           start_date: Optional[datetime] = None,
                           end_date: Optional[datetime] = None,
                           interval: str = "1d",
                           source_name: Optional[str] = None) -> pd.DataFrame:
        """
        Get historical data from the specified source, falling back to other sources if needed
        
        Args:
            ticker: Stock ticker symbol
            start_date: Start date for historical data
            end_date: End date for historical data
            interval: Data interval (1d, 1w, 1m)
            source_name: Name of the data source to use, or None for primary
            
        Returns:
            DataFrame with historical price data
        """
        # Try the specified source first
        if source_name:
            source = self.get_source(source_name)
            if source:
                df = await source.get_historical_data(ticker, start_date, end_date, interval)
                if not df.empty:
                    logger.info(f"Successfully fetched data from {source_name} for {ticker}")
                    return df
                logger.warning(f"Failed to fetch data from {source_name} for {ticker}")
        
        # If no source specified or the specified source failed, try primary
        if not source_name or source_name != self.primary_source:
            primary = self.get_source()
            if primary:
                df = await primary.get_historical_data(ticker, start_date, end_date, interval)
                if not df.empty:
                    logger.info(f"Successfully fetched data from primary source {primary.name} for {ticker}")
                    return df
                logger.warning(f"Failed to fetch data from primary source {primary.name} for {ticker}")
        
        # If primary source failed or wasn't available, try all other sources
        for name, source in self.sources.items():
            if name == source_name or name == self.primary_source:
                continue  # Skip sources we've already tried
                
            df = await source.get_historical_data(ticker, start_date, end_date, interval)
            if not df.empty:
                logger.info(f"Successfully fetched data from fallback source {name} for {ticker}")
                return df
        
        # If all sources failed, return empty dataframe
        logger.error(f"Failed to fetch data from any source for {ticker}")
        return pd.DataFrame()
    
    @cache_data(ttl_seconds=3600)  # Cache for 1 hour
    async def get_company_info(self, 
                         ticker: str,
                         source_name: Optional[str] = None) -> Dict[str, Any]:
        """
        Get company information with fallback mechanism
        """
        # Similar implementation to get_historical_data but for company info
        result = {'name': ticker, 'sector': 'N/A'}
        
        # Try specified source
        if source_name:
            source = self.get_source(source_name)
            if source:
                info = await source.get_company_info(ticker)
                if info and info.get('name') != 'N/A':
                    return info
        
        # Try primary source
        primary = self.get_source()
        if primary and (not source_name or source_name != primary.name):
            info = await primary.get_company_info(ticker)
            if info and info.get('name') != 'N/A':
                return info
        
        # Try all other sources
        for name, source in self.sources.items():
            if name == source_name or name == self.primary_source:
                continue
                
            info = await source.get_company_info(ticker)
            if info and info.get('name') != 'N/A':
                return info
        
        return result
    
    @cache_data(ttl_seconds=1800)  # Cache for 30 minutes
    async def get_market_news(self, 
                        ticker: Optional[str] = None,
                        source_name: Optional[str] = None) -> List[Dict[str, Any]]:
        """
        Get market news with fallback mechanism
        """
        # Similar implementation to the other methods but for news
        # Try specified source
        if source_name:
            source = self.get_source(source_name)
            if source:
                news = await source.get_market_news(ticker)
                if news:
                    return news
        
        # Try primary source
        primary = self.get_source()
        if primary and (not source_name or source_name != primary.name):
            news = await primary.get_market_news(ticker)
            if news:
                return news
        
        # Try all other sources
        for name, source in self.sources.items():
            if name == source_name or name == self.primary_source:
                continue
                
            news = await source.get_market_news(ticker)
            if news:
                return news
        
        return []


# Create a singleton instance of the data source manager
_data_source_manager = None

def get_data_source_manager() -> DataSourceManager:
    """Get the singleton instance of the data source manager"""
    global _data_source_manager
    if _data_source_manager is None:
        _data_source_manager = DataSourceManager()
        
        # Add default data sources
        _data_source_manager.add_source(YahooFinanceDataSource(), is_primary=True)
        
        # Add other data sources if API keys are available
        # These would typically be loaded from environment variables or config
        alpha_vantage_api_key = os.environ.get("ALPHA_VANTAGE_API_KEY")
        if alpha_vantage_api_key:
            _data_source_manager.add_source(AlphaVantageDataSource(alpha_vantage_api_key))
            
        marketstack_api_key = os.environ.get("MARKETSTACK_API_KEY")
        if marketstack_api_key:
            _data_source_manager.add_source(MarketStackDataSource(marketstack_api_key))
            
        # Google Finance is a placeholder
        # _data_source_manager.add_source(GoogleFinanceDataSource())
        
    return _data_source_manager
