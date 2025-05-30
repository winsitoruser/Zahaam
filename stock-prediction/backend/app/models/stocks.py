"""
Database models for stock data
"""
from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Text, Boolean
from sqlalchemy.orm import relationship
from datetime import datetime
from app.core.database import Base


class Stock(Base):
    """Stock entity representing a company listed on the exchange"""
    __tablename__ = "stocks"

    id = Column(Integer, primary_key=True, index=True)
    ticker = Column(String(20), unique=True, index=True, nullable=False)
    name = Column(String(255), nullable=False)
    sector = Column(String(100))
    last_updated = Column(DateTime, default=datetime.now)
    is_active = Column(Boolean, default=True)
    
    # Relationships
    prices = relationship("StockPrice", back_populates="stock", cascade="all, delete-orphan")
    indicators = relationship("StockIndicator", back_populates="stock", cascade="all, delete-orphan")
    
    def __repr__(self):
        return f"<Stock(ticker='{self.ticker}', name='{self.name}')>"


class StockPrice(Base):
    """Historical price data for a stock"""
    __tablename__ = "stock_prices"

    id = Column(Integer, primary_key=True, index=True)
    stock_id = Column(Integer, ForeignKey("stocks.id", ondelete="CASCADE"), nullable=False)
    date = Column(DateTime, index=True, nullable=False)
    open = Column(Float)
    high = Column(Float)
    low = Column(Float)
    close = Column(Float)
    volume = Column(Integer)
    
    # Relationship
    stock = relationship("Stock", back_populates="prices")
    
    def __repr__(self):
        return f"<StockPrice(ticker='{self.stock.ticker}', date='{self.date}', close={self.close})>"
    
    class Config:
        # Create a composite index on stock_id and date
        __table_args__ = (
            {"sqlite_autoincrement": True},
        )


class StockIndicator(Base):
    """Technical indicators for a stock on a specific date"""
    __tablename__ = "stock_indicators"

    id = Column(Integer, primary_key=True, index=True)
    stock_id = Column(Integer, ForeignKey("stocks.id", ondelete="CASCADE"), nullable=False)
    date = Column(DateTime, index=True, nullable=False)
    
    # Common indicators
    sma_20 = Column(Float)
    sma_50 = Column(Float)
    sma_200 = Column(Float)
    ema_12 = Column(Float)
    ema_26 = Column(Float)
    rsi_14 = Column(Float)
    macd = Column(Float)
    macd_signal = Column(Float)
    macd_histogram = Column(Float)
    bb_upper = Column(Float)
    bb_middle = Column(Float)
    bb_lower = Column(Float)
    
    # Signals
    signal = Column(String(20))  # BUY, SELL, HOLD
    signal_strength = Column(Float)  # 0 to 1
    notes = Column(Text)
    
    # Relationship
    stock = relationship("Stock", back_populates="indicators")
    
    def __repr__(self):
        return f"<StockIndicator(ticker='{self.stock.ticker}', date='{self.date}')>"
    
    class Config:
        # Create a composite index on stock_id and date
        __table_args__ = (
            {"sqlite_autoincrement": True},
        )
