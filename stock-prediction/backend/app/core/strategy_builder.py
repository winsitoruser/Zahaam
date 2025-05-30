"""
Custom strategy builder and executor for user-defined trading strategies
"""
import pandas as pd
import numpy as np
import logging
from typing import Dict, List, Any, Optional
from datetime import datetime

from app.core.trading_strategies import (
    calculate_moving_averages,
    calculate_exponential_moving_averages,
    calculate_rsi,
    calculate_macd,
    calculate_bollinger_bands,
    calculate_stochastic_oscillator,
    get_stop_loss_level,
    get_take_profit_level
)

logger = logging.getLogger(__name__)

class StrategyBuilder:
    """Builder class for constructing and executing user-defined trading strategies"""
    
    def __init__(self):
        self.available_indicators = {
            "sma": calculate_moving_averages,
            "ema": calculate_exponential_moving_averages,
            "rsi": calculate_rsi,
            "macd": calculate_macd,
            "bollinger": calculate_bollinger_bands,
            "stochastic": calculate_stochastic_oscillator
        }
        
        self.available_conditions = {
            "greater_than": self._condition_greater_than,
            "less_than": self._condition_less_than,
            "crosses_above": self._condition_crosses_above,
            "crosses_below": self._condition_crosses_below,
            "percent_change": self._condition_percent_change,
            "in_range": self._condition_in_range
        }
    
    def _condition_greater_than(self, df, indicator1, indicator2=None, value=None):
        """Check if indicator1 > indicator2 or value"""
        if indicator2:
            return df[indicator1] > df[indicator2]
        return df[indicator1] > value
    
    def _condition_less_than(self, df, indicator1, indicator2=None, value=None):
        """Check if indicator1 < indicator2 or value"""
        if indicator2:
            return df[indicator1] < df[indicator2]
        return df[indicator1] < value
    
    def _condition_crosses_above(self, df, indicator1, indicator2):
        """Check if indicator1 crosses above indicator2"""
        return (df[indicator1] > df[indicator2]) & (df[indicator1].shift(1) <= df[indicator2].shift(1))
    
    def _condition_crosses_below(self, df, indicator1, indicator2):
        """Check if indicator1 crosses below indicator2"""
        return (df[indicator1] < df[indicator2]) & (df[indicator1].shift(1) >= df[indicator2].shift(1))
    
    def _condition_percent_change(self, df, indicator, threshold, direction="up"):
        """Check if indicator has changed by more than threshold percent"""
        pct_change = df[indicator].pct_change() * 100
        if direction == "up":
            return pct_change > threshold
        return pct_change < -threshold
    
    def _condition_in_range(self, df, indicator, lower, upper):
        """Check if indicator is between lower and upper values"""
        return (df[indicator] >= lower) & (df[indicator] <= upper)
    
    def apply_indicators(self, df, strategy_params):
        """Apply technical indicators specified in the strategy params"""
        # Make a copy of the dataframe to avoid modifying the original
        df = df.copy()
        
        # Process each indicator group
        indicators = strategy_params.get("indicators", [])
        for indicator in indicators:
            indicator_type = indicator.get("type")
            params = indicator.get("params", {})
            
            if indicator_type in self.available_indicators:
                # Call the indicator function with the dataframe and params
                df = self.available_indicators[indicator_type](df, **params)
        
        return df
    
    def apply_conditions(self, df, conditions):
        """Apply buy or sell conditions to the dataframe"""
        signals = pd.Series(False, index=df.index)
        
        # Process each condition as an AND by default
        for condition in conditions:
            condition_type = condition.get("type")
            params = condition.get("params", {})
            
            if condition_type in self.available_conditions:
                # Apply the condition function
                condition_result = self.available_conditions[condition_type](df, **params)
                
                # Combine with existing signals based on operation
                operation = condition.get("operation", "and")
                if operation.lower() == "and":
                    signals = signals & condition_result
                elif operation.lower() == "or":
                    signals = signals | condition_result
                elif operation.lower() == "initial":
                    signals = condition_result
        
        return signals
    
    def execute_strategy(self, df, strategy_params):
        """Execute a user-defined strategy on historical data"""
        try:
            # Apply indicators first
            df = self.apply_indicators(df, strategy_params)
            
            # Apply buy and sell conditions
            buy_signals = self.apply_conditions(df, strategy_params.get("buy_conditions", []))
            sell_signals = self.apply_conditions(df, strategy_params.get("sell_conditions", []))
            
            # Create new columns for signals
            df["Buy_Signal"] = buy_signals
            df["Sell_Signal"] = sell_signals
            
            # Set Action based on signals
            df["Action"] = None
            df.loc[buy_signals, "Action"] = "BUY"
            df.loc[sell_signals, "Action"] = "SELL"
            
            return df
            
        except Exception as e:
            logger.error(f"Error executing strategy: {str(e)}")
            raise
    
    def backtest_strategy(self, df, strategy_params):
        """Backtest a user-defined strategy on historical data"""
        # Apply strategy to get signals
        df = self.execute_strategy(df, strategy_params)
        
        # Calculate trades and performance
        trades = []
        open_position = False
        entry_price = 0
        entry_date = None
        
        for i, row in df.iterrows():
            if row["Action"] == "BUY" and not open_position:
                open_position = True
                entry_price = row["Close"]
                entry_date = row.name  # Index is the date
                
            elif row["Action"] == "SELL" and open_position:
                open_position = False
                exit_price = row["Close"]
                exit_date = row.name
                
                profit = (exit_price - entry_price) / entry_price * 100
                trades.append({
                    "entry_date": entry_date.strftime("%Y-%m-%d") if hasattr(entry_date, "strftime") else str(entry_date),
                    "entry_price": round(entry_price, 2),
                    "exit_date": exit_date.strftime("%Y-%m-%d") if hasattr(exit_date, "strftime") else str(exit_date),
                    "exit_price": round(exit_price, 2),
                    "profit_pct": round(profit, 2)
                })
        
        # Calculate performance metrics
        total_trades = len(trades)
        winning_trades = sum(1 for trade in trades if trade["profit_pct"] > 0)
        
        if total_trades > 0:
            win_rate = winning_trades / total_trades * 100
            avg_profit = sum(trade["profit_pct"] for trade in trades) / total_trades
            
            # Calculate max drawdown
            profits = [trade["profit_pct"] for trade in trades]
            cumulative = np.cumsum(profits)
            max_drawdown = 0
            peak = 0
            
            for value in cumulative:
                if value > peak:
                    peak = value
                drawdown = peak - value
                max_drawdown = max(max_drawdown, drawdown)
        else:
            win_rate = 0
            avg_profit = 0
            max_drawdown = 0
        
        return {
            "trades": trades,
            "statistics": {
                "total_trades": total_trades,
                "winning_trades": winning_trades,
                "win_rate": round(win_rate, 2) if total_trades > 0 else 0,
                "avg_profit": round(avg_profit, 2) if total_trades > 0 else 0,
                "max_drawdown": round(max_drawdown, 2) if max_drawdown else 0,
                "profit_factor": round(sum(max(0, t["profit_pct"]) for t in trades) / abs(sum(min(0, t["profit_pct"]) for t in trades)), 2) if sum(min(0, t["profit_pct"]) for t in trades) != 0 else 0
            }
        }
    
    def generate_prediction(self, df, strategy_params):
        """Generate prediction using a user-defined strategy"""
        # Apply strategy to get signals
        df = self.execute_strategy(df, strategy_params)
        
        # Get latest price and signals
        latest_price = df["Close"].iloc[-1]
        latest_action = df["Action"].iloc[-1]
        
        # Calculate stop loss and take profit if have buy signal
        entry_price = latest_price
        stop_loss = None
        take_profit = None
        
        if latest_action == "BUY":
            # Get stop loss method and value
            stop_loss_method = strategy_params.get("stop_loss_method", "percentage")
            stop_loss_value = strategy_params.get("stop_loss_value", 2.0)
            
            # Calculate stop loss
            if stop_loss_method == "atr":
                stop_loss = get_stop_loss_level(df, entry_price, "atr", atr_multiple=stop_loss_value)
            elif stop_loss_method == "support":
                stop_loss = get_stop_loss_level(df, entry_price, "support")
            else:  # percentage
                stop_loss = entry_price * (1 - stop_loss_value / 100)
            
            # Calculate take profit
            take_profit_method = strategy_params.get("take_profit_method", "risk_reward")
            take_profit_value = strategy_params.get("take_profit_value", 2.0)
            
            if take_profit_method == "risk_reward":
                take_profit = get_take_profit_level(entry_price, stop_loss, take_profit_value)
            else:  # percentage
                take_profit = entry_price * (1 + take_profit_value / 100)
        
        # Build the prediction response
        prediction = {
            "latest_price": round(latest_price, 2),
            "prediction_date": datetime.now().strftime("%Y-%m-%d"),
            "action": latest_action if pd.notna(latest_action) else "HOLD"
        }
        
        # If there's a buy signal, add entry point details
        if latest_action == "BUY":
            prediction["entry_point"] = {
                "price": round(entry_price, 2),
                "date": datetime.now().strftime("%Y-%m-%d"),
                "stop_loss": round(stop_loss, 2) if stop_loss else None,
                "take_profit": round(take_profit, 2) if take_profit else None
            }
            
            prediction["recommendation"] = (
                f"BUY at {round(entry_price, 2)}. "
                f"Set stop loss at {round(stop_loss, 2) if stop_loss else 'N/A'} and "
                f"take profit at {round(take_profit, 2) if take_profit else 'N/A'}."
            )
            
        # For sell signals
        elif latest_action == "SELL":
            prediction["exit_point"] = {
                "price": round(entry_price, 2),
                "date": datetime.now().strftime("%Y-%m-%d")
            }
            prediction["recommendation"] = f"SELL at {round(entry_price, 2)}."
        
        # No clear signal
        else:
            prediction["recommendation"] = "HOLD. No clear entry or exit signal at the moment."
        
        return prediction
