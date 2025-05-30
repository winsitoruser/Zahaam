"""
Models for user custom trading strategies
"""
from sqlalchemy import Column, Integer, String, Float, Boolean, ForeignKey, JSON, Text, DateTime
from sqlalchemy.orm import relationship
from datetime import datetime

from app.core.database import Base

class User(Base):
    """User model for authentication and strategy ownership"""
    __tablename__ = "users"

    id = Column(Integer, primary_key=True, index=True)
    username = Column(String, unique=True, index=True)
    email = Column(String, unique=True, index=True)
    hashed_password = Column(String)
    is_active = Column(Boolean, default=True)
    created_at = Column(DateTime, default=datetime.now)
    
    # Relationships
    strategies = relationship("UserStrategy", back_populates="owner")
    
    def __repr__(self):
        return f"<User {self.username}>"


class UserStrategy(Base):
    """Custom trading strategy created by a user"""
    __tablename__ = "user_strategies"

    id = Column(Integer, primary_key=True, index=True)
    name = Column(String, index=True)
    description = Column(Text, nullable=True)
    user_id = Column(Integer, ForeignKey("users.id"))
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    is_public = Column(Boolean, default=False)
    
    # Strategy parameters stored as JSON
    strategy_params = Column(JSON)
    
    # Signal parameters
    buy_conditions = Column(JSON)
    sell_conditions = Column(JSON)
    stop_loss_method = Column(String, default="percentage")  # percentage, atr, support
    stop_loss_value = Column(Float, default=2.0)  # percentage or multiplier
    take_profit_method = Column(String, default="risk_reward")
    take_profit_value = Column(Float, default=2.0)  # risk/reward ratio
    
    # Performance metrics (updated after backtesting)
    win_rate = Column(Float, nullable=True)
    avg_profit = Column(Float, nullable=True)
    max_drawdown = Column(Float, nullable=True)
    total_trades = Column(Integer, default=0)
    
    # Relationships
    owner = relationship("User", back_populates="strategies")
    
    def __repr__(self):
        return f"<UserStrategy {self.name} by {self.owner.username if self.owner else 'Unknown'}>"
