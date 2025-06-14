"""
Database integration manager for news sentiment analysis

This module handles storing and retrieving news sentiment data from the database
"""

import logging
from typing import Dict, List, Optional, Any
from datetime import datetime, timedelta
from sqlalchemy.orm import Session

from app.models.news_sentiment import (
    NewsArticle, NewsSentimentAnalysis, NewsTickerMention,
    MarketSentimentSummary, TickerSentimentHistory
)
from app.services.news_sentiment_db import NewsSentimentDBService
from app.core.news_sentiment import NewsSentimentAnalyzer

# Configure logging
logger = logging.getLogger(__name__)

class NewsSentimentDBManager:
    """Handles integration between news sentiment analyzer and database"""
    
    def __init__(self, db: Session):
        """Initialize with database session"""
        self.db = db
        self.analyzer = NewsSentimentAnalyzer()
        self.db_service = NewsSentimentDBService()
    
    def analyze_and_store_article(self, article_url: str) -> Dict:
        """
        Analyze article sentiment and store results in database
        
        Args:
            article_url: URL of the article to analyze
            
        Returns:
            Dict with analysis results and storage status
        """
        try:
            # First analyze the article
            analysis_result = self.analyzer.analyze_article(article_url)
            
            if 'error' in analysis_result:
                return analysis_result
            
            # Get article metadata from the scraper
            scraper = get_scraper_for_source(article_url)
            if not scraper:
                return {
                    'error': f"No scraper available for {article_url}",
                    'analysis_result': analysis_result
                }
                
            article_data = scraper.extract_article_content(article_url)
            
            # Store article in database
            article = self.db_service.store_article(
                db=self.db,
                url=article_url,
                title=article_data.get('title', 'Unknown Title'),
                source=scraper.source_name,
                published_date=article_data.get('date', datetime.now()),
                content=article_data.get('content', '')
            )
            
            # Store sentiment analysis
            sentiment = analysis_result.get('sentiment', {})
            sentiment_label = self.analyzer._get_sentiment_label(sentiment.get('adjusted_compound', 0))
            
            sentiment_analysis = self.db_service.store_sentiment_analysis(
                db=self.db,
                article_id=article.id,
                sentiment_data=sentiment,
                sentiment_label=sentiment_label
            )
            
            # Store ticker mentions
            ticker_mentions = analysis_result.get('mentioned_tickers', [])
            
            # Get company names if available
            company_names = {}
            from app.core.news_sentiment import TICKER_TO_COMPANY
            for ticker in ticker_mentions:
                if ticker in TICKER_TO_COMPANY:
                    company_names[ticker] = TICKER_TO_COMPANY[ticker]
            
            # Determine primary ticker (if any)
            primary_ticker = None
            if ticker_mentions:
                # Simple heuristic: the most mentioned ticker is the primary one
                # In a real implementation, this would use more sophisticated analysis
                ticker_count = {}
                for ticker in ticker_mentions:
                    ticker_count[ticker] = ticker_count.get(ticker, 0) + 1
                
                primary_ticker = max(ticker_count.items(), key=lambda x: x[1])[0] if ticker_count else None
            
            ticker_mention_records = self.db_service.store_ticker_mentions(
                db=self.db,
                article_id=article.id,
                ticker_mentions=ticker_mentions,
                company_names=company_names,
                primary_ticker=primary_ticker
            )
            
            return {
                'success': True,
                'article_id': article.id,
                'sentiment_analysis_id': sentiment_analysis.id,
                'ticker_mentions': len(ticker_mention_records),
                'analysis_result': analysis_result
            }
            
        except Exception as e:
            logger.error(f"Error storing article analysis: {str(e)}", exc_info=True)
            return {
                'error': f"Failed to store analysis: {str(e)}",
                'analysis_result': analysis_result if 'analysis_result' in locals() else None
            }
    
    def analyze_and_store_ticker_sentiment(self, ticker: str, days: int = 7) -> Dict:
        """
        Analyze and store sentiment for a specific ticker
        
        Args:
            ticker: Stock ticker symbol
            days: Number of days to analyze
            
        Returns:
            Dict with analysis results and storage status
        """
        try:
            # Analyze ticker sentiment
            analysis_result = self.analyzer.analyze_ticker_sentiment(ticker, days)
            
            if 'error' in analysis_result:
                return analysis_result
            
            # Extract key data
            sentiment_score = analysis_result.get('sentiment_score', 0)
            sentiment_label = analysis_result.get('sentiment_label', 'Netral')
            article_count = analysis_result.get('articles_analyzed', 0)
            
            # Get article IDs
            article_ids = []
            for article_data in analysis_result.get('articles', []):
                # First store each article
                article_url = article_data.get('url')
                if article_url:
                    article_result = self.analyze_and_store_article(article_url)
                    if 'article_id' in article_result:
                        article_ids.append(article_result['article_id'])
            
            # Store ticker sentiment history
            ticker_sentiment = self.db_service.update_ticker_sentiment(
                db=self.db,
                ticker=ticker,
                date=datetime.now(),
                sentiment_score=sentiment_score,
                sentiment_label=sentiment_label,
                article_count=article_count,
                article_ids=article_ids
            )
            
            return {
                'success': True,
                'ticker_sentiment_id': ticker_sentiment.id,
                'analysis_result': analysis_result
            }
            
        except Exception as e:
            logger.error(f"Error storing ticker sentiment: {str(e)}", exc_info=True)
            return {
                'error': f"Failed to store ticker sentiment: {str(e)}",
                'analysis_result': analysis_result if 'analysis_result' in locals() else None
            }
    
    def analyze_and_store_market_sentiment(self, days: int = 3) -> Dict:
        """
        Analyze and store overall market sentiment
        
        Args:
            days: Number of days to analyze
            
        Returns:
            Dict with analysis results and storage status
        """
        try:
            # Analyze market sentiment
            analysis_result = self.analyzer.analyze_market_sentiment(days)
            
            if 'error' in analysis_result:
                return analysis_result
            
            # Extract key data
            sentiment_score = analysis_result.get('sentiment_score', 0)
            sentiment_label = analysis_result.get('sentiment_label', 'Netral')
            article_count = analysis_result.get('articles_analyzed', 0)
            
            # Get top positive and negative tickers
            ticker_sentiments = analysis_result.get('ticker_sentiments', {})
            
            # Sort tickers by sentiment score
            sorted_tickers = sorted(
                [{'ticker': t, **data} for t, data in ticker_sentiments.items()], 
                key=lambda x: x['sentiment_score']
            )
            
            # Get top positive and negative tickers
            top_positive = sorted_tickers[-5:] if len(sorted_tickers) >= 5 else sorted_tickers
            top_negative = sorted_tickers[:5] if len(sorted_tickers) >= 5 else []
            
            # Get most mentioned tickers
            most_mentioned = sorted(
                [{'ticker': t, **data} for t, data in ticker_sentiments.items()], 
                key=lambda x: x['article_count'], 
                reverse=True
            )[:10]  # Top 10
            
            # Store each article
            for article_data in analysis_result.get('articles', []):
                article_url = article_data.get('url')
                if article_url:
                    self.analyze_and_store_article(article_url)
            
            # Store market sentiment summary
            market_summary = self.db_service.update_market_sentiment_summary(
                db=self.db,
                date=datetime.now(),
                overall_sentiment=sentiment_score,
                sentiment_label=sentiment_label,
                article_count=article_count,
                top_positive_tickers=top_positive,
                top_negative_tickers=top_negative,
                most_mentioned_tickers=most_mentioned
            )
            
            # Also update ticker-specific sentiment for each mentioned ticker
            for ticker, data in ticker_sentiments.items():
                if data.get('article_count', 0) > 0:
                    self.db_service.update_ticker_sentiment(
                        db=self.db,
                        ticker=ticker,
                        date=datetime.now(),
                        sentiment_score=data.get('sentiment_score', 0),
                        sentiment_label=data.get('sentiment_label', 'Netral'),
                        article_count=data.get('article_count', 0)
                    )
            
            return {
                'success': True,
                'market_summary_id': market_summary.id,
                'analysis_result': analysis_result
            }
            
        except Exception as e:
            logger.error(f"Error storing market sentiment: {str(e)}", exc_info=True)
            return {
                'error': f"Failed to store market sentiment: {str(e)}",
                'analysis_result': analysis_result if 'analysis_result' in locals() else None
            }
    
    def get_latest_sentiment_data(self, ticker: Optional[str] = None) -> Dict:
        """
        Get the latest sentiment data, either for market or specific ticker
        
        Args:
            ticker: Optional ticker symbol, if None returns market sentiment
            
        Returns:
            Dict with sentiment data
        """
        try:
            if ticker:
                # Get latest ticker sentiment
                ticker_history = self.db_service.get_ticker_sentiment_history(
                    db=self.db, 
                    ticker=ticker,
                    days=30
                )
                
                # Get latest news for this ticker
                latest_news = self.db_service.get_latest_articles(
                    db=self.db,
                    ticker=ticker,
                    limit=5
                )
                
                return {
                    'ticker': ticker,
                    'sentiment_history': ticker_history,
                    'latest_news': latest_news
                }
            else:
                # Get market sentiment history
                market_history = self.db_service.get_market_sentiment_history(
                    db=self.db,
                    days=30
                )
                
                # Get latest market sentiment
                latest_market = market_history[-1] if market_history else None
                
                # Get positive and negative tickers
                positive_tickers = self.db_service.get_most_positive_tickers(
                    db=self.db,
                    limit=5,
                    days=3
                )
                
                negative_tickers = self.db_service.get_most_negative_tickers(
                    db=self.db,
                    limit=5,
                    days=3
                )
                
                # Get latest news
                latest_news = self.db_service.get_latest_articles(
                    db=self.db,
                    limit=5
                )
                
                return {
                    'market_sentiment': latest_market,
                    'sentiment_history': market_history,
                    'positive_tickers': positive_tickers,
                    'negative_tickers': negative_tickers,
                    'latest_news': latest_news
                }
                
        except Exception as e:
            logger.error(f"Error getting latest sentiment: {str(e)}", exc_info=True)
            return {'error': f"Failed to get latest sentiment data: {str(e)}"}


# Import necessary modules for function
from app.core.news_scrapers.factory import get_scraper_for_source

def get_sentiment_db_manager(db: Session) -> NewsSentimentDBManager:
    """
    Factory function to get a sentiment DB manager instance
    
    Args:
        db: Database session
        
    Returns:
        NewsSentimentDBManager instance
    """
    return NewsSentimentDBManager(db)
