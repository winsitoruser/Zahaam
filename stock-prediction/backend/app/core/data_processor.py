"""
Data processing module for ZAHAAM big data system.
Handles data cleaning, feature engineering, and preprocessing for machine learning.
"""
import logging
import pandas as pd
import numpy as np
from typing import Dict, List, Any, Optional, Tuple, Union
from datetime import datetime, timedelta
import asyncio
from sqlalchemy.orm import Session
import joblib
import os

from app.models.stocks import Stock, StockPrice, StockIndicator
from app.models.big_data import (
    DataPoint, StockMetrics, MLModel, StockPrediction, 
    MarketDataSnapshot, DataProcessingJob
)
from app.core.data_sources import get_data_source_manager
from app.core.cache_manager import cache_data, clear_cache

logger = logging.getLogger(__name__)

class DataProcessor:
    """Processes raw financial data into features for machine learning"""
    
    def __init__(self, db: Session):
        self.db = db
        self.data_source_manager = get_data_source_manager()
        
    async def process_stock_data(self, ticker: str, days: int = 365) -> Tuple[pd.DataFrame, bool]:
        """
        Process stock data for a specific ticker
        
        Args:
            ticker: Stock ticker symbol
            days: Number of days of historical data to process
            
        Returns:
            Tuple of (processed DataFrame, success flag)
        """
        try:
            # Create a data processing job record
            job = DataProcessingJob(
                job_type="stock_data_processing",
                status="running",
                parameters={"ticker": ticker, "days": days}
            )
            self.db.add(job)
            self.db.commit()
            
            # Get historical data from multiple sources and merge
            end_date = datetime.now()
            start_date = end_date - timedelta(days=days)
            
            # Try to get data from Yahoo Finance first
            df_yahoo = await self.data_source_manager.get_historical_data(
                ticker, start_date, end_date, "1d", "Yahoo Finance"
            )
            
            # Get company info
            company_info = await self.data_source_manager.get_company_info(ticker)
            
            # If Yahoo Finance failed, try other sources
            if df_yahoo.empty:
                # Try to get data from any available source
                df = await self.data_source_manager.get_historical_data(
                    ticker, start_date, end_date, "1d"
                )
                if df.empty:
                    logger.error(f"Failed to get data for {ticker} from any source")
                    job.status = "failed"
                    job.error_message = f"Failed to get data for {ticker} from any source"
                    job.completed_at = datetime.now()
                    self.db.commit()
                    return pd.DataFrame(), False
            else:
                df = df_yahoo
                
            # Clean and preprocess data
            df = self._clean_stock_data(df)
            
            # Calculate technical indicators
            df = self._calculate_technical_indicators(df)
            
            # Save processed data to database
            success = await self._save_processed_data(ticker, df, company_info)
            
            # Update job status
            job.status = "completed" if success else "failed"
            job.completed_at = datetime.now()
            job.results = {"rows_processed": len(df)}
            self.db.commit()
            
            return df, success
            
        except Exception as e:
            logger.error(f"Error processing stock data for {ticker}: {str(e)}")
            # Update job status on error
            if 'job' in locals():
                job.status = "failed"
                job.error_message = str(e)
                job.completed_at = datetime.now()
                self.db.commit()
            return pd.DataFrame(), False
    
    def _clean_stock_data(self, df: pd.DataFrame) -> pd.DataFrame:
        """Clean and preprocess stock data"""
        if df.empty:
            return df
            
        # Make sure we have all required columns
        required_columns = ['Date', 'Open', 'High', 'Low', 'Close', 'Volume']
        for col in required_columns:
            if col not in df.columns:
                raise ValueError(f"Required column {col} missing from data")
                
        # Convert Date to datetime if it's not already
        if not pd.api.types.is_datetime64_any_dtype(df['Date']):
            df['Date'] = pd.to_datetime(df['Date'])
            
        # Sort by date
        df = df.sort_values('Date')
        
        # Fill missing values
        # For price data, forward fill (use previous day's value)
        price_cols = ['Open', 'High', 'Low', 'Close']
        df[price_cols] = df[price_cols].fillna(method='ffill')
        
        # For volume, use median of nearby values
        df['Volume'] = df['Volume'].fillna(df['Volume'].rolling(5, center=True).median())
        
        # Remove duplicates
        df = df.drop_duplicates(subset=['Date'])
        
        # Remove rows with NaN values after filling
        df = df.dropna(subset=price_cols + ['Volume'])
        
        # Add day of week, month, etc. for potential seasonality features
        df['DayOfWeek'] = df['Date'].dt.dayofweek
        df['Month'] = df['Date'].dt.month
        df['Year'] = df['Date'].dt.year
        df['DayOfMonth'] = df['Date'].dt.day
        
        # Add price change and return columns
        df['PriceChange'] = df['Close'].diff()
        df['Return'] = df['Close'].pct_change() * 100
        
        return df
        
    def _calculate_technical_indicators(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate technical indicators for the stock data"""
        if df.empty:
            return df
            
        try:
            # Simple Moving Averages
            for window in [5, 10, 20, 50, 100, 200]:
                df[f'SMA_{window}'] = df['Close'].rolling(window=window).mean()
                
            # Exponential Moving Averages
            for window in [5, 10, 20, 50, 100, 200]:
                df[f'EMA_{window}'] = df['Close'].ewm(span=window, adjust=False).mean()
                
            # RSI (Relative Strength Index)
            delta = df['Close'].diff()
            gain = delta.where(delta > 0, 0)
            loss = -delta.where(delta < 0, 0)
            
            avg_gain = gain.rolling(window=14).mean()
            avg_loss = loss.rolling(window=14).mean()
            
            rs = avg_gain / avg_loss
            df['RSI'] = 100 - (100 / (1 + rs))
            
            # MACD (Moving Average Convergence Divergence)
            ema_12 = df['Close'].ewm(span=12, adjust=False).mean()
            ema_26 = df['Close'].ewm(span=26, adjust=False).mean()
            df['MACD'] = ema_12 - ema_26
            df['MACD_Signal'] = df['MACD'].ewm(span=9, adjust=False).mean()
            df['MACD_Histogram'] = df['MACD'] - df['MACD_Signal']
            
            # Bollinger Bands
            for window in [20]:
                df[f'BB_Middle_{window}'] = df['Close'].rolling(window=window).mean()
                df[f'BB_Std_{window}'] = df['Close'].rolling(window=window).std()
                df[f'BB_Upper_{window}'] = df[f'BB_Middle_{window}'] + 2 * df[f'BB_Std_{window}']
                df[f'BB_Lower_{window}'] = df[f'BB_Middle_{window}'] - 2 * df[f'BB_Std_{window}']
                
            # Stochastic Oscillator
            low_14 = df['Low'].rolling(window=14).min()
            high_14 = df['High'].rolling(window=14).max()
            df['%K'] = 100 * ((df['Close'] - low_14) / (high_14 - low_14))
            df['%D'] = df['%K'].rolling(window=3).mean()
            
            # Average True Range (ATR)
            tr1 = df['High'] - df['Low']
            tr2 = abs(df['High'] - df['Close'].shift())
            tr3 = abs(df['Low'] - df['Close'].shift())
            df['TR'] = pd.concat([tr1, tr2, tr3], axis=1).max(axis=1)
            df['ATR'] = df['TR'].rolling(window=14).mean()
            
            # Volume indicators
            df['Volume_Change'] = df['Volume'].pct_change() * 100
            df['Volume_MA_20'] = df['Volume'].rolling(window=20).mean()
            df['Relative_Volume'] = df['Volume'] / df['Volume_MA_20']
            
            # On-Balance Volume (OBV)
            df['OBV'] = 0
            df.loc[df['Close'] > df['Close'].shift(), 'OBV'] = df['Volume']
            df.loc[df['Close'] < df['Close'].shift(), 'OBV'] = -df['Volume']
            df['OBV'] = df['OBV'].cumsum()
            
            # Money Flow Index (MFI)
            typical_price = (df['High'] + df['Low'] + df['Close']) / 3
            raw_money_flow = typical_price * df['Volume']
            
            positive_flow = raw_money_flow.where(typical_price > typical_price.shift(), 0)
            negative_flow = raw_money_flow.where(typical_price < typical_price.shift(), 0)
            
            positive_mf = positive_flow.rolling(window=14).sum()
            negative_mf = negative_flow.rolling(window=14).sum()
            
            money_flow_ratio = positive_mf / negative_mf
            df['MFI'] = 100 - (100 / (1 + money_flow_ratio))
            
            # Volatility
            df['Volatility_20'] = df['Return'].rolling(window=20).std()
            
            # Price distance from moving averages (%)
            for ma in [20, 50, 200]:
                df[f'Distance_SMA_{ma}'] = (df['Close'] - df[f'SMA_{ma}']) / df[f'SMA_{ma}'] * 100
                
            # Generate trading signals
            df['Signal'] = 'HOLD'
            
            # Buy signals
            buy_conditions = (
                (df['RSI'] < 30) |  # RSI oversold
                (df['MACD'] > df['MACD_Signal']) & (df['MACD'].shift() <= df['MACD_Signal'].shift()) |  # MACD crossover
                (df['Close'] < df['BB_Lower_20'])  # Price below lower Bollinger Band
            )
            df.loc[buy_conditions, 'Signal'] = 'BUY'
            
            # Sell signals
            sell_conditions = (
                (df['RSI'] > 70) |  # RSI overbought
                (df['MACD'] < df['MACD_Signal']) & (df['MACD'].shift() >= df['MACD_Signal'].shift()) |  # MACD crossover
                (df['Close'] > df['BB_Upper_20'])  # Price above upper Bollinger Band
            )
            df.loc[sell_conditions, 'Signal'] = 'SELL'
            
            # Signal strength based on multiple indicators agreement
            df['Signal_Strength'] = 0.5  # Neutral
            
            # Count how many indicators suggest buy or sell
            buy_count = 0
            sell_count = 0
            
            # RSI
            buy_count += (df['RSI'] < 30).astype(int)
            sell_count += (df['RSI'] > 70).astype(int)
            
            # MACD
            buy_count += ((df['MACD'] > df['MACD_Signal']) & (df['MACD'].shift() <= df['MACD_Signal'].shift())).astype(int)
            sell_count += ((df['MACD'] < df['MACD_Signal']) & (df['MACD'].shift() >= df['MACD_Signal'].shift())).astype(int)
            
            # Bollinger Bands
            buy_count += (df['Close'] < df['BB_Lower_20']).astype(int)
            sell_count += (df['Close'] > df['BB_Upper_20']).astype(int)
            
            # Moving Average Crossovers
            buy_count += ((df['SMA_20'] > df['SMA_50']) & (df['SMA_20'].shift() <= df['SMA_50'].shift())).astype(int)
            sell_count += ((df['SMA_20'] < df['SMA_50']) & (df['SMA_20'].shift() >= df['SMA_50'].shift())).astype(int)
            
            # Stochastic Oscillator
            buy_count += ((df['%K'] < 20) & (df['%K'] > df['%K'].shift())).astype(int)
            sell_count += ((df['%K'] > 80) & (df['%K'] < df['%K'].shift())).astype(int)
            
            # Volume Surge
            buy_count += ((df['Relative_Volume'] > 1.5) & (df['Close'] > df['Open'])).astype(int)
            sell_count += ((df['Relative_Volume'] > 1.5) & (df['Close'] < df['Open'])).astype(int)
            
            # Add to dataframe
            df['Buy_Count'] = buy_count
            df['Sell_Count'] = sell_count
            df['Total_Signals'] = buy_count + sell_count
            
            # Calculate signal strength
            max_possible = 6  # Total number of indicators we're using
            df.loc[df['Signal'] == 'BUY', 'Signal_Strength'] = df['Buy_Count'] / max_possible
            df.loc[df['Signal'] == 'SELL', 'Signal_Strength'] = df['Sell_Count'] / max_possible
            
            # Fill NaN values in indicators
            df = df.fillna(method='bfill')
            
            return df
            
        except Exception as e:
            logger.error(f"Error calculating technical indicators: {str(e)}")
            return df
    
    async def _save_processed_data(self, ticker: str, df: pd.DataFrame, company_info: Dict[str, Any]) -> bool:
        """Save processed data to the database"""
        if df.empty:
            return False
            
        try:
            # Check if stock exists
            stock = self.db.query(Stock).filter(Stock.ticker == ticker).first()
            
            # If not, create it
            if not stock:
                stock = Stock(
                    ticker=ticker,
                    name=company_info.get('name', ticker),
                    sector=company_info.get('sector', 'N/A'),
                    last_updated=datetime.now(),
                    is_active=True
                )
                self.db.add(stock)
                self.db.commit()
                self.db.refresh(stock)
            else:
                # Update stock info
                stock.name = company_info.get('name', stock.name)
                stock.sector = company_info.get('sector', stock.sector)
                stock.last_updated = datetime.now()
                self.db.commit()
            
            # Process and save each day's data
            for _, row in df.iterrows():
                date = row['Date']
                
                # Save price data
                price = self.db.query(StockPrice).filter(
                    StockPrice.stock_id == stock.id,
                    StockPrice.date == date
                ).first()
                
                if not price:
                    price = StockPrice(
                        stock_id=stock.id,
                        date=date,
                        open=row['Open'],
                        high=row['High'],
                        low=row['Low'],
                        close=row['Close'],
                        volume=row['Volume']
                    )
                    self.db.add(price)
                else:
                    price.open = row['Open']
                    price.high = row['High']
                    price.low = row['Low']
                    price.close = row['Close']
                    price.volume = row['Volume']
                
                # Save indicators
                indicator = self.db.query(StockIndicator).filter(
                    StockIndicator.stock_id == stock.id,
                    StockIndicator.date == date
                ).first()
                
                if not indicator:
                    indicator = StockIndicator(
                        stock_id=stock.id,
                        date=date,
                        sma_20=row.get('SMA_20'),
                        sma_50=row.get('SMA_50'),
                        sma_200=row.get('SMA_200'),
                        ema_12=row.get('EMA_12'),
                        ema_26=row.get('EMA_26'),
                        rsi_14=row.get('RSI'),
                        macd=row.get('MACD'),
                        macd_signal=row.get('MACD_Signal'),
                        macd_histogram=row.get('MACD_Histogram'),
                        bb_upper=row.get('BB_Upper_20'),
                        bb_middle=row.get('BB_Middle_20'),
                        bb_lower=row.get('BB_Lower_20'),
                        signal=row.get('Signal'),
                        signal_strength=row.get('Signal_Strength'),
                        notes=""
                    )
                    self.db.add(indicator)
                else:
                    indicator.sma_20 = row.get('SMA_20')
                    indicator.sma_50 = row.get('SMA_50')
                    indicator.sma_200 = row.get('SMA_200')
                    indicator.ema_12 = row.get('EMA_12')
                    indicator.ema_26 = row.get('EMA_26')
                    indicator.rsi_14 = row.get('RSI')
                    indicator.macd = row.get('MACD')
                    indicator.macd_signal = row.get('MACD_Signal')
                    indicator.macd_histogram = row.get('MACD_Histogram')
                    indicator.bb_upper = row.get('BB_Upper_20')
                    indicator.bb_middle = row.get('BB_Middle_20')
                    indicator.bb_lower = row.get('BB_Lower_20')
                    indicator.signal = row.get('Signal')
                    indicator.signal_strength = row.get('Signal_Strength')
                
                # Save additional metrics if available
                metrics = self.db.query(StockMetrics).filter(
                    StockMetrics.stock_id == stock.id,
                    StockMetrics.date == date
                ).first()
                
                if not metrics:
                    metrics = StockMetrics(
                        stock_id=stock.id,
                        date=date,
                        pe_ratio=company_info.get('pe_ratio'),
                        dividend_yield=company_info.get('dividend_yield'),
                        market_cap=company_info.get('marketCap'),
                        beta=company_info.get('beta'),
                        historical_volatility=row.get('Volatility_20'),
                        atr=row.get('ATR')
                    )
                    self.db.add(metrics)
                else:
                    metrics.pe_ratio = company_info.get('pe_ratio')
                    metrics.dividend_yield = company_info.get('dividend_yield')
                    metrics.market_cap = company_info.get('marketCap')
                    metrics.beta = company_info.get('beta')
                    metrics.historical_volatility = row.get('Volatility_20')
                    metrics.atr = row.get('ATR')
            
            # Commit all changes
            self.db.commit()
            logger.info(f"Successfully saved processed data for {ticker}")
            return True
            
        except Exception as e:
            self.db.rollback()
            logger.error(f"Error saving processed data for {ticker}: {str(e)}")
            return False
            
    async def process_market_data(self) -> bool:
        """Process market-wide data for insights"""
        try:
            # Get a snapshot of major indices
            indices = ["^GSPC", "^IXIC", "^DJI", "^VIX"]  # S&P 500, NASDAQ, Dow Jones, VIX
            index_data = {}
            
            for index in indices:
                df = await self.data_source_manager.get_historical_data(
                    index, datetime.now() - timedelta(days=5), datetime.now(), "1d"
                )
                if not df.empty:
                    index_data[index] = df.iloc[-1]["Close"]
            
            # Get sector performance
            sectors = ["XLK", "XLF", "XLE", "XLV", "XLI", "XLP", "XLY", "XLU", "XLB", "XLRE", "XLC"]
            sector_performance = {}
            
            for sector in sectors:
                df = await self.data_source_manager.get_historical_data(
                    sector, datetime.now() - timedelta(days=5), datetime.now(), "1d"
                )
                if not df.empty:
                    sector_performance[sector] = df.iloc[-1]["Close"] / df.iloc[0]["Close"] - 1  # % change
            
            # Create market data snapshot
            snapshot = MarketDataSnapshot(
                date=datetime.now(),
                sp500=index_data.get("^GSPC"),
                nasdaq=index_data.get("^IXIC"),
                dow_jones=index_data.get("^DJI"),
                vix=index_data.get("^VIX"),
                sector_performance=sector_performance
            )
            
            self.db.add(snapshot)
            self.db.commit()
            
            logger.info("Successfully created market data snapshot")
            return True
            
        except Exception as e:
            self.db.rollback()
            logger.error(f"Error processing market data: {str(e)}")
            return False

# Singleton instance
_data_processor = None

def get_data_processor(db: Session) -> DataProcessor:
    """Get the singleton instance of the data processor"""
    global _data_processor
    if _data_processor is None or _data_processor.db != db:
        _data_processor = DataProcessor(db)
    return _data_processor
