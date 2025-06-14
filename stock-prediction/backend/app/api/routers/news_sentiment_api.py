"""
API endpoints for news sentiment analysis

These endpoints provide access to sentiment analysis of Indonesian financial news,
with persistent storage in the database.
"""

from fastapi import APIRouter, HTTPException, Query, Path, Depends
from typing import List, Dict, Any, Optional
from datetime import datetime
import logging

from app.core.news_sentiment import get_sentiment_analyzer
from app.core.news_scraper import aggregate_news
from app.core.news_sentiment_db_manager import get_sentiment_db_manager
from app.core.database import get_db
from sqlalchemy.orm import Session

# Configure logging
logger = logging.getLogger(__name__)

# Create router
router = APIRouter(
    prefix="/api/news-sentiment",
    tags=["news-sentiment"],
    responses={404: {"description": "Not found"}},
)


@router.get("/market")
async def get_market_sentiment(
    days: int = Query(3, description="Number of days to look back"),
    store: bool = Query(True, description="Whether to store results in database"),
    db: Session = Depends(get_db)
):
    """
    Get overall market sentiment from recent financial news
    
    Returns sentiment analysis of market news across all sources.
    Results are stored in the database for future reference.
    """
    try:
        if store:
            # Use DB manager to analyze and store results
            db_manager = get_sentiment_db_manager(db)
            result = db_manager.analyze_and_store_market_sentiment(days=days)
        else:
            # Just analyze without storing
            analyzer = get_sentiment_analyzer()
            result = analyzer.analyze_market_sentiment(days=days)
        
        return result
    except Exception as e:
        logger.error(f"Error getting market sentiment: {str(e)}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Error analyzing market sentiment: {str(e)}")


@router.get("/ticker/{ticker}")
async def get_ticker_sentiment(
    ticker: str = Path(..., description="Stock ticker symbol"),
    days: int = Query(7, description="Number of days to look back"),
    store: bool = Query(True, description="Whether to store results in database"),
    db: Session = Depends(get_db)
):
    """
    Get sentiment for a specific ticker based on recent news
    
    Returns sentiment analysis of news mentioning the specified ticker.
    Results are stored in the database for future reference.
    """
    try:
        if store:
            # Use DB manager to analyze and store results
            db_manager = get_sentiment_db_manager(db)
            result = db_manager.analyze_and_store_ticker_sentiment(ticker=ticker, days=days)
        else:
            # Just analyze without storing
            analyzer = get_sentiment_analyzer()
            result = analyzer.analyze_ticker_sentiment(ticker=ticker, days=days)
            
        return result
    except Exception as e:
        logger.error(f"Error getting sentiment for {ticker}: {str(e)}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Error analyzing sentiment for {ticker}: {str(e)}")


@router.get("/latest-news")
async def get_latest_news(
    days: int = Query(3, description="Number of days to look back"),
    ticker: Optional[str] = Query(None, description="Optional ticker to filter by"),
    use_db: bool = Query(False, description="Whether to fetch from database instead of scraping"),
    db: Session = Depends(get_db)
):
    """
    Get the latest news articles from all sources
    
    Optionally filter by ticker symbol. If use_db=True, retrieves from database,
    otherwise scrapes new content.
    """
    try:
        if use_db:
            # Get articles from database
            from app.services.news_sentiment_db import NewsSentimentDBService
            db_service = NewsSentimentDBService()
            articles = db_service.get_latest_articles(
                db=db,
                ticker=ticker,
                days=days,
                limit=50
            )
            return {
                "articles": articles,
                "count": len(articles),
                "ticker": ticker,
                "days": days,
                "source": "database"
            }
        else:
            # Get articles from scrapers
            articles = aggregate_news(ticker=ticker, days=days)
            
            # Format response - ensure dates are strings
            formatted_articles = []
            for article in articles:
                article_copy = article.copy()
                if isinstance(article_copy.get('date'), datetime):
                    article_copy['date'] = article_copy['date'].strftime("%Y-%m-%d %H:%M:%S")
                formatted_articles.append(article_copy)
                
            return {
                "articles": formatted_articles,
                "count": len(formatted_articles),
                "ticker": ticker,
                "days": days,
                "source": "scrapers"
            }
    except Exception as e:
        logger.error(f"Error getting latest news: {str(e)}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Error fetching latest news: {str(e)}")


@router.get("/analyze-article")
async def analyze_article(
    url: str = Query(..., description="URL of the article to analyze"),
    store: bool = Query(True, description="Whether to store results in database"),
    db: Session = Depends(get_db)
):
    """
    Analyze sentiment for a specific article URL
    
    Returns detailed sentiment analysis of the given article.
    Results are stored in the database for future reference if store=True.
    """
    try:
        if store:
            # Use DB manager to analyze and store results
            db_manager = get_sentiment_db_manager(db)
            result = db_manager.analyze_and_store_article(url)
        else:
            # Just analyze without storing
            analyzer = get_sentiment_analyzer()
            result = analyzer.analyze_article(url)
            
        return result
    except Exception as e:
        logger.error(f"Error analyzing article {url}: {str(e)}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Error analyzing article: {str(e)}")


@router.get("/db/market")
async def get_db_market_sentiment(
    days: int = Query(30, description="Number of days of history to return"),
    db: Session = Depends(get_db)
):
    """
    Get market sentiment data from database
    
    Returns historical market sentiment from the database
    """
    try:
        db_manager = get_sentiment_db_manager(db)
        result = db_manager.get_latest_sentiment_data()
        return result
    except Exception as e:
        logger.error(f"Error getting DB market sentiment: {str(e)}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Error retrieving market sentiment: {str(e)}")


@router.get("/db/ticker/{ticker}")
async def get_db_ticker_sentiment(
    ticker: str = Path(..., description="Stock ticker symbol"),
    days: int = Query(30, description="Number of days of history to return"),
    db: Session = Depends(get_db)
):
    """
    Get ticker sentiment data from database
    
    Returns historical sentiment for a ticker from the database
    """
    try:
        db_manager = get_sentiment_db_manager(db)
        result = db_manager.get_latest_sentiment_data(ticker=ticker)
        return result
    except Exception as e:
        logger.error(f"Error getting DB ticker sentiment for {ticker}: {str(e)}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Error retrieving ticker sentiment: {str(e)}")

