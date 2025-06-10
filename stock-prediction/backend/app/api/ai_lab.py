"""
API routes for AI Lab (Premium feature)
"""
from fastapi import APIRouter, Depends, HTTPException, Query, Path, BackgroundTasks
from typing import List, Dict, Any, Optional
from sqlalchemy.orm import Session
from datetime import datetime, timedelta
import json

from app.core.database import get_db
from app.models.stocks import Stock, StockPrice
from app.core.security import get_current_active_user

router = APIRouter(tags=["ai_lab"])

@router.get("/ai-lab/models")
async def get_ai_models(
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Get available AI models for custom strategies
    """
    # In a production app, this would query available models from a database
    # For now, return mock data
    
    models = [
        {
            "id": "random_forest",
            "name": "Random Forest",
            "description": "Ensemble learning method for classification and regression",
            "accuracy": 78.5,
            "features": ["Price patterns", "Volume", "Moving averages", "Technical indicators"],
            "parameters": [
                {
                    "name": "n_estimators",
                    "label": "Number of trees",
                    "type": "integer",
                    "default": 100,
                    "min": 10,
                    "max": 500
                },
                {
                    "name": "max_depth",
                    "label": "Maximum depth",
                    "type": "integer",
                    "default": 10,
                    "min": 3,
                    "max": 20
                },
                {
                    "name": "min_samples_split",
                    "label": "Minimum samples to split",
                    "type": "integer",
                    "default": 2,
                    "min": 2,
                    "max": 10
                }
            ],
            "prediction_types": ["price", "direction", "volatility"]
        },
        {
            "id": "lstm",
            "name": "LSTM Neural Network",
            "description": "Long Short-Term Memory neural network for time series forecasting",
            "accuracy": 82.3,
            "features": ["Price patterns", "Volume", "Moving averages", "Technical indicators", "Sentiment"],
            "parameters": [
                {
                    "name": "units",
                    "label": "LSTM units",
                    "type": "integer",
                    "default": 50,
                    "min": 10,
                    "max": 200
                },
                {
                    "name": "layers",
                    "label": "Number of layers",
                    "type": "integer",
                    "default": 2,
                    "min": 1,
                    "max": 5
                },
                {
                    "name": "dropout",
                    "label": "Dropout rate",
                    "type": "float",
                    "default": 0.2,
                    "min": 0.0,
                    "max": 0.5
                },
                {
                    "name": "time_steps",
                    "label": "Time steps",
                    "type": "integer",
                    "default": 60,
                    "min": 30,
                    "max": 90
                }
            ],
            "prediction_types": ["price", "direction", "volatility"]
        },
        {
            "id": "xgboost",
            "name": "XGBoost",
            "description": "Gradient boosting framework with trees",
            "accuracy": 83.7,
            "features": ["Price patterns", "Volume", "Moving averages", "Technical indicators", "Market breadth"],
            "parameters": [
                {
                    "name": "learning_rate",
                    "label": "Learning rate",
                    "type": "float",
                    "default": 0.1,
                    "min": 0.01,
                    "max": 0.3
                },
                {
                    "name": "max_depth",
                    "label": "Maximum depth",
                    "type": "integer",
                    "default": 6,
                    "min": 3,
                    "max": 15
                },
                {
                    "name": "n_estimators",
                    "label": "Number of estimators",
                    "type": "integer",
                    "default": 100,
                    "min": 50,
                    "max": 300
                }
            ],
            "prediction_types": ["price", "direction", "volatility", "support_resistance"]
        },
        {
            "id": "ensemble",
            "name": "Ensemble (Premium)",
            "description": "Combined predictions from multiple models for higher accuracy",
            "accuracy": 87.2,
            "features": ["Price patterns", "Volume", "Moving averages", "Technical indicators", "Sentiment", "Market breadth"],
            "parameters": [
                {
                    "name": "models",
                    "label": "Models to include",
                    "type": "multi_select",
                    "default": ["random_forest", "lstm", "xgboost"],
                    "options": ["random_forest", "lstm", "xgboost"]
                },
                {
                    "name": "voting",
                    "label": "Voting method",
                    "type": "select",
                    "default": "soft",
                    "options": ["soft", "hard", "weighted"]
                }
            ],
            "prediction_types": ["price", "direction", "volatility", "support_resistance", "trend_strength"]
        }
    ]
    
    return {
        "models": models,
        "count": len(models)
    }

@router.post("/ai-lab/custom-model")
async def create_custom_model(
    user_id: str,
    model_type: str,
    name: str,
    description: Optional[str] = None,
    parameters: Dict[str, Any] = None,
    features: List[str] = None,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Create a custom AI model with specific parameters
    
    Parameters:
    - user_id: User ID
    - model_type: Base model type to customize
    - name: Custom model name
    - description: Custom model description
    - parameters: Model parameters
    - features: Features to include in the model
    """
    valid_model_types = ["random_forest", "lstm", "xgboost", "ensemble"]
    if model_type not in valid_model_types:
        raise HTTPException(
            status_code=400, 
            detail=f"Invalid model type. Must be one of: {', '.join(valid_model_types)}"
        )
    
    # In a production app, this would save the custom model to a database
    # For now, return mock data
    
    custom_model = {
        "id": f"custom_{user_id}_{datetime.now().strftime('%Y%m%d%H%M%S')}",
        "user_id": user_id,
        "base_model": model_type,
        "name": name,
        "description": description or f"Custom {model_type.capitalize()} model",
        "created_at": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        "parameters": parameters or {},
        "features": features or [],
        "status": "created"
    }
    
    return {
        "status": "success",
        "message": "Custom model created successfully",
        "model": custom_model
    }

@router.post("/ai-lab/train")
async def train_custom_model(
    background_tasks: BackgroundTasks,
    model_id: str,
    ticker: str,
    training_period: str = "1y",
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Train a custom AI model on historical data
    
    Parameters:
    - model_id: Custom model ID
    - ticker: Stock ticker for training
    - training_period: Historical data period for training (1m, 3m, 6m, 1y, 5y)
    """
    # Check if stock exists
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail=f"Stock {ticker} not found")
    
    # In a production app, this would start an async task to train the model
    # For now, simulate a training job
    
    # Generate job ID
    job_id = f"train_{model_id}_{ticker}_{datetime.now().strftime('%Y%m%d%H%M%S')}"
    
    # Return immediate response while "training" happens in background
    return {
        "status": "success",
        "message": f"Training started for model {model_id} on {ticker}",
        "job_id": job_id,
        "estimated_completion": "5 minutes"
    }

@router.get("/ai-lab/training-status/{job_id}")
async def get_training_status(
    job_id: str,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Check the status of a model training job
    
    Parameters:
    - job_id: Training job ID
    """
    # In a production app, this would check the actual training status
    # For now, generate a random status based on job_id
    
    # Extract info from job_id for demo
    parts = job_id.split('_')
    if len(parts) < 4 or parts[0] != "train":
        raise HTTPException(status_code=404, detail="Invalid job ID")
    
    model_id = parts[1]
    ticker = parts[2]
    
    # Generate a status based on time elapsed since job was created
    created_time = datetime.strptime('_'.join(parts[3:]), '%Y%m%d%H%M%S')
    elapsed = (datetime.now() - created_time).total_seconds()
    
    # Simulate different stages of training
    if elapsed < 30:
        status = "preparing_data"
        progress = 10
        message = "Preparing training data..."
    elif elapsed < 60:
        status = "feature_engineering"
        progress = 30
        message = "Performing feature engineering..."
    elif elapsed < 90:
        status = "training"
        progress = 60
        message = "Training model..."
    elif elapsed < 120:
        status = "evaluation"
        progress = 80
        message = "Evaluating model performance..."
    else:
        status = "completed"
        progress = 100
        message = "Training completed successfully"
    
    return {
        "job_id": job_id,
        "model_id": model_id,
        "ticker": ticker,
        "status": status,
        "progress": progress,
        "message": message,
        "started_at": created_time.strftime("%Y-%m-%d %H:%M:%S"),
        "estimated_completion": (created_time + timedelta(minutes=2)).strftime("%Y-%m-%d %H:%M:%S") if status != "completed" else None
    }

@router.get("/ai-lab/model-comparison")
async def compare_models(
    ticker: str,
    model_ids: List[str],
    period: str = "3m",
    metrics: List[str] = Query(["accuracy", "sharpe_ratio", "profit_factor"]),
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Compare performance of multiple AI models
    
    Parameters:
    - ticker: Stock ticker for comparison
    - model_ids: List of model IDs to compare
    - period: Test period for comparison
    - metrics: Metrics to include in comparison
    """
    # Check if stock exists
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    if not stock:
        raise HTTPException(status_code=404, detail=f"Stock {ticker} not found")
    
    # In a production app, this would fetch actual model performance data
    # For now, generate mock comparison data
    
    # Define baseline performance
    base_performance = {
        "accuracy": 75.5,
        "precision": 72.3,
        "recall": 68.9,
        "f1_score": 70.5,
        "mse": 0.0032,
        "mae": 0.045,
        "sharpe_ratio": 1.35,
        "sortino_ratio": 1.68,
        "profit_factor": 1.82,
        "max_drawdown": 15.2
    }
    
    # Generate comparison results
    comparison = []
    
    for i, model_id in enumerate(model_ids):
        # Determine model type from ID
        model_type = "random_forest" if "random_forest" in model_id else (
            "lstm" if "lstm" in model_id else (
                "xgboost" if "xgboost" in model_id else "ensemble"
            )
        )
        
        # Generate performance metrics with slight variations
        model_performance = {k: v * (0.9 + (i * 0.05)) for k, v in base_performance.items()}
        
        # Add comparison entry
        comparison.append({
            "model_id": model_id,
            "model_type": model_type,
            "ticker": ticker,
            "period": period,
            "performance": {
                k: round(v, 3) for k, v in model_performance.items() if k in metrics
            }
        })
    
    # Sort by accuracy
    comparison.sort(key=lambda x: x["performance"].get("accuracy", 0), reverse=True)
    
    return {
        "ticker": ticker,
        "period": period,
        "metrics": metrics,
        "comparison": comparison
    }

@router.post("/ai-lab/deploy-model")
async def deploy_model(
    model_id: str,
    name: Optional[str] = None,
    description: Optional[str] = None,
    db: Session = Depends(get_db),
    _: Dict = Depends(get_current_active_user)
):
    """
    Deploy a custom model for live predictions
    
    Parameters:
    - model_id: Model ID to deploy
    - name: Deployment name
    - description: Deployment description
    """
    # In a production app, this would deploy the model to a prediction service
    # For now, return mock deployment status
    
    deployment = {
        "id": f"deploy_{model_id}_{datetime.now().strftime('%Y%m%d%H%M%S')}",
        "model_id": model_id,
        "name": name or f"Deployment {datetime.now().strftime('%Y-%m-%d')}",
        "description": description or "Custom model deployment",
        "status": "active",
        "deployed_at": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        "endpoint": f"/api/predict/custom/{model_id}"
    }
    
    return {
        "status": "success",
        "message": "Model deployed successfully",
        "deployment": deployment
    }
