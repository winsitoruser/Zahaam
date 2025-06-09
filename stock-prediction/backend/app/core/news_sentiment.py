"""
News sentiment analysis for Indonesian financial news

This module analyzes sentiment from Indonesian financial news articles
and extracts sentiment scores related to specific stocks or the market in general.
"""

import pandas as pd
import numpy as np
import re
import logging
from typing import Dict, List, Any, Optional, Tuple
from datetime import datetime, timedelta
import nltk
from nltk.sentiment import SentimentIntensityAnalyzer
import os

# For Indonesian sentiment analysis
# Multilingual libraries or dictionaries would be preferred
import pickle

# Import news scraper
from app.core.news_scrapers.factory import aggregate_news, get_scraper_for_source

# Configure logging
logger = logging.getLogger(__name__)

# Dictionary of Indonesian stock tickers to company names
# This will help identify which companies are mentioned in articles
# Will be expanded with a more comprehensive list
TICKER_TO_COMPANY = {
    'BBCA': 'Bank Central Asia',
    'BBRI': 'Bank Rakyat Indonesia',
    'ASII': 'Astra International',
    'TLKM': 'Telkom Indonesia',
    'UNVR': 'Unilever Indonesia',
    'BMRI': 'Bank Mandiri',
    'HMSP': 'HM Sampoerna',
    'ICBP': 'Indofood CBP',
    'INDF': 'Indofood Sukses Makmur',
    'KLBF': 'Kalbe Farma',
    'BBNI': 'Bank Negara Indonesia',
    'ADRO': 'Adaro Energy',
    'GGRM': 'Gudang Garam',
    'PTBA': 'Bukit Asam',
    'WIKA': 'Wijaya Karya',
    # To be expanded with more tickers
}

# Indonesian finance-specific sentiment words
# These would ideally be expanded and refined with domain expertise
POSITIVE_WORDS = [
    'naik', 'meningkat', 'bullish', 'positif', 'untung', 'laba', 
    'menguat', 'optimis', 'berhasil', 'rekomendasi', 'target', 'potensial',
    'peluang', 'pertumbuhan', 'ekspansi', 'dividen', 'efisiensi'
]

NEGATIVE_WORDS = [
    'turun', 'menurun', 'bearish', 'negatif', 'rugi', 'melemah',
    'tekanan', 'pesimis', 'gagal', 'jual', 'beban', 'risiko', 
    'kendala', 'tantangan', 'kerugian', 'penurunan', 'koreksi'
]

NEUTRAL_WORDS = [
    'tetap', 'stabil', 'flat', 'konsolidasi', 'sideways',
    'mixed', 'tunggu', 'pantau', 'evaluasi'
]

# Make sure NLTK resources are downloaded
try:
    nltk.data.find('sentiment/vader_lexicon.zip')
except LookupError:
    nltk.download('vader_lexicon')
    nltk.download('punkt')


class NewsSentimentAnalyzer:
    """Analyzes sentiment from financial news articles"""
    
    def __init__(self):
        """Initialize the sentiment analyzer"""
        self.sia = SentimentIntensityAnalyzer()
        # Load or create cache for articles
        self.cache_dir = os.path.join(os.path.dirname(os.path.dirname(__file__)), "cache")
        if not os.path.exists(self.cache_dir):
            os.makedirs(self.cache_dir)
        
        # Add finance-specific sentiment words to the lexicon
        self._update_sentiment_lexicon()
    
    def _update_sentiment_lexicon(self):
        """Update the sentiment lexicon with finance-specific terms"""
        # Add Indonesian financial terms to the VADER lexicon
        for word in POSITIVE_WORDS:
            self.sia.lexicon[word] = 2.0  # Strong positive
        
        for word in NEGATIVE_WORDS:
            self.sia.lexicon[word] = -2.0  # Strong negative
        
        for word in NEUTRAL_WORDS:
            self.sia.lexicon[word] = 0.0  # Neutral
    
    def analyze_text(self, text: str) -> Dict[str, float]:
        """Analyze sentiment in a text snippet"""
        # Convert text to lowercase for better matching
        text_lower = text.lower()
        
        # Use VADER for general sentiment
        sentiment_scores = self.sia.polarity_scores(text_lower)
        
        # Add custom finance-specific analysis
        # Count occurrences of positive/negative words
        positive_count = sum(1 for word in POSITIVE_WORDS if word in text_lower)
        negative_count = sum(1 for word in NEGATIVE_WORDS if word in text_lower)
        
        # Adjust compound score based on financial terms
        financial_bias = (positive_count - negative_count) / max(1, positive_count + negative_count)
        
        # Combine VADER score with financial bias (weighted)
        adjusted_compound = (sentiment_scores['compound'] * 0.7) + (financial_bias * 0.3)
        
        return {
            'compound': sentiment_scores['compound'],
            'positive': sentiment_scores['pos'],
            'negative': sentiment_scores['neg'],
            'neutral': sentiment_scores['neu'],
            'financial_bias': financial_bias,
            'adjusted_compound': min(1.0, max(-1.0, adjusted_compound))
        }
    
    def _extract_ticker_mentions(self, text: str) -> List[str]:
        """Extract potential ticker symbols from text"""
        # Pattern for detecting stock tickers (typically 4 uppercase letters for IDX)
        # This is a simplified approach - would need refinement
        ticker_pattern = r'\b[A-Z]{4}\b'
        potential_tickers = re.findall(ticker_pattern, text)
        
        # Filter to only include valid tickers
        valid_tickers = [ticker for ticker in potential_tickers if ticker in TICKER_TO_COMPANY]
        return valid_tickers
    
    def _extract_company_mentions(self, text: str) -> List[str]:
        """Extract company name mentions from text"""
        mentioned_companies = []
        text_lower = text.lower()
        
        for ticker, company in TICKER_TO_COMPANY.items():
            if company.lower() in text_lower:
                mentioned_companies.append(ticker)
        
        return mentioned_companies
    
    def analyze_article(self, article_url: str) -> Dict:
        """Analyze the sentiment of a specific article"""
        # Check if we have a scraper for this URL
        scraper = get_scraper_for_source(article_url)
        
        if not scraper:
            return {
                'url': article_url,
                'error': 'No scraper available for this news source'
            }
        
        # Extract the article content
        article_data = scraper.extract_article_content(article_url)
        
        if 'error' in article_data:
            return article_data
        
        content = article_data.get('content', '')
        
        # Extract ticker mentions
        tickers_in_content = set(article_data.get('mentioned_tickers', []))
        company_mentions = set(self._extract_company_mentions(content))
        
        # Combine ticker mentions
        all_tickers = tickers_in_content.union(company_mentions)
        
        # Analyze the overall sentiment
        sentiment = self.analyze_text(content)
        
        result = {
            'url': article_url,
            'sentiment': sentiment,
            'mentioned_tickers': list(all_tickers)
        }
        
        return result
    
    def analyze_ticker_sentiment(self, ticker: str, days: int = 7) -> Dict:
        """
        Analyze overall sentiment for a specific ticker over recent days
        
        Args:
            ticker: Stock ticker symbol
            days: Number of days to look back
            
        Returns:
            Dict with sentiment stats and article details
        """
        # Get recent articles for this ticker
        articles = aggregate_news(ticker=ticker, days=days)
        
        if not articles:
            return {
                'ticker': ticker,
                'error': f'No articles found for {ticker} in the past {days} days',
                'articles_analyzed': 0,
                'sentiment_score': 0,
                'articles': []
            }
        
        # Analyze each article
        analyzed_articles = []
        sentiment_scores = []
        
        for article in articles:
            # Get article URL
            url = article.get('url')
            
            if not url:
                continue
            
            # Analyze article sentiment
            sentiment_data = self.analyze_article(url)
            
            if 'sentiment' in sentiment_data:
                sentiment_scores.append(sentiment_data['sentiment']['adjusted_compound'])
                
                analyzed_articles.append({
                    'url': url,
                    'title': article.get('title', ''),
                    'date': article.get('date').strftime('%Y-%m-%d') if isinstance(article.get('date'), datetime) else article.get('date'),
                    'source': article.get('source', ''),
                    'sentiment_score': sentiment_data['sentiment']['adjusted_compound']
                })
        
        # Calculate overall sentiment
        overall_sentiment = np.mean(sentiment_scores) if sentiment_scores else 0
        
        return {
            'ticker': ticker,
            'articles_analyzed': len(analyzed_articles),
            'sentiment_score': float(overall_sentiment),
            'sentiment_label': self._get_sentiment_label(overall_sentiment),
            'articles': analyzed_articles
        }
    
    def analyze_market_sentiment(self, days: int = 3) -> Dict:
        """
        Analyze overall market sentiment from recent news
        
        Args:
            days: Number of days to look back
            
        Returns:
            Dict with market sentiment and top articles
        """
        # Get recent market news (not ticker-specific)
        articles = aggregate_news(days=days)
        
        if not articles:
            return {
                'error': f'No articles found for market in the past {days} days',
                'articles_analyzed': 0,
                'sentiment_score': 0,
                'articles': []
            }
        
        # Analyze each article
        analyzed_articles = []
        sentiment_scores = []
        ticker_sentiments = {}
        
        for article in articles:
            # Get article URL
            url = article.get('url')
            
            if not url:
                continue
            
            # Analyze article sentiment
            sentiment_data = self.analyze_article(url)
            
            if 'sentiment' in sentiment_data:
                sentiment_score = sentiment_data['sentiment']['adjusted_compound']
                sentiment_scores.append(sentiment_score)
                
                # Record mentioned tickers and associate sentiment
                for ticker in sentiment_data.get('mentioned_tickers', []):
                    if ticker not in ticker_sentiments:
                        ticker_sentiments[ticker] = []
                    ticker_sentiments[ticker].append(sentiment_score)
                
                analyzed_articles.append({
                    'url': url,
                    'title': article.get('title', ''),
                    'date': article.get('date').strftime('%Y-%m-%d') if isinstance(article.get('date'), datetime) else article.get('date'),
                    'source': article.get('source', ''),
                    'sentiment_score': sentiment_score
                })
        
        # Calculate overall sentiment
        overall_sentiment = np.mean(sentiment_scores) if sentiment_scores else 0
        
        # Calculate ticker-specific sentiments
        ticker_results = {}
        for ticker, scores in ticker_sentiments.items():
            avg_score = np.mean(scores)
            ticker_results[ticker] = {
                'sentiment_score': float(avg_score),
                'sentiment_label': self._get_sentiment_label(avg_score),
                'article_count': len(scores)
            }
        
        return {
            'articles_analyzed': len(analyzed_articles),
            'sentiment_score': float(overall_sentiment),
            'sentiment_label': self._get_sentiment_label(overall_sentiment),
            'ticker_sentiments': ticker_results,
            'articles': sorted(analyzed_articles, key=lambda x: x['sentiment_score'], reverse=True)[:10]  # Top 10 most positive
        }
    
    def _get_sentiment_label(self, score: float) -> str:
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


# Factory function
def get_sentiment_analyzer() -> NewsSentimentAnalyzer:
    """Get sentiment analyzer instance"""
    return NewsSentimentAnalyzer()
