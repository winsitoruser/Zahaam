"""
API routes for strategy backtesting
"""
from fastapi import APIRouter, Depends, HTTPException, Query
from typing import List, Dict, Any, Optional
from sqlalchemy.orm import Session
from datetime import datetime, timedelta
import pandas as pd
import numpy as np

from app.core.database import get_db
from app.models.stocks import Stock, StockPrice
from app.core.security import get_current_active_user

router = APIRouter(tags=["backtesting"])

@router.get("/backtest")
async def backtest_strategy(
    strategy_id: str,
    ticker: str,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
    initial_capital: float = 10000.0,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Backtest a trading strategy on historical data
    
    Parameters:
    - strategy_id: ID of the strategy to backtest
    - ticker: Stock ticker symbol
    - start_date: Start date for backtest (YYYY-MM-DD)
    - end_date: End date for backtest (YYYY-MM-DD)
    - initial_capital: Initial capital for backtest
    """
    try:
        # Check if stock exists
        stock = db.query(Stock).filter(Stock.ticker == ticker).first()
        if not stock:
            raise HTTPException(status_code=404, detail=f"Stock {ticker} not found")
        
        # Parse dates
        today = datetime.now().date()
        if end_date:
            end = datetime.strptime(end_date, "%Y-%m-%d").date()
        else:
            end = today
            
        if start_date:
            start = datetime.strptime(start_date, "%Y-%m-%d").date()
        else:
            # Default to 1 year lookback if not specified
            start = end - timedelta(days=365)
        
        # Get historical price data
        prices = db.query(StockPrice).filter(
            StockPrice.stock_id == stock.id,
            StockPrice.date >= start,
            StockPrice.date <= end
        ).order_by(StockPrice.date).all()
        
        if not prices:
            raise HTTPException(
                status_code=404, 
                detail=f"No price data found for {ticker} between {start} and {end}"
            )
            
        # Convert to pandas DataFrame
        price_data = pd.DataFrame([{
            'date': p.date,
            'open': p.open,
            'high': p.high,
            'low': p.low,
            'close': p.close,
            'volume': p.volume
        } for p in prices])
        
        # Fetch the strategy details - this would typically come from a database
        # For now, we'll simulate with predefined strategies
        strategy_params = get_strategy_params(strategy_id)
        
        # Run the backtest
        results = run_backtest(price_data, strategy_params, initial_capital)
        
        return {
            'status': 'success',
            'ticker': ticker,
            'strategy_id': strategy_id,
            'strategy_name': strategy_params.get('name', 'Unknown Strategy'),
            'period': {
                'start': start.strftime("%Y-%m-%d"),
                'end': end.strftime("%Y-%m-%d"),
                'days': (end - start).days
            },
            'initial_capital': initial_capital,
            'results': results
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Backtest error: {str(e)}")

def get_strategy_params(strategy_id: str) -> Dict:
    """
    Get strategy parameters based on strategy ID
    In a real implementation, this would query the database
    """
    mock_strategies = {
        "strat_001": {
            "id": "strat_001",
            "name": "Golden Cross",
            "description": "SMA(50) crosses above SMA(200)",
            "type": "technical",
            "parameters": {
                "fast_ma": 50,
                "slow_ma": 200,
                "exit_strategy": "sma_cross_below"
            }
        },
        "strat_002": {
            "id": "strat_002",
            "name": "RSI Oversold + Support",
            "description": "Buy when RSI < 30 and price near support level",
            "type": "technical",
            "parameters": {
                "rsi_period": 14,
                "rsi_threshold": 30,
                "support_tolerance": 2.5,
                "exit_strategy": "rsi_above_70"
            }
        },
        "strat_003": {
            "id": "strat_003",
            "name": "AI Prediction Follow",
            "description": "Follow AI prediction signals",
            "type": "ai",
            "parameters": {
                "confidence_threshold": 75,
                "min_expected_profit": 5.0,
                "exit_strategy": "target_profit_or_stop_loss",
                "target_profit_percent": 10,
                "stop_loss_percent": 5
            }
        }
    }
    
    return mock_strategies.get(strategy_id, {"name": "Custom Strategy", "parameters": {}})

def run_backtest(price_data: pd.DataFrame, strategy_params: Dict, initial_capital: float) -> Dict:
    """
    Run backtest simulation based on strategy parameters and price data
    """
    # Initialize results
    trades = []
    portfolio_value = []
    cash = initial_capital
    shares = 0
    
    # Get strategy type
    strategy_type = strategy_params.get('type', 'technical')
    params = strategy_params.get('parameters', {})
    
    # Generate signals based on strategy type
    signals = generate_signals(price_data, strategy_type, params)
    
    # Simulate trading
    for i in range(len(price_data)):
        date = price_data.iloc[i]['date']
        close_price = price_data.iloc[i]['close']
        
        # Check for buy signal
        if signals[i] == 1 and cash > 0:
            # Buy as many shares as possible
            max_shares = int(cash / close_price)
            if max_shares > 0:
                shares += max_shares
                cost = max_shares * close_price
                cash -= cost
                
                trades.append({
                    'date': date.strftime("%Y-%m-%d"),
                    'type': 'BUY',
                    'shares': max_shares,
                    'price': close_price,
                    'cost': cost,
                    'remaining_cash': cash
                })
        
        # Check for sell signal
        elif signals[i] == -1 and shares > 0:
            # Sell all shares
            proceeds = shares * close_price
            cash += proceeds
            
            trades.append({
                'date': date.strftime("%Y-%m-%d"),
                'type': 'SELL',
                'shares': shares,
                'price': close_price,
                'proceeds': proceeds,
                'remaining_cash': cash
            })
            
            shares = 0
        
        # Calculate portfolio value
        portfolio_value.append({
            'date': date.strftime("%Y-%m-%d"),
            'close': close_price,
            'shares': shares,
            'cash': cash,
            'value': cash + (shares * close_price)
        })
    
    # Calculate performance metrics
    if not portfolio_value:
        return {
            'trades': [],
            'portfolio_history': [],
            'metrics': {
                'total_return': 0,
                'total_return_percent': 0,
                'annualized_return': 0,
                'max_drawdown': 0,
                'win_rate': 0,
                'total_trades': 0
            }
        }
    
    start_value = initial_capital
    end_value = portfolio_value[-1]['value']
    total_return = end_value - start_value
    total_return_percent = (total_return / start_value) * 100
    
    # Calculate max drawdown
    max_value = start_value
    max_drawdown = 0
    
    for pv in portfolio_value:
        if pv['value'] > max_value:
            max_value = pv['value']
        drawdown = (max_value - pv['value']) / max_value * 100
        max_drawdown = max(max_drawdown, drawdown)
    
    # Calculate win rate
    winning_trades = 0
    current_buy = None
    
    for trade in trades:
        if trade['type'] == 'BUY':
            current_buy = trade
        elif trade['type'] == 'SELL' and current_buy:
            if trade['price'] > current_buy['price']:
                winning_trades += 1
            current_buy = None
    
    win_rate = (winning_trades / (len(trades) // 2)) * 100 if trades and len(trades) >= 2 else 0
    
    # Calculate annualized return
    days = (price_data.iloc[-1]['date'] - price_data.iloc[0]['date']).days
    annualized_return = ((1 + (total_return / start_value)) ** (365 / days) - 1) * 100 if days > 0 else 0
    
    return {
        'trades': trades,
        'portfolio_history': portfolio_value[::max(1, len(portfolio_value)//100)],  # Downsample to ~100 points
        'metrics': {
            'total_return': round(total_return, 2),
            'total_return_percent': round(total_return_percent, 2),
            'annualized_return': round(annualized_return, 2),
            'max_drawdown': round(max_drawdown, 2),
            'win_rate': round(win_rate, 2),
            'total_trades': len(trades),
            'profitable_trades': winning_trades,
            'loss_making_trades': (len(trades) // 2) - winning_trades
        }
    }

def generate_signals(price_data: pd.DataFrame, strategy_type: str, params: Dict) -> List[int]:
    """
    Generate trading signals based on strategy parameters
    0 = Hold, 1 = Buy, -1 = Sell
    """
    signals = [0] * len(price_data)
    
    if strategy_type == 'technical':
        # Implement technical strategy signals
        if 'fast_ma' in params and 'slow_ma' in params:
            # Golden Cross / Death Cross strategy
            fast_period = params.get('fast_ma', 50)
            slow_period = params.get('slow_ma', 200)
            
            # Calculate moving averages
            price_data['fast_ma'] = price_data['close'].rolling(window=fast_period).mean()
            price_data['slow_ma'] = price_data['close'].rolling(window=slow_period).mean()
            
            # Generate signals
            for i in range(1, len(price_data)):
                if pd.notna(price_data.iloc[i]['fast_ma']) and pd.notna(price_data.iloc[i]['slow_ma']):
                    # Buy when fast crosses above slow (Golden Cross)
                    if (price_data.iloc[i-1]['fast_ma'] <= price_data.iloc[i-1]['slow_ma'] and
                        price_data.iloc[i]['fast_ma'] > price_data.iloc[i]['slow_ma']):
                        signals[i] = 1
                    
                    # Sell when fast crosses below slow (Death Cross)
                    elif (price_data.iloc[i-1]['fast_ma'] >= price_data.iloc[i-1]['slow_ma'] and
                          price_data.iloc[i]['fast_ma'] < price_data.iloc[i]['slow_ma']):
                        signals[i] = -1
        
        elif 'rsi_period' in params and 'rsi_threshold' in params:
            # RSI strategy
            rsi_period = params.get('rsi_period', 14)
            oversold = params.get('rsi_threshold', 30)
            overbought = params.get('overbought_threshold', 70)
            
            # Calculate RSI
            delta = price_data['close'].diff()
            gain = (delta.where(delta > 0, 0)).rolling(window=rsi_period).mean()
            loss = (-delta.where(delta < 0, 0)).rolling(window=rsi_period).mean()
            
            rs = gain / loss
            price_data['rsi'] = 100 - (100 / (1 + rs))
            
            # Generate signals
            for i in range(1, len(price_data)):
                if pd.notna(price_data.iloc[i]['rsi']):
                    # Buy when RSI crosses below oversold threshold
                    if (price_data.iloc[i-1]['rsi'] >= oversold and
                        price_data.iloc[i]['rsi'] < oversold):
                        signals[i] = 1
                    
                    # Sell when RSI crosses above overbought threshold
                    elif (price_data.iloc[i-1]['rsi'] <= overbought and
                          price_data.iloc[i]['rsi'] > overbought):
                        signals[i] = -1
    
    elif strategy_type == 'ai':
        # Simulate AI strategy with random signals for demonstration
        # In a real implementation, this would use an actual AI model
        import random
        
        # Generate signals with slight upward bias
        for i in range(len(price_data)):
            if i > 0 and signals[i-1] != 0:
                # After a trade, wait a few days
                continue
                
            rand_val = random.random()
            if rand_val > 0.95:  # 5% chance of buy signal
                signals[i] = 1
            elif rand_val < 0.03:  # 3% chance of sell signal
                signals[i] = -1
    
    return signals

@router.post("/backtest/export")
async def export_backtest_results(
    backtest_id: str,
    format: str = Query("pdf", description="Export format: pdf, csv, or json"),
    _: Dict = Depends(get_current_active_user)
):
    """
    Export backtest results
    
    Parameters:
    - backtest_id: ID of the backtest to export
    - format: Export format (pdf, csv, json)
    """
    # In a production app, we would retrieve backtest results and export them
    # For now, just return a success message with download URL
    
    valid_formats = ["pdf", "csv", "json"]
    if format not in valid_formats:
        raise HTTPException(status_code=400, detail=f"Invalid format. Must be one of: {', '.join(valid_formats)}")
    
    return {
        "status": "success",
        "message": f"Backtest results exported as {format.upper()}",
        "download_url": f"/api/backtest/download/{backtest_id}.{format}"
    }

@router.get("/backtest/compare")
async def compare_backtests(
    backtest_ids: List[str],
    _: Dict = Depends(get_current_active_user)
):
    """
    Compare multiple backtest results
    
    Parameters:
    - backtest_ids: List of backtest IDs to compare
    """
    # In a production app, we would retrieve multiple backtest results and compare them
    # For now, return mock comparison data
    
    if not backtest_ids:
        raise HTTPException(status_code=400, detail="No backtest IDs provided")
    
    # Mock comparison data
    comparisons = []
    for i, backtest_id in enumerate(backtest_ids):
        comparisons.append({
            "backtest_id": backtest_id,
            "strategy_name": f"Strategy {i+1}",
            "total_return": 15.2 + i * 2.5,
            "annualized_return": 8.7 + i * 1.2,
            "sharpe_ratio": 1.2 + i * 0.3,
            "max_drawdown": 12.5 - i * 0.8,
            "win_rate": 65 + i * 3,
            "total_trades": 42 + i * 5
        })
    
    return {
        "status": "success",
        "comparisons": comparisons
    }
