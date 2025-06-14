"""
Database models for strategy backtesting
"""
from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Text, Boolean, JSON
from sqlalchemy.orm import relationship
from sqlalchemy.dialects.postgresql import UUID
import uuid
from datetime import datetime

from app.core.database import Base


class BacktestSession(Base):
    """A backtesting session for a trading strategy"""
    __tablename__ = "backtest_sessions"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    user_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), nullable=False)
    strategy_id = Column(String, ForeignKey("user_strategies.id", ondelete="CASCADE"), nullable=False)
    ticker = Column(String(20), nullable=False)
    name = Column(String(100), nullable=False)
    description = Column(Text)
    
    # Date range
    start_date = Column(DateTime, nullable=False)
    end_date = Column(DateTime, nullable=False)
    
    # Configuration
    initial_capital = Column(Float, default=10000.0)
    position_size_type = Column(String(20), default="percentage")  # percentage, fixed, risk_based
    position_size_value = Column(Float, default=100.0)  # 100% = full portfolio
    commission_type = Column(String(20), default="percentage")  # percentage, fixed
    commission_value = Column(Float, default=0.1)  # 0.1% or fixed amount
    
    # Results
    status = Column(String(20), default="pending")  # pending, running, completed, failed
    total_return = Column(Float)
    total_return_pct = Column(Float)
    annualized_return = Column(Float)
    sharpe_ratio = Column(Float)
    max_drawdown = Column(Float)
    win_rate = Column(Float)
    total_trades = Column(Integer)
    profitable_trades = Column(Integer)
    loss_making_trades = Column(Integer)
    
    # Detailed results stored as JSON
    trades = Column(JSON)
    equity_curve = Column(JSON)
    
    created_at = Column(DateTime, default=datetime.now)
    completed_at = Column(DateTime)

    # Relationships
    user = relationship("User", backref="backtest_sessions")
    strategy = relationship("UserStrategy", backref="backtest_sessions")
    
    def __repr__(self):
        return f"<BacktestSession {self.id} for {self.ticker} using strategy {self.strategy_id}>"


class BacktestComparison(Base):
    """Comparison between multiple backtests"""
    __tablename__ = "backtest_comparisons"
    
    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    user_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), nullable=False)
    name = Column(String(100))
    description = Column(Text)
    
    # Configuration
    backtest_ids = Column(JSON)  # List of backtest session IDs being compared
    metrics = Column(JSON)  # List of metrics being compared
    
    # Results stored as JSON
    comparison_results = Column(JSON)
    
    created_at = Column(DateTime, default=datetime.now)
    
    # Relationships
    user = relationship("User", backref="backtest_comparisons")
    
    def __repr__(self):
        return f"<BacktestComparison {self.id} by user {self.user_id}>"
