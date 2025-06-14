"""
Database models for portfolio management
"""
from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Text, Boolean, JSON, Enum
from sqlalchemy.orm import relationship
from sqlalchemy.dialects.postgresql import UUID
import uuid
from datetime import datetime
import enum

from app.core.database import Base


class TransactionType(str, enum.Enum):
    """Types of portfolio transactions"""
    BUY = "BUY"
    SELL = "SELL"
    DIVIDEND = "DIVIDEND"
    SPLIT = "SPLIT"
    DEPOSIT = "DEPOSIT"
    WITHDRAWAL = "WITHDRAWAL"


class Portfolio(Base):
    """User portfolio for tracking investments"""
    __tablename__ = "portfolios"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    user_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), nullable=False)
    name = Column(String(100), nullable=False)
    description = Column(Text)
    
    # Portfolio configuration
    default_currency = Column(String(3), default="IDR")  # Default currency (IDR, USD)
    is_default = Column(Boolean, default=False)  # Is this the default portfolio for the user
    
    # Portfolio balances
    cash_balance = Column(Float, default=0.0)
    total_value = Column(Float, default=0.0)  # Current value of all holdings + cash
    total_cost = Column(Float, default=0.0)  # Total cost basis of all holdings
    
    # System fields
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    
    # Relationships
    user = relationship("User", backref="portfolios")
    holdings = relationship("PortfolioHolding", back_populates="portfolio", cascade="all, delete-orphan")
    transactions = relationship("PortfolioTransaction", back_populates="portfolio", cascade="all, delete-orphan")
    
    def __repr__(self):
        return f"<Portfolio {self.name} for user {self.user_id}>"


class PortfolioHolding(Base):
    """Stock holding within a portfolio"""
    __tablename__ = "portfolio_holdings"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    portfolio_id = Column(String(36), ForeignKey("portfolios.id", ondelete="CASCADE"), nullable=False)
    ticker = Column(String(20), nullable=False)
    
    # Current position
    shares = Column(Float, nullable=False, default=0.0)
    average_cost = Column(Float, nullable=False)
    cost_basis = Column(Float, nullable=False)  # total cost for this holding (shares * avg_cost)
    current_price = Column(Float)
    current_value = Column(Float)  # current market value (shares * current_price)
    
    # Performance
    unrealized_gain_loss = Column(Float)
    unrealized_gain_loss_pct = Column(Float)
    realized_gain_loss = Column(Float)  # from selling part of this holding
    
    # Additional data
    sector = Column(String(50))
    industry = Column(String(100))
    last_updated = Column(DateTime, default=datetime.now)
    
    # Relationships
    portfolio = relationship("Portfolio", back_populates="holdings")
    transactions = relationship("PortfolioTransaction", back_populates="holding")
    
    def __repr__(self):
        return f"<PortfolioHolding {self.ticker} in portfolio {self.portfolio_id}>"


class PortfolioTransaction(Base):
    """Individual transaction in a portfolio"""
    __tablename__ = "portfolio_transactions"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    portfolio_id = Column(String(36), ForeignKey("portfolios.id", ondelete="CASCADE"), nullable=False)
    holding_id = Column(String(36), ForeignKey("portfolio_holdings.id", ondelete="SET NULL"), nullable=True)
    
    # Transaction details
    transaction_type = Column(Enum(TransactionType), nullable=False)
    ticker = Column(String(20))  # nullable for DEPOSIT/WITHDRAWAL
    date = Column(DateTime, nullable=False, default=datetime.now)
    shares = Column(Float)
    price = Column(Float)
    amount = Column(Float, nullable=False)  # Total transaction amount
    fees = Column(Float, default=0.0)
    
    # For splits
    split_ratio = Column(String(10))  # e.g., "2:1" for 2-for-1 split
    
    # Metadata
    notes = Column(Text)
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    
    # Relationships
    portfolio = relationship("Portfolio", back_populates="transactions")
    holding = relationship("PortfolioHolding", back_populates="transactions")
    
    def __repr__(self):
        return f"<PortfolioTransaction {self.transaction_type.value} {self.ticker if self.ticker else ''} {self.amount}>"


class PortfolioSnapshot(Base):
    """Historical snapshot of portfolio value"""
    __tablename__ = "portfolio_snapshots"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    portfolio_id = Column(String(36), ForeignKey("portfolios.id", ondelete="CASCADE"), nullable=False)
    date = Column(DateTime, nullable=False)
    
    # Snapshot values
    total_value = Column(Float, nullable=False)
    cash_balance = Column(Float, nullable=False)
    securities_value = Column(Float, nullable=False)
    
    # Performance since inception
    cost_basis = Column(Float, nullable=False)
    gain_loss = Column(Float)
    gain_loss_pct = Column(Float)
    
    # Current day performance
    day_change = Column(Float)
    day_change_pct = Column(Float)
    
    # Allocation data
    allocation_by_sector = Column(JSON)  # Sector allocation percentages
    top_holdings = Column(JSON)  # Top holdings by value
    
    # Relationships
    portfolio = relationship("Portfolio")
    
    def __repr__(self):
        return f"<PortfolioSnapshot for {self.portfolio_id} on {self.date.strftime('%Y-%m-%d')}>"


class PortfolioAlert(Base):
    """Alerts for portfolio items"""
    __tablename__ = "portfolio_alerts"

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    user_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), nullable=False)
    portfolio_id = Column(String(36), ForeignKey("portfolios.id", ondelete="CASCADE"), nullable=True)
    ticker = Column(String(20), nullable=True)
    
    # Alert configuration
    alert_type = Column(String(50), nullable=False)  # price_target, price_change, portfolio_value
    target_value = Column(Float, nullable=True)
    target_percentage = Column(Float, nullable=True)
    direction = Column(String(10), nullable=True)  # above, below
    
    # Alert status
    is_active = Column(Boolean, default=True)
    is_triggered = Column(Boolean, default=False)
    last_triggered = Column(DateTime, nullable=True)
    
    # System fields
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    
    # Relationships
    user = relationship("User")
    portfolio = relationship("Portfolio")
    
    def __repr__(self):
        if self.ticker:
            return f"<PortfolioAlert {self.alert_type} for {self.ticker}>"
        return f"<PortfolioAlert {self.alert_type} for portfolio {self.portfolio_id}>"
