"""
Models for user watchlists
"""
from sqlalchemy import Column, Integer, String, Boolean, ForeignKey, DateTime
from sqlalchemy.orm import relationship
from datetime import datetime

from app.core.database import Base
from app.models.user_strategies import User

class WatchlistItem(Base):
    """Model for a stock item in a user's watchlist"""
    __tablename__ = "watchlist_items"
    
    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"))
    ticker = Column(String, index=True)
    added_at = Column(DateTime, default=datetime.now)
    notes = Column(String, nullable=True)
    is_favorite = Column(Boolean, default=False)
    alert_price_high = Column(Integer, nullable=True)  # Price alert when stock goes above this value
    alert_price_low = Column(Integer, nullable=True)   # Price alert when stock goes below this value
    
    # Relationships
    user = relationship("User")
    
    def __repr__(self):
        return f"<WatchlistItem {self.ticker} for user {self.user_id}>"
