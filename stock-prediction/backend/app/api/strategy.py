"""
API routes for trading strategies and management
"""
from fastapi import APIRouter, Depends, HTTPException, Query, Path
from typing import List, Dict, Any, Optional
from sqlalchemy.orm import Session
from datetime import datetime, timedelta

from app.core.database import get_db
from app.models.stocks import Stock
from app.core.security import get_current_active_user

router = APIRouter(tags=["strategy"])

@router.get("/strategies")
async def get_strategies(
    user_id: str,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Get all trading strategies for a user
    
    Parameters:
    - user_id: User ID
    """
    # In a production app, we would query from a strategies table
    # For now, return mock data
    
    strategies = [
        {
            "id": "strat_001",
            "name": "Golden Cross",
            "description": "SMA(50) crosses above SMA(200)",
            "created_at": "2025-05-15 10:30:00",
            "last_run": "2025-06-09 14:25:00",
            "is_active": True,
            "type": "technical",
            "indicators": ["sma"],
            "parameters": {
                "fast_ma": 50,
                "slow_ma": 200,
                "lookback_period": 365
            },
            "performance": {
                "win_rate": 68.5,
                "total_trades": 42,
                "avg_profit": 12.3,
                "max_drawdown": 15.2
            }
        },
        {
            "id": "strat_002",
            "name": "RSI Oversold + Support",
            "description": "Buy when RSI < 30 and price near support level",
            "created_at": "2025-05-20 15:45:00",
            "last_run": "2025-06-09 14:25:00",
            "is_active": True,
            "type": "technical",
            "indicators": ["rsi", "support_resistance"],
            "parameters": {
                "rsi_period": 14,
                "rsi_threshold": 30,
                "support_tolerance": 2.5
            },
            "performance": {
                "win_rate": 72.1,
                "total_trades": 36,
                "avg_profit": 8.7,
                "max_drawdown": 12.1
            }
        },
        {
            "id": "strat_003",
            "name": "AI Prediction Follow",
            "description": "Follow AI prediction signals",
            "created_at": "2025-06-01 09:15:00",
            "last_run": "2025-06-09 14:25:00",
            "is_active": True,
            "type": "ai",
            "indicators": ["ai_prediction"],
            "parameters": {
                "confidence_threshold": 75,
                "min_expected_profit": 5.0
            },
            "performance": {
                "win_rate": 81.2,
                "total_trades": 32,
                "avg_profit": 9.8,
                "max_drawdown": 10.5
            }
        }
    ]
    
    return {
        "strategies": strategies,
        "count": len(strategies)
    }

@router.get("/strategies/{strategy_id}")
async def get_strategy(
    strategy_id: str,
    user_id: str,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Get details of a specific strategy
    
    Parameters:
    - strategy_id: Strategy ID
    - user_id: User ID
    """
    # In a production app, we would query from a strategies table
    # For now, return mock data for specific strategies
    
    mock_strategies = {
        "strat_001": {
            "id": "strat_001",
            "name": "Golden Cross",
            "description": "SMA(50) crosses above SMA(200)",
            "created_at": "2025-05-15 10:30:00",
            "last_run": "2025-06-09 14:25:00",
            "is_active": True,
            "type": "technical",
            "indicators": ["sma"],
            "parameters": {
                "fast_ma": 50,
                "slow_ma": 200,
                "lookback_period": 365,
                "confirmation_days": 3,
                "exit_strategy": "sma_cross_below"
            },
            "performance": {
                "win_rate": 68.5,
                "total_trades": 42,
                "winning_trades": 29,
                "losing_trades": 13,
                "avg_profit": 12.3,
                "avg_loss": 5.7,
                "max_drawdown": 15.2,
                "sharpe_ratio": 1.4,
                "profit_factor": 2.1
            },
            "recent_signals": [
                {
                    "ticker": "AAPL",
                    "signal": "BUY",
                    "date": "2025-06-08",
                    "price": 192.53,
                    "confidence": 82.5
                },
                {
                    "ticker": "MSFT",
                    "signal": "BUY",
                    "date": "2025-06-07",
                    "price": 342.75,
                    "confidence": 78.9
                },
                {
                    "ticker": "GOOG",
                    "signal": "SELL",
                    "date": "2025-06-06",
                    "price": 178.42,
                    "confidence": 65.3
                }
            ]
        },
        "strat_002": {
            "id": "strat_002",
            "name": "RSI Oversold + Support",
            "description": "Buy when RSI < 30 and price near support level",
            "created_at": "2025-05-20 15:45:00",
            "last_run": "2025-06-09 14:25:00",
            "is_active": True,
            "type": "technical",
            "indicators": ["rsi", "support_resistance"],
            "parameters": {
                "rsi_period": 14,
                "rsi_threshold": 30,
                "support_tolerance": 2.5,
                "confirmation_candles": 2,
                "exit_strategy": "rsi_above_70"
            },
            "performance": {
                "win_rate": 72.1,
                "total_trades": 36,
                "winning_trades": 26,
                "losing_trades": 10,
                "avg_profit": 8.7,
                "avg_loss": 4.2,
                "max_drawdown": 12.1,
                "sharpe_ratio": 1.7,
                "profit_factor": 2.5
            },
            "recent_signals": [
                {
                    "ticker": "NFLX",
                    "signal": "BUY",
                    "date": "2025-06-09",
                    "price": 612.35,
                    "confidence": 88.2
                },
                {
                    "ticker": "AMZN",
                    "signal": "BUY",
                    "date": "2025-06-07",
                    "price": 145.23,
                    "confidence": 76.4
                }
            ]
        },
        "strat_003": {
            "id": "strat_003",
            "name": "AI Prediction Follow",
            "description": "Follow AI prediction signals",
            "created_at": "2025-06-01 09:15:00",
            "last_run": "2025-06-09 14:25:00",
            "is_active": True,
            "type": "ai",
            "indicators": ["ai_prediction"],
            "parameters": {
                "confidence_threshold": 75,
                "min_expected_profit": 5.0,
                "model_type": "ensemble",
                "prediction_horizon": 5,
                "exit_strategy": "target_profit_or_stop_loss",
                "target_profit_percent": 10,
                "stop_loss_percent": 5
            },
            "performance": {
                "win_rate": 81.2,
                "total_trades": 32,
                "winning_trades": 26,
                "losing_trades": 6,
                "avg_profit": 9.8,
                "avg_loss": 3.2,
                "max_drawdown": 10.5,
                "sharpe_ratio": 2.1,
                "profit_factor": 3.2
            },
            "recent_signals": [
                {
                    "ticker": "TSLA",
                    "signal": "BUY",
                    "date": "2025-06-09",
                    "price": 228.65,
                    "confidence": 92.7
                },
                {
                    "ticker": "NVDA",
                    "signal": "BUY",
                    "date": "2025-06-08",
                    "price": 924.35,
                    "confidence": 88.9
                },
                {
                    "ticker": "META",
                    "signal": "HOLD",
                    "date": "2025-06-08",
                    "price": 478.92,
                    "confidence": 65.8
                }
            ]
        }
    }
    
    strategy = mock_strategies.get(strategy_id)
    if not strategy:
        raise HTTPException(status_code=404, detail=f"Strategy {strategy_id} not found")
    
    return strategy

@router.post("/strategies")
async def create_strategy(
    user_id: str,
    name: str,
    description: str,
    strategy_type: str,
    indicators: List[str],
    parameters: Dict[str, Any],
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Create a new trading strategy
    
    Parameters:
    - user_id: User ID
    - name: Strategy name
    - description: Strategy description
    - strategy_type: Type of strategy (technical, fundamental, ai)
    - indicators: List of indicators used in the strategy
    - parameters: Strategy parameters
    """
    # Validate strategy type
    valid_types = ["technical", "fundamental", "ai", "hybrid"]
    if strategy_type not in valid_types:
        raise HTTPException(status_code=400, detail=f"Invalid strategy type. Must be one of: {', '.join(valid_types)}")
    
    # Validate indicators
    if not indicators:
        raise HTTPException(status_code=400, detail="At least one indicator must be specified")
    
    # In a production app, we would save to a strategies table
    # For now, just return success response
    new_strategy = {
        "id": f"strat_{datetime.now().strftime('%Y%m%d%H%M%S')}",
        "user_id": user_id,
        "name": name,
        "description": description,
        "created_at": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        "last_run": None,
        "is_active": True,
        "type": strategy_type,
        "indicators": indicators,
        "parameters": parameters,
        "performance": {
            "win_rate": 0,
            "total_trades": 0,
            "winning_trades": 0,
            "losing_trades": 0,
            "avg_profit": 0,
            "avg_loss": 0,
            "max_drawdown": 0,
            "sharpe_ratio": 0,
            "profit_factor": 0
        },
        "recent_signals": []
    }
    
    return {
        "status": "success",
        "message": "Strategy created successfully",
        "strategy": new_strategy
    }

@router.put("/strategies/{strategy_id}")
async def update_strategy(
    strategy_id: str,
    user_id: str,
    name: Optional[str] = None,
    description: Optional[str] = None,
    is_active: Optional[bool] = None,
    parameters: Optional[Dict[str, Any]] = None,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Update a trading strategy
    
    Parameters:
    - strategy_id: Strategy ID
    - user_id: User ID
    - name: Strategy name
    - description: Strategy description
    - is_active: Whether the strategy is active
    - parameters: Strategy parameters
    """
    # In a production app, we would update the strategy in the database
    # For now, just return success response
    
    # Create an updated fields dictionary with only provided fields
    updated_fields = {}
    if name is not None:
        updated_fields["name"] = name
    if description is not None:
        updated_fields["description"] = description
    if is_active is not None:
        updated_fields["is_active"] = is_active
    if parameters is not None:
        updated_fields["parameters"] = parameters
    
    return {
        "status": "success",
        "message": f"Strategy {strategy_id} updated successfully",
        "updated_fields": updated_fields
    }

@router.delete("/strategies/{strategy_id}")
async def delete_strategy(
    strategy_id: str,
    user_id: str,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Delete a trading strategy
    
    Parameters:
    - strategy_id: Strategy ID
    - user_id: User ID
    """
    # In a production app, we would delete the strategy from the database
    # For now, just return success response
    return {
        "status": "success",
        "message": f"Strategy {strategy_id} deleted successfully"
    }

@router.get("/strategy-templates")
async def get_strategy_templates(
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Get pre-defined strategy templates
    """
    templates = [
        {
            "id": "template_001",
            "name": "Golden Cross",
            "description": "SMA(50) crosses above SMA(200)",
            "type": "technical",
            "indicators": ["sma"],
            "parameters": {
                "fast_ma": 50,
                "slow_ma": 200,
                "lookback_period": 365,
                "confirmation_days": 3,
                "exit_strategy": "sma_cross_below"
            }
        },
        {
            "id": "template_002",
            "name": "RSI Oversold Bounce",
            "description": "Buy when RSI < 30, sell when RSI > 70",
            "type": "technical",
            "indicators": ["rsi"],
            "parameters": {
                "rsi_period": 14,
                "oversold_threshold": 30,
                "overbought_threshold": 70,
                "confirmation_days": 2,
                "exit_strategy": "rsi_above_70"
            }
        },
        {
            "id": "template_003",
            "name": "MACD Crossover",
            "description": "Buy on MACD line crossing above signal line",
            "type": "technical",
            "indicators": ["macd"],
            "parameters": {
                "macd_fast": 12,
                "macd_slow": 26,
                "macd_signal": 9,
                "confirmation_days": 2,
                "exit_strategy": "macd_cross_below"
            }
        },
        {
            "id": "template_004",
            "name": "Bollinger Band Bounce",
            "description": "Buy when price touches lower band, sell at middle band",
            "type": "technical",
            "indicators": ["bollinger"],
            "parameters": {
                "bb_period": 20,
                "bb_std_dev": 2,
                "oversold_threshold": 0.02,
                "exit_strategy": "middle_band_touch"
            }
        },
        {
            "id": "template_005",
            "name": "AI Prediction Strategy",
            "description": "Use AI predictions to generate signals",
            "type": "ai",
            "indicators": ["ai_prediction"],
            "parameters": {
                "model_type": "ensemble",
                "confidence_threshold": 80,
                "min_expected_profit": 5,
                "exit_strategy": "target_profit_or_stop_loss",
                "target_profit_percent": 15,
                "stop_loss_percent": 7
            }
        }
    ]
    
    return {
        "templates": templates,
        "count": len(templates)
    }
