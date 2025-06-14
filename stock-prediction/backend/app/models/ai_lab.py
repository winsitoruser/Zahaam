"""
Database models for AI Lab and custom prediction models
"""
from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Text, Boolean, JSON
from sqlalchemy.orm import relationship
from sqlalchemy.dialects.postgresql import UUID
import uuid
from datetime import datetime

from app.core.database import Base


class AIModel(Base):
    """Base model types available in the AI Lab"""
    __tablename__ = "ai_models"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    name = Column(String(100), nullable=False)
    description = Column(Text)
    model_type = Column(String(50), nullable=False)  # random_forest, lstm, xgboost, ensemble
    is_premium = Column(Boolean, default=False)
    accuracy = Column(Float)
    
    # Model capabilities
    features_supported = Column(JSON)  # List of features this model can use
    parameters = Column(JSON)  # Available parameters and their defaults/ranges
    prediction_types = Column(JSON)  # Types of predictions (price, direction, volatility)
    
    # System fields
    created_at = Column(DateTime, default=datetime.now)
    is_active = Column(Boolean, default=True)
    
    # Relationships
    custom_models = relationship("CustomAIModel", back_populates="base_model")
    
    def __repr__(self):
        return f"<AIModel {self.name} ({self.model_type})>"


class CustomAIModel(Base):
    """User-created custom AI model based on a base model"""
    __tablename__ = "custom_ai_models"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    user_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), nullable=False)
    base_model_id = Column(String(36), ForeignKey("ai_models.id"), nullable=False)
    name = Column(String(100), nullable=False)
    description = Column(Text)
    
    # Model configuration
    parameters = Column(JSON)  # Specific parameters for this custom model
    features = Column(JSON)  # Features selected for this model
    
    # Training information
    is_trained = Column(Boolean, default=False)
    training_date = Column(DateTime)
    training_ticker = Column(String(20))  # Ticker used for training
    training_period = Column(String(10))  # 1m, 3m, 6m, 1y, 5y
    
    # Performance metrics
    accuracy = Column(Float)
    precision = Column(Float)
    recall = Column(Float)
    f1_score = Column(Float)
    mse = Column(Float)  # Mean squared error for regression models
    
    # System fields
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    is_active = Column(Boolean, default=True)
    is_deployed = Column(Boolean, default=False)
    
    # Relationships
    user = relationship("User", backref="custom_models")
    base_model = relationship("AIModel", back_populates="custom_models")
    training_jobs = relationship("ModelTrainingJob", back_populates="model", cascade="all, delete-orphan")
    
    def __repr__(self):
        return f"<CustomAIModel {self.name} by user {self.user_id}>"


class ModelTrainingJob(Base):
    """Training job for a custom AI model"""
    __tablename__ = "model_training_jobs"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    model_id = Column(String(36), ForeignKey("custom_ai_models.id", ondelete="CASCADE"), nullable=False)
    user_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), nullable=False)
    
    # Training configuration
    ticker = Column(String(20), nullable=False)
    start_date = Column(DateTime, nullable=False)
    end_date = Column(DateTime, nullable=False)
    
    # Job status
    status = Column(String(20), default="pending")  # pending, running, completed, failed
    progress = Column(Integer, default=0)  # 0-100%
    status_message = Column(Text)
    
    # Results
    results = Column(JSON)  # Training results and metrics
    
    # System fields
    created_at = Column(DateTime, default=datetime.now)
    started_at = Column(DateTime)
    completed_at = Column(DateTime)
    
    # Relationships
    model = relationship("CustomAIModel", back_populates="training_jobs")
    user = relationship("User")
    
    def __repr__(self):
        return f"<ModelTrainingJob {self.id} for model {self.model_id} ({self.status})>"


class ModelDeployment(Base):
    """Deployment of a trained custom AI model"""
    __tablename__ = "model_deployments"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    model_id = Column(String(36), ForeignKey("custom_ai_models.id", ondelete="CASCADE"), nullable=False)
    user_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), nullable=False)
    name = Column(String(100))
    description = Column(Text)
    
    # Deployment configuration
    endpoint_path = Column(String(100), unique=True)
    is_public = Column(Boolean, default=False)
    
    # Status
    status = Column(String(20), default="active")  # active, paused, expired
    
    # Usage metrics
    total_predictions = Column(Integer, default=0)
    last_prediction = Column(DateTime)
    
    # System fields
    created_at = Column(DateTime, default=datetime.now)
    expires_at = Column(DateTime)  # For premium users with time-limited deployments
    
    # Relationships
    model = relationship("CustomAIModel")
    user = relationship("User")
    
    def __repr__(self):
        return f"<ModelDeployment {self.name} for model {self.model_id}>"
