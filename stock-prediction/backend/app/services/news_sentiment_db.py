"""
Database service for news sentiment analysis storage and retrieval
"""

from sqlalchemy.orm import Session
from sqlalchemy import func, desc, and_, or_
import hashlib
import logging
from typing import Dict, List, Optional, Any
from datetime import datetime, timedelta

from app.models.news_sentiment import (
    NewsArticle, 
    NewsSentimentAnalysis, 
    NewsTickerMention,
    MarketSentimentSummary,
    TickerSentimentHistory
)

# Configure logging
logger = logging.getLogger(__name__)

class NewsSentimentDBService:
    """Service for interacting with news sentiment database models"""
    
    @staticmethod
    def generate_content_hash(content: str) -> str:
        """Generate hash from article content to detect duplicates"""
        return hashlib.sha256(content.encode('utf-8')).hexdigest()
    
    @staticmethod
    def store_article(
        db: Session, 
        url: str, 
        title: str, 
        source: str,
        published_date: datetime,
        content: Optional[str] = None
    ) -> NewsArticle:
        """
        Store a news article in the database, avoiding duplicates
        
        Args:
            db: Database session
            url: Article URL
            title: Article title
            source: Source name (e.g., "Bisnis.com")
            published_date: Article publication date
            content: Optional article content text
            
        Returns:
            NewsArticle: Stored article model
        """
        # Check if article already exists
        existing_article = db.query(NewsArticle).filter(NewsArticle.url == url).first()
        
        if existing_article:
            # Update existing article if needed
            if content and not existing_article.content:
                # Calculate content hash
                content_hash = NewsSentimentDBService.generate_content_hash(content)
                
                existing_article.content = content
                existing_article.content_hash = content_hash
                existing_article.updated_at = datetime.now()
                
                # Commit changes
                db.commit()
                db.refresh(existing_article)
            
            return existing_article
        
        # Create new article
        content_hash = None
        if content:
            content_hash = NewsSentimentDBService.generate_content_hash(content)
            
        new_article = NewsArticle(
            url=url,
            title=title,
            source=source,
            published_date=published_date,
            content=content,
            content_hash=content_hash
        )
        
        db.add(new_article)
        db.commit()
        db.refresh(new_article)
        
        return new_article
    
    @staticmethod
    def store_sentiment_analysis(
        db: Session,
        article_id: int,
        sentiment_data: Dict[str, float],
        sentiment_label: str,
        analyzer_version: str = "1.0",
        raw_data: Optional[Dict] = None
    ) -> NewsSentimentAnalysis:
        """
        Store sentiment analysis results for an article
        
        Args:
            db: Database session
            article_id: ID of the article
            sentiment_data: Dict of sentiment scores
            sentiment_label: Sentiment category label
            analyzer_version: Version of the analyzer used
            raw_data: Optional additional analysis data
            
        Returns:
            NewsSentimentAnalysis: Stored sentiment analysis model
        """
        # Check if analysis already exists for this article
        existing_analysis = db.query(NewsSentimentAnalysis).filter(
            NewsSentimentAnalysis.article_id == article_id
        ).first()
        
        if existing_analysis:
            # Update existing analysis
            existing_analysis.compound_score = sentiment_data.get('compound', 0)
            existing_analysis.positive_score = sentiment_data.get('positive', 0)
            existing_analysis.negative_score = sentiment_data.get('negative', 0)
            existing_analysis.neutral_score = sentiment_data.get('neutral', 0)
            existing_analysis.financial_bias = sentiment_data.get('financial_bias')
            existing_analysis.adjusted_compound = sentiment_data.get('adjusted_compound')
            existing_analysis.sentiment_label = sentiment_label
            existing_analysis.analyzer_version = analyzer_version
            existing_analysis.raw_data = raw_data
            existing_analysis.updated_at = datetime.now()
            
            # Commit changes
            db.commit()
            db.refresh(existing_analysis)
            
            return existing_analysis
        
        # Create new analysis
        new_analysis = NewsSentimentAnalysis(
            article_id=article_id,
            compound_score=sentiment_data.get('compound', 0),
            positive_score=sentiment_data.get('positive', 0),
            negative_score=sentiment_data.get('negative', 0),
            neutral_score=sentiment_data.get('neutral', 0),
            financial_bias=sentiment_data.get('financial_bias'),
            adjusted_compound=sentiment_data.get('adjusted_compound'),
            sentiment_label=sentiment_label,
            analyzer_version=analyzer_version,
            raw_data=raw_data
        )
        
        db.add(new_analysis)
        db.commit()
        db.refresh(new_analysis)
        
        return new_analysis
    
    @staticmethod
    def store_ticker_mentions(
        db: Session,
        article_id: int,
        ticker_mentions: List[str],
        company_names: Optional[Dict[str, str]] = None,
        primary_ticker: Optional[str] = None
    ) -> List[NewsTickerMention]:
        """
        Store ticker mentions for an article
        
        Args:
            db: Database session
            article_id: ID of the article
            ticker_mentions: List of tickers mentioned
            company_names: Optional dict mapping tickers to company names
            primary_ticker: Optional ticker that is the main subject
            
        Returns:
            List[NewsTickerMention]: List of stored ticker mention models
        """
        # Delete existing mentions for this article
        db.query(NewsTickerMention).filter(
            NewsTickerMention.article_id == article_id
        ).delete()
        
        # Create ticker mention counts
        ticker_count = {}
        for ticker in ticker_mentions:
            ticker_count[ticker] = ticker_count.get(ticker, 0) + 1
        
        # Store new mentions
        result = []
        for ticker, count in ticker_count.items():
            company_name = None
            if company_names and ticker in company_names:
                company_name = company_names[ticker]
                
            is_primary = ticker == primary_ticker if primary_ticker else False
            
            new_mention = NewsTickerMention(
                article_id=article_id,
                ticker=ticker,
                company_name=company_name,
                mention_count=count,
                is_primary_subject=is_primary
            )
            
            db.add(new_mention)
            result.append(new_mention)
        
        db.commit()
        for item in result:
            db.refresh(item)
        
        return result
    
    @staticmethod
    def update_market_sentiment_summary(
        db: Session,
        date: datetime,
        overall_sentiment: float,
        sentiment_label: str,
        article_count: int,
        top_positive_tickers: Optional[List[Dict]] = None,
        top_negative_tickers: Optional[List[Dict]] = None,
        most_mentioned_tickers: Optional[List[Dict]] = None,
        sector_sentiments: Optional[Dict] = None
    ) -> MarketSentimentSummary:
        """
        Update or create market sentiment summary for a specific date
        
        Args:
            db: Database session
            date: Date of the summary
            overall_sentiment: Average sentiment score
            sentiment_label: Sentiment category label
            article_count: Number of articles analyzed
            top_positive_tickers: Optional list of most positive tickers
            top_negative_tickers: Optional list of most negative tickers
            most_mentioned_tickers: Optional list of most mentioned tickers
            sector_sentiments: Optional dict of sector sentiments
            
        Returns:
            MarketSentimentSummary: Updated or new summary model
        """
        # Get date without time component for uniqueness
        date_only = date.replace(hour=0, minute=0, second=0, microsecond=0)
        
        # Check if summary already exists for this date
        existing_summary = db.query(MarketSentimentSummary).filter(
            func.date(MarketSentimentSummary.date) == func.date(date_only)
        ).first()
        
        if existing_summary:
            # Update existing summary
            existing_summary.overall_sentiment = overall_sentiment
            existing_summary.sentiment_label = sentiment_label
            existing_summary.article_count = article_count
            
            if top_positive_tickers:
                existing_summary.top_positive_tickers = top_positive_tickers
                
            if top_negative_tickers:
                existing_summary.top_negative_tickers = top_negative_tickers
                
            if most_mentioned_tickers:
                existing_summary.most_mentioned_tickers = most_mentioned_tickers
                
            if sector_sentiments:
                existing_summary.sector_sentiments = sector_sentiments
                
            existing_summary.updated_at = datetime.now()
            
            # Commit changes
            db.commit()
            db.refresh(existing_summary)
            
            return existing_summary
        
        # Create new summary
        new_summary = MarketSentimentSummary(
            date=date_only,
            overall_sentiment=overall_sentiment,
            sentiment_label=sentiment_label,
            article_count=article_count,
            top_positive_tickers=top_positive_tickers,
            top_negative_tickers=top_negative_tickers,
            most_mentioned_tickers=most_mentioned_tickers,
            sector_sentiments=sector_sentiments
        )
        
        db.add(new_summary)
        db.commit()
        db.refresh(new_summary)
        
        return new_summary
    
    @staticmethod
    def update_ticker_sentiment(
        db: Session,
        ticker: str,
        date: datetime,
        sentiment_score: float,
        sentiment_label: str,
        article_count: int,
        article_ids: Optional[List[int]] = None
    ) -> TickerSentimentHistory:
        """
        Update or create ticker sentiment for a specific date
        
        Args:
            db: Database session
            ticker: Stock ticker symbol
            date: Date of the sentiment
            sentiment_score: Sentiment score
            sentiment_label: Sentiment category label
            article_count: Number of articles analyzed
            article_ids: Optional list of article IDs
            
        Returns:
            TickerSentimentHistory: Updated or new ticker sentiment model
        """
        # Get date without time component for uniqueness
        date_only = date.replace(hour=0, minute=0, second=0, microsecond=0)
        
        # Check if entry already exists for this ticker and date
        existing_entry = db.query(TickerSentimentHistory).filter(
            and_(
                TickerSentimentHistory.ticker == ticker,
                func.date(TickerSentimentHistory.date) == func.date(date_only)
            )
        ).first()
        
        if existing_entry:
            # Update existing entry
            existing_entry.sentiment_score = sentiment_score
            existing_entry.sentiment_label = sentiment_label
            existing_entry.article_count = article_count
            
            if article_ids:
                existing_entry.article_ids = article_ids
                
            existing_entry.updated_at = datetime.now()
            
            # Commit changes
            db.commit()
            db.refresh(existing_entry)
            
            return existing_entry
        
        # Create new entry
        new_entry = TickerSentimentHistory(
            ticker=ticker,
            date=date_only,
            sentiment_score=sentiment_score,
            sentiment_label=sentiment_label,
            article_count=article_count,
            article_ids=article_ids
        )
        
        db.add(new_entry)
        db.commit()
        db.refresh(new_entry)
        
        return new_entry
    
    @staticmethod
    def get_latest_articles(
        db: Session, 
        limit: int = 50, 
        ticker: Optional[str] = None,
        days: int = 7
    ) -> List[Dict]:
        """
        Get the latest news articles with their sentiment analysis
        
        Args:
            db: Database session
            limit: Maximum number of results
            ticker: Optional ticker filter
            days: Number of days to look back
            
        Returns:
            List[Dict]: List of articles with sentiment data
        """
        # Base query for articles
        query = db.query(NewsArticle)
        
        # Apply date filter
        if days > 0:
            cutoff_date = datetime.now() - timedelta(days=days)
            query = query.filter(NewsArticle.published_date >= cutoff_date)
        
        # Apply ticker filter if specified
        if ticker:
            query = query.join(NewsTickerMention).filter(NewsTickerMention.ticker == ticker)
        
        # Order by latest first and limit results
        query = query.order_by(desc(NewsArticle.published_date)).limit(limit)
        
        # Execute query
        articles = query.all()
        
        # Format results
        result = []
        for article in articles:
            # Get sentiment data if available
            sentiment_data = None
            if article.sentiment_analyses:
                sentiment = article.sentiment_analyses[0]
                sentiment_data = {
                    "compound_score": sentiment.compound_score,
                    "adjusted_compound": sentiment.adjusted_compound,
                    "sentiment_label": sentiment.sentiment_label
                }
            
            # Get ticker mentions
            mentioned_tickers = [
                {"ticker": mention.ticker, "count": mention.mention_count, 
                 "is_primary": mention.is_primary_subject}
                for mention in article.ticker_mentions
            ]
            
            # Format article
            article_data = {
                "id": article.id,
                "url": article.url,
                "title": article.title,
                "source": article.source,
                "published_date": article.published_date.isoformat(),
                "sentiment": sentiment_data,
                "mentioned_tickers": mentioned_tickers
            }
            
            result.append(article_data)
        
        return result
    
    @staticmethod
    def get_market_sentiment_history(
        db: Session, 
        days: int = 30
    ) -> List[Dict]:
        """
        Get market sentiment history for the specified number of days
        
        Args:
            db: Database session
            days: Number of days to look back
            
        Returns:
            List[Dict]: List of daily market sentiment summaries
        """
        # Calculate cutoff date
        cutoff_date = datetime.now() - timedelta(days=days)
        
        # Query for market sentiment summaries
        summaries = db.query(MarketSentimentSummary).\
            filter(MarketSentimentSummary.date >= cutoff_date).\
            order_by(MarketSentimentSummary.date).all()
        
        # Format results
        result = []
        for summary in summaries:
            summary_data = {
                "date": summary.date.strftime("%Y-%m-%d"),
                "sentiment_score": summary.overall_sentiment,
                "sentiment_label": summary.sentiment_label,
                "article_count": summary.article_count,
                "top_positive_tickers": summary.top_positive_tickers,
                "top_negative_tickers": summary.top_negative_tickers,
                "most_mentioned_tickers": summary.most_mentioned_tickers
            }
            
            result.append(summary_data)
        
        return result
    
    @staticmethod
    def get_ticker_sentiment_history(
        db: Session, 
        ticker: str,
        days: int = 30
    ) -> List[Dict]:
        """
        Get sentiment history for a specific ticker
        
        Args:
            db: Database session
            ticker: Stock ticker symbol
            days: Number of days to look back
            
        Returns:
            List[Dict]: List of daily ticker sentiment entries
        """
        # Calculate cutoff date
        cutoff_date = datetime.now() - timedelta(days=days)
        
        # Query for ticker sentiment history
        history = db.query(TickerSentimentHistory).\
            filter(
                and_(
                    TickerSentimentHistory.ticker == ticker,
                    TickerSentimentHistory.date >= cutoff_date
                )
            ).\
            order_by(TickerSentimentHistory.date).all()
        
        # Format results
        result = []
        for entry in history:
            entry_data = {
                "date": entry.date.strftime("%Y-%m-%d"),
                "sentiment_score": entry.sentiment_score,
                "sentiment_label": entry.sentiment_label,
                "article_count": entry.article_count
            }
            
            result.append(entry_data)
        
        return result
    
    @staticmethod
    def get_most_positive_tickers(
        db: Session, 
        limit: int = 10,
        days: int = 3
    ) -> List[Dict]:
        """
        Get tickers with the most positive sentiment
        
        Args:
            db: Database session
            limit: Maximum number of results
            days: Number of days to look back
            
        Returns:
            List[Dict]: List of tickers with positive sentiment
        """
        # Calculate cutoff date
        cutoff_date = datetime.now() - timedelta(days=days)
        
        # Subquery to get average sentiment by ticker
        ticker_sentiments = db.query(
            TickerSentimentHistory.ticker,
            func.avg(TickerSentimentHistory.sentiment_score).label('avg_sentiment'),
            func.sum(TickerSentimentHistory.article_count).label('article_count')
        ).\
            filter(TickerSentimentHistory.date >= cutoff_date).\
            group_by(TickerSentimentHistory.ticker).\
            having(func.sum(TickerSentimentHistory.article_count) >= 2).\
            order_by(desc('avg_sentiment')).\
            limit(limit).all()
        
        # Format results
        result = []
        for ticker, avg_sentiment, article_count in ticker_sentiments:
            result.append({
                "ticker": ticker,
                "sentiment_score": round(float(avg_sentiment), 3),
                "sentiment_label": NewsSentimentDBService._get_sentiment_label(avg_sentiment),
                "article_count": article_count
            })
        
        return result
    
    @staticmethod
    def get_most_negative_tickers(
        db: Session, 
        limit: int = 10,
        days: int = 3
    ) -> List[Dict]:
        """
        Get tickers with the most negative sentiment
        
        Args:
            db: Database session
            limit: Maximum number of results
            days: Number of days to look back
            
        Returns:
            List[Dict]: List of tickers with negative sentiment
        """
        # Calculate cutoff date
        cutoff_date = datetime.now() - timedelta(days=days)
        
        # Subquery to get average sentiment by ticker
        ticker_sentiments = db.query(
            TickerSentimentHistory.ticker,
            func.avg(TickerSentimentHistory.sentiment_score).label('avg_sentiment'),
            func.sum(TickerSentimentHistory.article_count).label('article_count')
        ).\
            filter(TickerSentimentHistory.date >= cutoff_date).\
            group_by(TickerSentimentHistory.ticker).\
            having(func.sum(TickerSentimentHistory.article_count) >= 2).\
            order_by('avg_sentiment').\
            limit(limit).all()
        
        # Format results
        result = []
        for ticker, avg_sentiment, article_count in ticker_sentiments:
            result.append({
                "ticker": ticker,
                "sentiment_score": round(float(avg_sentiment), 3),
                "sentiment_label": NewsSentimentDBService._get_sentiment_label(avg_sentiment),
                "article_count": article_count
            })
        
        return result
    
    @staticmethod
    def _get_sentiment_label(score: float) -> str:
        """Convert sentiment score to label"""
        if score >= 0.5:
            return 'Sangat Positif'
        elif score >= 0.2:
            return 'Positif'
        elif score <= -0.5:
            return 'Sangat Negatif'
        elif score <= -0.2:
            return 'Negatif'
        else:
            return 'Netral'
