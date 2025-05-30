"""
API routes for user custom strategies
"""
from fastapi import APIRouter, HTTPException, Depends, Query, Path, Body
from typing import List, Dict, Any, Optional
from sqlalchemy.orm import Session
import json
from datetime import datetime
import pandas as pd

from app.core.database import get_db
from app.models.user_strategies import User, UserStrategy
from app.core.strategy_builder import StrategyBuilder
from app.models.stocks import Stock, StockPrice

router = APIRouter(prefix="/api", tags=["user_strategies"])

# Mock user ID for demo (in production, get from authentication)
MOCK_USER_ID = 1

@router.get("/strategies")
async def get_user_strategies(
    db: Session = Depends(get_db)
):
    """
    Get all strategies for the current user
    """
    # In production, get user ID from auth token
    user_id = MOCK_USER_ID
    
    # Check if user exists
    user = db.query(User).filter(User.id == user_id).first()
    if not user:
        # Create mock user for demo
        user = User(
            id=MOCK_USER_ID,
            username="demo_user",
            email="demo@example.com",
            hashed_password="hashed_demo_password",
        )
        db.add(user)
        db.commit()
        db.refresh(user)
    
    # Get strategies
    strategies = db.query(UserStrategy).filter(UserStrategy.user_id == user_id).all()
    
    return {
        "strategies": [
            {
                "id": strategy.id,
                "name": strategy.name,
                "description": strategy.description,
                "created_at": strategy.created_at,
                "updated_at": strategy.updated_at,
                "is_public": strategy.is_public,
                "win_rate": strategy.win_rate,
                "avg_profit": strategy.avg_profit,
                "total_trades": strategy.total_trades
            }
            for strategy in strategies
        ]
    }

@router.post("/strategy")
async def create_strategy(
    strategy_data: Dict[str, Any] = Body(...),
    db: Session = Depends(get_db)
):
    """
    Create a new custom trading strategy
    """
    user_id = MOCK_USER_ID
    
    # Check if user exists
    user = db.query(User).filter(User.id == user_id).first()
    if not user:
        raise HTTPException(status_code=404, detail="User not found")
    
    try:
        # Create new strategy
        new_strategy = UserStrategy(
            name=strategy_data.get("name", "Unnamed Strategy"),
            description=strategy_data.get("description", ""),
            user_id=user_id,
            strategy_params=strategy_data.get("strategy_params", {}),
            buy_conditions=strategy_data.get("buy_conditions", []),
            sell_conditions=strategy_data.get("sell_conditions", []),
            stop_loss_method=strategy_data.get("stop_loss_method", "percentage"),
            stop_loss_value=strategy_data.get("stop_loss_value", 2.0),
            take_profit_method=strategy_data.get("take_profit_method", "risk_reward"),
            take_profit_value=strategy_data.get("take_profit_value", 2.0),
            is_public=strategy_data.get("is_public", False)
        )
        
        db.add(new_strategy)
        db.commit()
        db.refresh(new_strategy)
        
        return {
            "message": "Strategy created successfully",
            "strategy_id": new_strategy.id,
            "name": new_strategy.name
        }
        
    except Exception as e:
        db.rollback()
        raise HTTPException(status_code=400, detail=f"Error creating strategy: {str(e)}")

@router.get("/strategy/{strategy_id}")
async def get_strategy_details(
    strategy_id: int = Path(...),
    db: Session = Depends(get_db)
):
    """
    Get details of a specific strategy
    """
    user_id = MOCK_USER_ID
    
    # Get the strategy
    strategy = db.query(UserStrategy).filter(
        UserStrategy.id == strategy_id,
        UserStrategy.user_id == user_id
    ).first()
    
    if not strategy:
        raise HTTPException(status_code=404, detail="Strategy not found")
    
    return {
        "id": strategy.id,
        "name": strategy.name,
        "description": strategy.description,
        "created_at": strategy.created_at,
        "updated_at": strategy.updated_at,
        "is_public": strategy.is_public,
        "strategy_params": strategy.strategy_params,
        "buy_conditions": strategy.buy_conditions,
        "sell_conditions": strategy.sell_conditions,
        "stop_loss_method": strategy.stop_loss_method,
        "stop_loss_value": strategy.stop_loss_value,
        "take_profit_method": strategy.take_profit_method,
        "take_profit_value": strategy.take_profit_value,
        "performance": {
            "win_rate": strategy.win_rate,
            "avg_profit": strategy.avg_profit,
            "max_drawdown": strategy.max_drawdown,
            "total_trades": strategy.total_trades
        }
    }

@router.put("/strategy/{strategy_id}")
async def update_strategy(
    strategy_id: int = Path(...),
    strategy_data: Dict[str, Any] = Body(...),
    db: Session = Depends(get_db)
):
    """
    Update an existing strategy
    """
    user_id = MOCK_USER_ID
    
    # Get the strategy
    strategy = db.query(UserStrategy).filter(
        UserStrategy.id == strategy_id,
        UserStrategy.user_id == user_id
    ).first()
    
    if not strategy:
        raise HTTPException(status_code=404, detail="Strategy not found")
    
    try:
        # Update fields
        strategy.name = strategy_data.get("name", strategy.name)
        strategy.description = strategy_data.get("description", strategy.description)
        strategy.strategy_params = strategy_data.get("strategy_params", strategy.strategy_params)
        strategy.buy_conditions = strategy_data.get("buy_conditions", strategy.buy_conditions)
        strategy.sell_conditions = strategy_data.get("sell_conditions", strategy.sell_conditions)
        strategy.stop_loss_method = strategy_data.get("stop_loss_method", strategy.stop_loss_method)
        strategy.stop_loss_value = strategy_data.get("stop_loss_value", strategy.stop_loss_value)
        strategy.take_profit_method = strategy_data.get("take_profit_method", strategy.take_profit_method)
        strategy.take_profit_value = strategy_data.get("take_profit_value", strategy.take_profit_value)
        strategy.is_public = strategy_data.get("is_public", strategy.is_public)
        strategy.updated_at = datetime.now()
        
        db.commit()
        db.refresh(strategy)
        
        return {
            "message": "Strategy updated successfully",
            "strategy_id": strategy.id,
            "name": strategy.name
        }
        
    except Exception as e:
        db.rollback()
        raise HTTPException(status_code=400, detail=f"Error updating strategy: {str(e)}")

@router.delete("/strategy/{strategy_id}")
async def delete_strategy(
    strategy_id: int = Path(...),
    db: Session = Depends(get_db)
):
    """
    Delete a strategy
    """
    user_id = MOCK_USER_ID
    
    # Get the strategy
    strategy = db.query(UserStrategy).filter(
        UserStrategy.id == strategy_id,
        UserStrategy.user_id == user_id
    ).first()
    
    if not strategy:
        raise HTTPException(status_code=404, detail="Strategy not found")
    
    try:
        db.delete(strategy)
        db.commit()
        
        return {
            "message": "Strategy deleted successfully",
            "strategy_id": strategy_id
        }
        
    except Exception as e:
        db.rollback()
        raise HTTPException(status_code=400, detail=f"Error deleting strategy: {str(e)}")

@router.post("/strategy/{strategy_id}/backtest/{ticker}")
async def backtest_user_strategy(
    strategy_id: int = Path(...),
    ticker: str = Path(...),
    days: int = Query(365, description="Days of historical data to use"),
    db: Session = Depends(get_db)
):
    """
    Backtest a user strategy on historical data
    """
    user_id = MOCK_USER_ID
    
    # Get the strategy
    strategy = db.query(UserStrategy).filter(
        UserStrategy.id == strategy_id,
        UserStrategy.user_id == user_id
    ).first()
    
    if not strategy:
        raise HTTPException(status_code=404, detail="Strategy not found")
    
    # Get the stock
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
    
    try:
        # Get historical data
        from datetime import datetime, timedelta
        end_date = datetime.now()
        start_date = end_date - timedelta(days=days)
        
        prices = (
            db.query(StockPrice)
            .filter(StockPrice.stock_id == stock.id)
            .filter(StockPrice.date >= start_date)
            .filter(StockPrice.date <= end_date)
            .order_by(StockPrice.date)
            .all()
        )
        
        if not prices:
            raise HTTPException(status_code=404, detail="No historical data found for this stock")
        
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
        
        # Get strategy parameters
        strategy_params = {
            "indicators": strategy.strategy_params.get("indicators", []),
            "buy_conditions": strategy.buy_conditions,
            "sell_conditions": strategy.sell_conditions,
            "stop_loss_method": strategy.stop_loss_method,
            "stop_loss_value": strategy.stop_loss_value,
            "take_profit_method": strategy.take_profit_method,
            "take_profit_value": strategy.take_profit_value
        }
        
        # Run backtest
        strategy_builder = StrategyBuilder()
        backtest_result = strategy_builder.backtest_strategy(df, strategy_params)
        
        # Update strategy performance metrics
        strategy.win_rate = backtest_result["statistics"]["win_rate"]
        strategy.avg_profit = backtest_result["statistics"]["avg_profit"]
        strategy.max_drawdown = backtest_result["statistics"]["max_drawdown"]
        strategy.total_trades = backtest_result["statistics"]["total_trades"]
        db.commit()
        
        # Return backtest results
        return {
            "ticker": ticker,
            "strategy_name": strategy.name,
            "period": f"{days} days",
            "trades": backtest_result["trades"],
            "statistics": backtest_result["statistics"],
            "message": "Backtest completed successfully"
        }
        
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Error running backtest: {str(e)}")

@router.post("/strategy/{strategy_id}/predict/{ticker}")
async def predict_with_strategy(
    strategy_id: int = Path(...),
    ticker: str = Path(...),
    days: int = Query(365, description="Days of historical data to use"),
    db: Session = Depends(get_db)
):
    """
    Generate prediction using a custom strategy
    """
    user_id = MOCK_USER_ID
    
    # Get the strategy
    strategy = db.query(UserStrategy).filter(
        UserStrategy.id == strategy_id,
        UserStrategy.user_id == user_id
    ).first()
    
    if not strategy:
        raise HTTPException(status_code=404, detail="Strategy not found")
    
    # Get the stock
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail="Stock not found")
    
    try:
        # Get historical data
        from datetime import datetime, timedelta
        end_date = datetime.now()
        start_date = end_date - timedelta(days=days)
        
        prices = (
            db.query(StockPrice)
            .filter(StockPrice.stock_id == stock.id)
            .filter(StockPrice.date >= start_date)
            .filter(StockPrice.date <= end_date)
            .order_by(StockPrice.date)
            .all()
        )
        
        if not prices:
            raise HTTPException(status_code=404, detail="No historical data found for this stock")
        
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
        
        # Get strategy parameters
        strategy_params = {
            "indicators": strategy.strategy_params.get("indicators", []),
            "buy_conditions": strategy.buy_conditions,
            "sell_conditions": strategy.sell_conditions,
            "stop_loss_method": strategy.stop_loss_method,
            "stop_loss_value": strategy.stop_loss_value,
            "take_profit_method": strategy.take_profit_method,
            "take_profit_value": strategy.take_profit_value
        }
        
        # Generate prediction
        strategy_builder = StrategyBuilder()
        prediction = strategy_builder.generate_prediction(df, strategy_params)
        
        # Add additional info
        prediction.update({
            "ticker": ticker,
            "strategy_name": strategy.name,
            "strategy_id": strategy.id,
            "stock_info": {
                "name": stock.name,
                "sector": stock.sector
            }
        })
        
        return prediction
        
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Error generating prediction: {str(e)}")
    
@router.get("/strategy/templates")
async def get_strategy_templates():
    """
    Get predefined strategy templates
    """
    return {
        "templates": [
            {
                "name": "Golden Cross Strategy",
                "description": "Buy when SMA-50 crosses above SMA-200, sell when SMA-50 crosses below SMA-200",
                "strategy_params": {
                    "indicators": [
                        {"type": "sma", "params": {"periods": [50, 200]}}
                    ]
                },
                "buy_conditions": [
                    {"type": "crosses_above", "params": {"indicator1": "SMA_50", "indicator2": "SMA_200"}}
                ],
                "sell_conditions": [
                    {"type": "crosses_below", "params": {"indicator1": "SMA_50", "indicator2": "SMA_200"}}
                ],
                "stop_loss_method": "percentage",
                "stop_loss_value": 5.0,
                "take_profit_method": "risk_reward",
                "take_profit_value": 2.0
            },
            {
                "name": "RSI Strategy",
                "description": "Buy when RSI crosses above 30 (oversold), sell when RSI crosses below 70 (overbought)",
                "strategy_params": {
                    "indicators": [
                        {"type": "rsi", "params": {"period": 14}}
                    ]
                },
                "buy_conditions": [
                    {"type": "crosses_above", "params": {"indicator1": "RSI", "indicator2": None, "value": 30}}
                ],
                "sell_conditions": [
                    {"type": "crosses_below", "params": {"indicator1": "RSI", "indicator2": None, "value": 70}}
                ],
                "stop_loss_method": "percentage",
                "stop_loss_value": 3.0,
                "take_profit_method": "risk_reward",
                "take_profit_value": 2.5
            },
            {
                "name": "MACD Strategy",
                "description": "Buy when MACD crosses above signal line, sell when MACD crosses below signal line",
                "strategy_params": {
                    "indicators": [
                        {"type": "macd", "params": {"fast": 12, "slow": 26, "signal": 9}}
                    ]
                },
                "buy_conditions": [
                    {"type": "crosses_above", "params": {"indicator1": "MACD", "indicator2": "MACD_Signal"}}
                ],
                "sell_conditions": [
                    {"type": "crosses_below", "params": {"indicator1": "MACD", "indicator2": "MACD_Signal"}}
                ],
                "stop_loss_method": "atr",
                "stop_loss_value": 2.0,
                "take_profit_method": "risk_reward",
                "take_profit_value": 2.0
            },
            {
                "name": "Bollinger Bands Strategy",
                "description": "Buy when price touches the lower band and starts rising, sell when it touches the upper band and starts falling",
                "strategy_params": {
                    "indicators": [
                        {"type": "bollinger", "params": {"period": 20, "std_dev": 2}}
                    ]
                },
                "buy_conditions": [
                    {"type": "less_than", "params": {"indicator1": "Low", "indicator2": "BB_Lower"}, "operation": "initial"},
                    {"type": "greater_than", "params": {"indicator1": "Close", "indicator2": "Close", "shift": 1}, "operation": "and"}
                ],
                "sell_conditions": [
                    {"type": "greater_than", "params": {"indicator1": "High", "indicator2": "BB_Upper"}, "operation": "initial"},
                    {"type": "less_than", "params": {"indicator1": "Close", "indicator2": "Close", "shift": 1}, "operation": "and"}
                ],
                "stop_loss_method": "percentage",
                "stop_loss_value": 2.0,
                "take_profit_method": "risk_reward",
                "take_profit_value": 2.0
            }
        ]
    }
