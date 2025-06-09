"""
Database models for news articles and sentiment analysis
"""

from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Text, Boolean, Table
from sqlalchemy.orm import relationship
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.dialects.postgresql import JSONB
from datetime import datetime

from app.core.database import Base

class NewsArticle(Base):
    """Model for storing news articles"""
    __tablename__ = "news_articles"
    
    id = Column(String, primary_key=True, index=True)
    url = Column(String(1024), unique=True, index=True)
    title = Column(String(512), nullable=False)
    source = Column(String(128), nullable=False)
    published_date = Column(DateTime, nullable=False)
    content = Column(Text, nullable=True)
    content_hash = Column(String(64), nullable=True, index=True)  # For checking duplicates
    
    # Relationships
    sentiment_analyses = relationship("NewsSentimentAnalysis", back_populates="article")
    ticker_mentions = relationship("NewsTickerMention", back_populates="article")
    
    # Metadata
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    
    def __repr__(self):
        return f"<NewsArticle(id={self.id}, title='{self.title[:30]}...', source='{self.source}')>"


class NewsSentimentAnalysis(Base):
    """Model for storing sentiment analysis results for news articles"""
    __tablename__ = "news_sentiment_analyses"
    
    id = Column(String, primary_key=True, index=True)
    article_id = Column(String, ForeignKey("news_articles.id"), nullable=False)
    compound_score = Column(Float, nullable=False)  # Overall sentiment score
    positive_score = Column(Float, nullable=False)
    negative_score = Column(Float, nullable=False)
    neutral_score = Column(Float, nullable=False)
    financial_bias = Column(Float, nullable=True)  # Financial context adjustment
    adjusted_compound = Column(Float, nullable=True)  # Final adjusted score
    sentiment_label = Column(String(32), nullable=False)  # Descriptive label
    analyzer_version = Column(String(32), nullable=True)  # For tracking algorithm versions
    
    # Raw data
    raw_data = Column(JSONB, nullable=True)  # For storing additional analysis details
    
    # Relationships
    article = relationship("NewsArticle", back_populates="sentiment_analyses")
    
    # Metadata
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    
    def __repr__(self):
        return f"<NewsSentimentAnalysis(id={self.id}, score={self.compound_score}, label='{self.sentiment_label}')>"


class NewsTickerMention(Base):
    """Model for storing ticker mentions in news articles"""
    __tablename__ = "news_ticker_mentions"
    
    id = Column(String, primary_key=True, index=True)
    article_id = Column(String, ForeignKey("news_articles.id"), nullable=False)
    ticker = Column(String(16), nullable=False, index=True)
    company_name = Column(String(256), nullable=True)
    mention_count = Column(Integer, default=1)
    is_primary_subject = Column(Boolean, default=False)  # Whether the article is primarily about this ticker
    
    # Relationships
    article = relationship("NewsArticle", back_populates="ticker_mentions")
    
    # Metadata
    created_at = Column(DateTime, default=datetime.now)
    
    def __repr__(self):
        return f"<NewsTickerMention(ticker='{self.ticker}', article_id={self.article_id}, count={self.mention_count})>"


class MarketSentimentSummary(Base):
    """Model for storing daily market sentiment summary"""
    __tablename__ = "market_sentiment_summaries"
    
    id = Column(String, primary_key=True, index=True)
    date = Column(DateTime, unique=True, index=True)
    overall_sentiment = Column(Float, nullable=False)
    sentiment_label = Column(String(32), nullable=False)
    article_count = Column(Integer, default=0)
    
    # Raw data
    top_positive_tickers = Column(JSONB, nullable=True)
    top_negative_tickers = Column(JSONB, nullable=True)
    most_mentioned_tickers = Column(JSONB, nullable=True)
    sector_sentiments = Column(JSONB, nullable=True)  # Sentiment grouped by sector
    
    # Metadata
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    
    def __repr__(self):
        return f"<MarketSentimentSummary(date='{self.date}', sentiment={self.overall_sentiment})>"


class TickerSentimentHistory(Base):
    """Model for tracking sentiment history for specific tickers"""
    __tablename__ = "ticker_sentiment_histories"
    
    id = Column(String, primary_key=True, index=True)
    ticker = Column(String(16), nullable=False, index=True)
    date = Column(DateTime, nullable=False, index=True)
    sentiment_score = Column(Float, nullable=False)
    sentiment_label = Column(String(32), nullable=False)
    article_count = Column(Integer, default=0)
    
    # Raw data
    article_ids = Column(JSONB, nullable=True)  # List of article IDs related to this sentiment
    
    # Metadata
    created_at = Column(DateTime, default=datetime.now)
    updated_at = Column(DateTime, default=datetime.now, onupdate=datetime.now)
    
    def __repr__(self):
        return f"<TickerSentimentHistory(ticker='{self.ticker}', date='{self.date}', score={self.sentiment_score})>"
