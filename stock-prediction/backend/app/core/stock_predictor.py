"""
Stock prediction service module
"""
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import logging
from typing import Dict, List, Any, Optional, Tuple
from sqlalchemy.orm import Session

from app.models.stocks import Stock, StockPrice, StockIndicator
from app.core.trading_strategies import (
    calculate_moving_averages,
    calculate_rsi,
    calculate_macd,
    calculate_bollinger_bands,
    strategy_moving_average_crossover,
    strategy_rsi_oversold_overbought,
    strategy_macd,
    strategy_bollinger_bands_bounce,
    combine_strategies,
    get_stop_loss_level,
    get_take_profit_level
)

# Configure logging
logger = logging.getLogger(__name__)

class StockPredictionService:
    """Service for generating stock predictions and trading signals"""
    
    def __init__(self, db: Session):
        """Initialize with database session"""
        self.db = db
    
    def _get_stock_dataframe(self, ticker: str, days: int = 365) -> pd.DataFrame:
        """
        Get stock price data as a DataFrame from the database
        
        Args:
            ticker: Stock ticker symbol
            days: Number of days of historical data
            
        Returns:
            DataFrame with stock price data
        """
        try:
            # Get the stock from the database
            stock = self.db.query(Stock).filter(Stock.ticker == ticker).first()
            if not stock:
                raise ValueError(f"Stock {ticker} not found in database")
            
            # Get the date range
            end_date = datetime.now()
            start_date = end_date - timedelta(days=days)
            
            # Query stock prices
            prices = (self.db.query(StockPrice)
                     .filter(StockPrice.stock_id == stock.id)
                     .filter(StockPrice.date >= start_date)
                     .filter(StockPrice.date <= end_date)
                     .order_by(StockPrice.date)
                     .all())
            
            if not prices:
                raise ValueError(f"No price data for {ticker} in the specified date range")
            
            # Convert to DataFrame
            df = pd.DataFrame([
                {
                    'Date': price.date,
                    'Open': price.open,
                    'High': price.high,
                    'Low': price.low,
                    'Close': price.close,
                    'Volume': price.volume
                }
                for price in prices
            ])
            
            df.set_index('Date', inplace=True)
            return df
            
        except Exception as e:
            logger.error(f"Error getting stock data for {ticker}: {str(e)}")
            raise
    
    def generate_prediction(self, ticker: str, days: int = 365) -> Dict[str, Any]:
        """
        Generate prediction for a stock including technical indicators, 
        entry/exit signals, and price targets
        
        Args:
            ticker: Stock ticker symbol
            days: Number of days of historical data to use
            
        Returns:
            Dict containing prediction data
        """
        try:
            # Get stock data
            df = self._get_stock_dataframe(ticker, days)
            
            # Apply technical analysis
            df = calculate_moving_averages(df)
            df = calculate_rsi(df)
            df = calculate_macd(df)
            df = calculate_bollinger_bands(df)
            
            # Apply combined strategy
            df = combine_strategies(df)
            
            # Get latest price
            latest_price = df['Close'].iloc[-1]
            
            # Check if we have a buy or sell signal on the latest day
            latest_action = df['Combined_Action'].iloc[-1]
            
            # Initialize prediction results
            prediction = {
                'ticker': ticker,
                'latest_price': round(latest_price, 2),
                'prediction_date': datetime.now().strftime('%Y-%m-%d'),
                'action': latest_action if pd.notna(latest_action) else 'HOLD',
                'confidence': self._calculate_confidence(df),
                'technical_indicators': {
                    'sma_20': round(df['SMA_20'].iloc[-1], 2) if 'SMA_20' in df.columns else None,
                    'sma_50': round(df['SMA_50'].iloc[-1], 2) if 'SMA_50' in df.columns else None,
                    'rsi': round(df['RSI'].iloc[-1], 2) if 'RSI' in df.columns else None,
                    'macd': round(df['MACD'].iloc[-1], 2) if 'MACD' in df.columns else None,
                    'macd_signal': round(df['MACD_Signal'].iloc[-1], 2) if 'MACD_Signal' in df.columns else None,
                    'bb_upper': round(df['BB_Upper'].iloc[-1], 2) if 'BB_Upper' in df.columns else None,
                    'bb_lower': round(df['BB_Lower'].iloc[-1], 2) if 'BB_Lower' in df.columns else None,
                }
            }
            
            # If there's a buy signal, calculate entry, target, and stop loss
            if latest_action == 'BUY':
                entry_price = latest_price
                stop_loss = get_stop_loss_level(df, entry_price)
                take_profit = get_take_profit_level(entry_price, stop_loss)
                
                prediction['entry_point'] = {
                    'price': round(entry_price, 2),
                    'date': datetime.now().strftime('%Y-%m-%d'),
                    'stop_loss': round(stop_loss, 2),
                    'take_profit': round(take_profit, 2),
                    'risk_reward_ratio': 2.0,
                }
                
                # Add recommendation text
                prediction['recommendation'] = (
                    f"BUY {ticker} at {round(entry_price, 2)}. "
                    f"Set stop loss at {round(stop_loss, 2)} and "
                    f"take profit at {round(take_profit, 2)}."
                )
                
            # For sell signals
            elif latest_action == 'SELL':
                exit_price = latest_price
                prediction['exit_point'] = {
                    'price': round(exit_price, 2),
                    'date': datetime.now().strftime('%Y-%m-%d')
                }
                prediction['recommendation'] = f"SELL {ticker} at {round(exit_price, 2)}."
            
            # No clear signal
            else:
                prediction['recommendation'] = f"HOLD {ticker}. No clear entry or exit signal at the moment."
            
            # Add potential price targets based on Fibonacci extensions
            prediction['price_targets'] = self._calculate_price_targets(df)
            
            # Add expected time frame for the prediction
            prediction['time_frame'] = self._determine_time_frame(df)
            
            return prediction
            
        except Exception as e:
            logger.error(f"Error generating prediction for {ticker}: {str(e)}")
            return {
                'ticker': ticker,
                'error': str(e),
                'status': 'failed'
            }
    
    def _calculate_confidence(self, df: pd.DataFrame) -> int:
        """
        Calculate confidence percentage in the prediction
        
        Returns:
            Confidence score (0-100)
        """
        confidence = 50  # Start with neutral confidence
        
        # More agreeing strategies increases confidence
        if 'Buy_Count' in df.columns and 'Strategy_Count' in df.columns:
            latest_buy = df['Buy_Count'].iloc[-1]
            latest_sell = df['Sell_Count'].iloc[-1]
            total_strategies = df['Strategy_Count'].iloc[-1]
            
            if latest_buy > 0 or latest_sell > 0:
                agreement = max(latest_buy, latest_sell) / total_strategies
                confidence += int(agreement * 40)  # Up to 40 points from strategy agreement
        
        # Strong RSI readings increase confidence
        if 'RSI' in df.columns:
            rsi = df['RSI'].iloc[-1]
            if rsi < 30 or rsi > 70:
                confidence += 10  # Very oversold or overbought
            elif rsi < 40 or rsi > 60:
                confidence += 5  # Moderately oversold or overbought
        
        # Price near Bollinger Bands increases confidence
        if all(col in df.columns for col in ['Close', 'BB_Upper', 'BB_Lower']):
            close = df['Close'].iloc[-1]
            upper = df['BB_Upper'].iloc[-1]
            lower = df['BB_Lower'].iloc[-1]
            
            # If price is near the bands
            upper_distance = (upper - close) / close
            lower_distance = (close - lower) / close
            
            if upper_distance < 0.02 or lower_distance < 0.02:
                confidence += 10  # Very close to bands
        
        return min(max(confidence, 0), 100)  # Ensure between 0-100
    
    def _calculate_price_targets(self, df: pd.DataFrame) -> Dict[str, float]:
        """
        Calculate potential price targets based on Fibonacci extensions
        """
        # Get recent high and low
        period = min(90, len(df))
        recent_df = df.iloc[-period:]
        
        recent_high = recent_df['High'].max()
        recent_low = recent_df['Low'].min()
        latest_close = df['Close'].iloc[-1]
        
        # Calculate range
        range_size = recent_high - recent_low
        
        # If we're in an uptrend (close > middle of range)
        if latest_close > (recent_low + range_size / 2):
            targets = {
                'target_1': round(recent_high + 0.382 * range_size, 2),  # 38.2% extension
                'target_2': round(recent_high + 0.618 * range_size, 2),  # 61.8% extension
                'target_3': round(recent_high + 1.0 * range_size, 2)     # 100% extension
            }
        else:  # Downtrend
            targets = {
                'target_1': round(recent_low - 0.382 * range_size, 2),  # 38.2% extension
                'target_2': round(recent_low - 0.618 * range_size, 2),  # 61.8% extension
                'target_3': round(recent_low - 1.0 * range_size, 2)     # 100% extension
            }
        
        return targets
    
    def _determine_time_frame(self, df: pd.DataFrame) -> str:
        """
        Determine expected time frame for the prediction
        """
        # Calculate volatility (standard deviation of returns)
        returns = df['Close'].pct_change().dropna()
        volatility = returns.std()
        
        # Volume trend
        recent_vol = df['Volume'].iloc[-5:].mean()
        older_vol = df['Volume'].iloc[-20:-5].mean()
        vol_change = recent_vol / older_vol if older_vol > 0 else 1
        
        # Determine time frame based on indicators
        if volatility > 0.025 or vol_change > 1.5:  # High volatility or increasing volume
            return "Short-term (1-5 days)"
        elif volatility > 0.015 or vol_change > 1.2:
            return "Medium-term (1-3 weeks)"
        else:
            return "Long-term (1-3 months)"

    def backtest_strategy(self, ticker: str, days: int = 365, strategy: str = 'combined') -> Dict[str, Any]:
        """
        Backtest a trading strategy on historical data
        
        Args:
            ticker: Stock ticker symbol
            days: Number of days to backtest
            strategy: Which strategy to backtest (combined, ma_crossover, rsi, macd, bollinger)
            
        Returns:
            Dict with backtest results
        """
        try:
            # Get stock data
            df = self._get_stock_dataframe(ticker, days)
            
            # Apply the selected strategy
            if strategy == 'ma_crossover':
                df = strategy_moving_average_crossover(df)
                action_col = 'Action'
            elif strategy == 'rsi':
                df = strategy_rsi_oversold_overbought(df)
                action_col = 'Action'
            elif strategy == 'macd':
                df = strategy_macd(df)
                action_col = 'Action'
            elif strategy == 'bollinger':
                df = strategy_bollinger_bands_bounce(df)
                action_col = 'Action'
            else:  # combined
                df = combine_strategies(df)
                action_col = 'Combined_Action'
            
            # Convert to list for processing
            df = df.reset_index()
            
            # Extract buy and sell signals
            buy_signals = df[df[action_col] == 'BUY'][['Date', 'Close']].rename(
                columns={'Date': 'buy_date', 'Close': 'buy_price'}
            ).reset_index(drop=True)
            
            sell_signals = df[df[action_col] == 'SELL'][['Date', 'Close']].rename(
                columns={'Date': 'sell_date', 'Close': 'sell_price'}
            ).reset_index(drop=True)
            
            # Calculate trades
            trades = []
            open_position = False
            entry_price = 0
            
            for i, row in df.iterrows():
                if row[action_col] == 'BUY' and not open_position:
                    open_position = True
                    entry_price = row['Close']
                    entry_date = row['Date']
                
                elif row[action_col] == 'SELL' and open_position:
                    open_position = False
                    exit_price = row['Close']
                    exit_date = row['Date']
                    
                    profit = (exit_price - entry_price) / entry_price * 100
                    trades.append({
                        'entry_date': entry_date.strftime('%Y-%m-%d'),
                        'entry_price': round(entry_price, 2),
                        'exit_date': exit_date.strftime('%Y-%m-%d'),
                        'exit_price': round(exit_price, 2),
                        'profit_pct': round(profit, 2)
                    })
            
            # Calculate statistics
            total_trades = len(trades)
            winning_trades = sum(1 for trade in trades if trade['profit_pct'] > 0)
            
            if total_trades > 0:
                win_rate = winning_trades / total_trades * 100
                avg_profit = sum(trade['profit_pct'] for trade in trades) / total_trades
                max_profit = max([trade['profit_pct'] for trade in trades]) if trades else 0
                max_loss = min([trade['profit_pct'] for trade in trades]) if trades else 0
            else:
                win_rate = 0
                avg_profit = 0
                max_profit = 0
                max_loss = 0
            
            return {
                'ticker': ticker,
                'strategy': strategy,
                'period': f"{days} days",
                'trades': trades,
                'statistics': {
                    'total_trades': total_trades,
                    'winning_trades': winning_trades,
                    'win_rate': round(win_rate, 2),
                    'avg_profit': round(avg_profit, 2),
                    'max_profit': round(max_profit, 2),
                    'max_loss': round(max_loss, 2),
                }
            }
            
        except Exception as e:
            logger.error(f"Error backtesting {strategy} strategy for {ticker}: {str(e)}")
            return {
                'ticker': ticker,
                'strategy': strategy,
                'error': str(e),
                'status': 'failed'
            }

def get_prediction_service(db: Session) -> StockPredictionService:
    """Get prediction service instance"""
    return StockPredictionService(db)
