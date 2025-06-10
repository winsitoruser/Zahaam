"""
Database models for news and social media data storage.

This module defines SQLAlchemy ORM models for storing collected news articles,
social media posts, sentiment analysis results, and related metadata.
"""

import uuid
from datetime import datetime
from typing import Dict, List, Any, Optional, Union
from sqlalchemy import (
    Column, Integer, String, Text, Float, Boolean, DateTime,
    ForeignKey, JSON, Enum, Table, UniqueConstraint
)
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.dialects.postgresql import UUID, JSONB
from sqlalchemy.orm import relationship

# Create base model class
Base = declarative_base()

# Association tables for many-to-many relationships
news_ticker_association = Table(
    'news_ticker_association', 
    Base.metadata,
    Column('news_id', UUID(as_uuid=True), ForeignKey('news_articles.id', ondelete='CASCADE')),
    Column('ticker_id', Integer, ForeignKey('stock_tickers.id', ondelete='CASCADE')),
    UniqueConstraint('news_id', 'ticker_id', name='uq_news_ticker')
)

social_ticker_association = Table(
    'social_ticker_association',
    Base.metadata,
    Column('social_post_id', UUID(as_uuid=True), ForeignKey('social_media_posts.id', ondelete='CASCADE')),
    Column('ticker_id', Integer, ForeignKey('stock_tickers.id', ondelete='CASCADE')),
    UniqueConstraint('social_post_id', 'ticker_id', name='uq_social_ticker')
)


class StockTicker(Base):
    """Stock ticker reference table"""
    __tablename__ = 'stock_tickers'
    
    id = Column(Integer, primary_key=True)
    symbol = Column(String(20), unique=True, nullable=False, index=True)
    company_name = Column(String(255))
    sector = Column(String(100))
    industry = Column(String(100))
    country = Column(String(100))
    is_index = Column(Boolean, default=False)
    is_etf = Column(Boolean, default=False)
    is_active = Column(Boolean, default=True)
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    news_articles = relationship(
        "NewsArticle", 
        secondary=news_ticker_association, 
        back_populates="tickers"
    )
    social_posts = relationship(
        "SocialMediaPost", 
        secondary=social_ticker_association, 
        back_populates="tickers"
    )
    
    def __repr__(self):
        return f"<StockTicker(symbol='{self.symbol}', company='{self.company_name}')>"


class DataSource(Base):
    """Source of news or social media data"""
    __tablename__ = 'data_sources'
    
    id = Column(Integer, primary_key=True)
    name = Column(String(100), nullable=False)
    source_type = Column(Enum('news', 'social_media', 'market', name='source_type_enum'), nullable=False)
    platform = Column(String(50), nullable=False)  # e.g., 'twitter', 'reddit', 'newsapi'
    domain = Column(String(255))  # For news sources
    url = Column(String(255))
    reliability_score = Column(Float, default=0.0)  # 0-1 score of source reliability
    sentiment_bias = Column(Float, default=0.0)  # -1 to 1 representing negative to positive bias
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    news_articles = relationship("NewsArticle", back_populates="source")
    social_posts = relationship("SocialMediaPost", back_populates="source")
    
    def __repr__(self):
        return f"<DataSource(name='{self.name}', type='{self.source_type}', platform='{self.platform}')>"


class NewsArticle(Base):
    """News article from various sources"""
    __tablename__ = 'news_articles'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    source_id = Column(Integer, ForeignKey('data_sources.id'), nullable=False)
    author = Column(String(255))
    title = Column(String(500), nullable=False)
    description = Column(Text)
    content = Column(Text)
    url = Column(String(1000), unique=True)
    image_url = Column(String(1000))
    published_at = Column(DateTime, index=True)
    collected_at = Column(DateTime, default=datetime.utcnow)
    
    # Sentiment analysis related fields
    sentiment_score = Column(Float)  # -1.0 to 1.0 scale
    sentiment_magnitude = Column(Float)  # 0.0 to +inf scale of sentiment strength
    sentiment_direction = Column(
        Enum('very_negative', 'negative', 'neutral', 'positive', 'very_positive', name='sentiment_direction_enum')
    )
    sentiment_confidence = Column(Float)  # 0.0 to 1.0 confidence in sentiment classification
    sentiment_details = Column(JSONB)  # Detailed sentiment analysis results
    
    # Content keywords and entities
    keywords = Column(JSONB)  # List of extracted keywords with scores
    entities = Column(JSONB)  # Named entities extracted from text
    
    # Collection metadata
    collection_batch_id = Column(String(100))  # Batch ID for tracking collection runs
    is_processed = Column(Boolean, default=False)  # Flag if article has been processed for sentiment
    processing_errors = Column(Text)  # Any errors during processing
    
    # Relationships
    source = relationship("DataSource", back_populates="news_articles")
    tickers = relationship(
        "StockTicker",
        secondary=news_ticker_association,
        back_populates="news_articles"
    )
    
    def __repr__(self):
        return f"<NewsArticle(title='{self.title[:30]}...', published='{self.published_at}')>"


class SocialMediaAuthor(Base):
    """Author/user of social media content"""
    __tablename__ = 'social_media_authors'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    platform_id = Column(String(255), nullable=False)  # ID from the platform (Twitter ID, etc.)
    platform = Column(String(50), nullable=False)  # Twitter, Reddit, etc.
    username = Column(String(255))
    display_name = Column(String(255))
    verified = Column(Boolean, default=False)
    followers_count = Column(Integer, default=0)
    friends_count = Column(Integer, default=0)
    profile_url = Column(String(1000))
    description = Column(Text)
    location = Column(String(255))
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Author influence and classification
    influence_score = Column(Float, default=0.0)  # 0-1 score of author influence
    is_financial_expert = Column(Boolean, default=False)
    author_category = Column(String(50))  # 'analyst', 'investor', 'ceo', 'institution', etc.
    reliability_score = Column(Float, default=0.0)  # 0-1 score based on historical accuracy
    
    # Relationships
    social_posts = relationship("SocialMediaPost", back_populates="author")
    
    def __repr__(self):
        return f"<SocialMediaAuthor(platform='{self.platform}', username='{self.username}')>"


class SocialMediaPost(Base):
    """Social media post from various platforms"""
    __tablename__ = 'social_media_posts'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    source_id = Column(Integer, ForeignKey('data_sources.id'), nullable=False)
    author_id = Column(UUID(as_uuid=True), ForeignKey('social_media_authors.id'))
    platform_post_id = Column(String(255))  # Original ID from the platform
    content = Column(Text)
    content_url = Column(String(1000))
    post_type = Column(String(50))  # Tweet, Reddit post, comment, etc.
    published_at = Column(DateTime, index=True)
    collected_at = Column(DateTime, default=datetime.utcnow)
    
    # Engagement metrics
    likes_count = Column(Integer, default=0)
    shares_count = Column(Integer, default=0)
    replies_count = Column(Integer, default=0)
    engagement_total = Column(Integer, default=0)
    
    # Sentiment analysis related fields
    sentiment_score = Column(Float)  # -1.0 to 1.0 scale
    sentiment_magnitude = Column(Float)  # 0.0 to +inf scale of sentiment strength
    sentiment_direction = Column(
        Enum('very_negative', 'negative', 'neutral', 'positive', 'very_positive', name='sentiment_direction_enum')
    )
    sentiment_confidence = Column(Float)  # 0.0 to 1.0 confidence in sentiment classification
    sentiment_details = Column(JSONB)  # Detailed sentiment analysis results
    
    # Content features
    keywords = Column(JSONB)  # List of extracted keywords with scores
    entities = Column(JSONB)  # Named entities extracted from text
    hashtags = Column(JSONB)  # Extracted hashtags
    cashtags = Column(JSONB)  # Extracted cashtags ($AAPL, etc.)
    
    # Collection metadata
    collection_batch_id = Column(String(100))  # Batch ID for tracking collection runs
    is_processed = Column(Boolean, default=False)  # Flag if post has been processed for sentiment
    processing_errors = Column(Text)  # Any errors during processing
    
    # Platform-specific data
    platform_metadata = Column(JSONB)  # Additional platform-specific metadata
    
    # Relationships
    source = relationship("DataSource", back_populates="social_posts")
    author = relationship("SocialMediaAuthor", back_populates="social_posts")
    tickers = relationship(
        "StockTicker",
        secondary=social_ticker_association,
        back_populates="social_posts"
    )
    
    def __repr__(self):
        return f"<SocialMediaPost(platform='{self.source.platform}', published='{self.published_at}')>"


class SentimentAggregate(Base):
    """Aggregated sentiment data for tickers and market"""
    __tablename__ = 'sentiment_aggregates'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    ticker_id = Column(Integer, ForeignKey('stock_tickers.id'))
    ticker_symbol = Column(String(20))  # Denormalized for convenience
    
    # Time dimensions
    date = Column(DateTime, index=True)
    timeframe = Column(String(20), index=True)  # '1h', '4h', '1d', '1w', etc.
    
    # Source type breakdown
    news_sentiment_score = Column(Float)
    social_sentiment_score = Column(Float)
    combined_sentiment_score = Column(Float)
    
    # Sentiment direction distributions (percentage in each category)
    very_positive_pct = Column(Float)
    positive_pct = Column(Float)
    neutral_pct = Column(Float)
    negative_pct = Column(Float)
    very_negative_pct = Column(Float)
    
    # Volatility and confidence metrics
    sentiment_volatility = Column(Float)  # Standard deviation of sentiment scores
    sentiment_confidence = Column(Float)  # Overall confidence in the sentiment aggregation
    
    # Volume metrics
    news_volume = Column(Integer)  # Count of news articles
    social_volume = Column(Integer)  # Count of social media posts
    total_volume = Column(Integer)  # Total count of all data points
    
    # Content metrics
    top_keywords = Column(JSONB)  # Most frequent keywords with counts
    top_entities = Column(JSONB)  # Most frequent entities with counts
    
    # Calculation metadata
    created_at = Column(DateTime, default=datetime.utcnow)
    data_points_count = Column(Integer)  # Number of data points in the aggregation
    calculation_metadata = Column(JSONB)  # Details about the calculation process
    
    def __repr__(self):
        return f"<SentimentAggregate(ticker='{self.ticker_symbol}', date='{self.date}', score='{self.combined_sentiment_score}')>"


class SentimentImpact(Base):
    """Analysis of sentiment impact on stock prices"""
    __tablename__ = 'sentiment_impacts'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    ticker_id = Column(Integer, ForeignKey('stock_tickers.id'))
    ticker_symbol = Column(String(20))  # Denormalized for convenience
    
    # Time dimensions
    analysis_date = Column(DateTime)
    timeframe = Column(String(20))  # '1d', '1w', '1m', etc.
    
    # Price metrics at time of analysis
    price_before = Column(Float)
    price_after = Column(Float)
    price_change_pct = Column(Float)
    
    # Sentiment metrics
    sentiment_score_before = Column(Float)
    sentiment_change = Column(Float)
    
    # Impact analysis
    correlation_coefficient = Column(Float)  # Correlation between sentiment and price
    sentiment_impact_score = Column(Float)  # Calculated impact score
    sentiment_lead_time = Column(Integer)  # Minutes between sentiment change and price reaction
    confidence_score = Column(Float)  # Confidence in the impact analysis
    
    # Related events
    related_news_ids = Column(JSONB)  # News articles that may have influenced the price
    related_social_ids = Column(JSONB)  # Social posts that may have influenced the price
    market_events = Column(JSONB)  # Market events during the analysis period
    
    # Analysis metadata
    created_at = Column(DateTime, default=datetime.utcnow)
    analysis_version = Column(String(50))  # Version of the analysis algorithm
    analysis_parameters = Column(JSONB)  # Parameters used in the analysis
    
    def __repr__(self):
        return f"<SentimentImpact(ticker='{self.ticker_symbol}', date='{self.analysis_date}', impact='{self.sentiment_impact_score}')>"


class DataCollectionStats(Base):
    """Statistics about data collection runs"""
    __tablename__ = 'data_collection_stats'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    collection_batch_id = Column(String(100), nullable=False)
    collector_type = Column(String(50), nullable=False)  # 'news', 'twitter', 'reddit', etc.
    start_time = Column(DateTime, nullable=False)
    end_time = Column(DateTime, nullable=False)
    duration_seconds = Column(Float)
    
    # Collection metrics
    requests_count = Column(Integer)
    items_collected = Column(Integer)
    items_new = Column(Integer)
    items_updated = Column(Integer)
    bytes_collected = Column(Integer)
    
    # API metrics
    api_calls = Column(Integer)
    api_rate_limit_remaining = Column(Integer)
    api_rate_limit_reset = Column(DateTime)
    
    # Error tracking
    error_count = Column(Integer, default=0)
    error_details = Column(JSONB)  # Detailed error information
    
    # Performance metrics
    avg_request_time_ms = Column(Float)
    avg_processing_time_ms = Column(Float)
    avg_db_write_time_ms = Column(Float)
    
    def __repr__(self):
        return f"<DataCollectionStats(batch='{self.collection_batch_id}', type='{self.collector_type}', items='{self.items_collected}')>"


def create_tables(engine):
    """Create all database tables"""
    Base.metadata.create_all(engine)


def drop_tables(engine):
    """Drop all database tables"""
    Base.metadata.drop_all(engine)


if __name__ == "__main__":
    # Example of database table creation
    from sqlalchemy import create_engine
    from os import environ
    
    # Get database URL from environment or use default
    db_url = environ.get("POSTGRES_AI_URL", "postgresql://postgres:postgres@localhost:5432/postgres_ai")
    
    # Create engine and tables
    engine = create_engine(db_url)
    create_tables(engine)
    print("Database tables created")
