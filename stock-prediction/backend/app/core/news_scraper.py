"""
News scraper module for Indonesian financial news sources.

This module scrapes financial news from various Indonesian sources
and extracts relevant information for sentiment analysis.
"""

import requests
from bs4 import BeautifulSoup
import pandas as pd
from datetime import datetime, timedelta
import logging
from typing import Dict, List, Optional, Any
import re
import time
import random
from urllib.parse import urlparse

# Configure logging
logger = logging.getLogger(__name__)

class NewsScraperBase:
    """Base class for news scrapers"""
    
    def __init__(self, source_name: str, base_url: str):
        """Initialize the scraper with source name and base URL"""
        self.source_name = source_name
        self.base_url = base_url
        self.headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
        }
    
    def _get_page_content(self, url: str) -> Optional[BeautifulSoup]:
        """Get page content as BeautifulSoup object"""
        try:
            response = requests.get(url, headers=self.headers, timeout=10)
            response.raise_for_status()
            return BeautifulSoup(response.content, 'html.parser')
        except Exception as e:
            logger.error(f"Error fetching {url}: {str(e)}")
            return None
    
    def _extract_date(self, date_str: str) -> datetime:
        """Extract date from string in various formats"""
        # This method should be implemented by subclasses
        # based on the specific date format used by each news source
        raise NotImplementedError
    
    def search_by_ticker(self, ticker: str, max_pages: int = 3) -> List[Dict]:
        """Search news for a specific stock ticker"""
        # This method should be implemented by subclasses
        raise NotImplementedError
    
    def get_latest_news(self, max_pages: int = 2) -> List[Dict]:
        """Get the latest market news from the source"""
        # This method should be implemented by subclasses
        raise NotImplementedError
    
    def extract_article_content(self, url: str) -> Dict:
        """Extract content from a specific article URL"""
        # This method should be implemented by subclasses
        raise NotImplementedError


class BisnisComScraper(NewsScraperBase):
    """Scraper for market.bisnis.com"""
    
    def __init__(self):
        super().__init__("Bisnis.com", "https://market.bisnis.com")
    
    def _extract_date(self, date_str: str) -> datetime:
        """Extract date from Bisnis.com format"""
        # Example: "Kamis, 3 Juni 2021 | 14:30 WIB"
        try:
            # Remove day of week and WIB
            date_str = re.sub(r'^\w+,\s+', '', date_str)
            date_str = date_str.split('|')[0].strip()
            
            # Convert Indonesian month names to numbers
            id_months = {
                'januari': '01', 'februari': '02', 'maret': '03', 'april': '04',
                'mei': '05', 'juni': '06', 'juli': '07', 'agustus': '08',
                'september': '09', 'oktober': '10', 'november': '11', 'desember': '12'
            }
            
            for id_month, num_month in id_months.items():
                date_str = re.sub(id_month, num_month, date_str.lower())
            
            # Parse with day, month, year format
            day, month, year = date_str.split()
            time_part = date_str.split('|')[1].strip() if '|' in date_str else "00:00"
            
            return datetime.strptime(f"{day} {month} {year} {time_part}", "%d %m %Y %H:%M")
        except Exception as e:
            logger.warning(f"Failed to parse date: {date_str}. Error: {str(e)}")
            return datetime.now()
    
    def search_by_ticker(self, ticker: str, max_pages: int = 3) -> List[Dict]:
        """Search news for a specific stock ticker on Bisnis.com"""
        all_articles = []
        # Remove .JK suffix if present
        search_term = ticker.split('.')[0]
        
        for page in range(1, max_pages + 1):
            try:
                search_url = f"{self.base_url}/search/index_saham/{search_term}?page={page}"
                soup = self._get_page_content(search_url)
                
                if not soup:
                    continue
                
                articles = soup.select('div.col-sm-8.col-md-9 div.list-news')
                
                if not articles:
                    break
                
                for article in articles:
                    try:
                        title_tag = article.select_one('h2 a')
                        date_tag = article.select_one('div.date')
                        
                        if title_tag and date_tag:
                            title = title_tag.text.strip()
                            url = title_tag['href']
                            date_str = date_tag.text.strip()
                            
                            article_data = {
                                'title': title,
                                'url': url,
                                'source': self.source_name,
                                'date': self._extract_date(date_str),
                                'ticker': ticker
                            }
                            
                            all_articles.append(article_data)
                    except Exception as e:
                        logger.error(f"Error parsing article: {str(e)}")
                
                # Add delay to avoid being blocked
                time.sleep(random.uniform(1, 3))
                
            except Exception as e:
                logger.error(f"Error searching page {page} for {ticker}: {str(e)}")
        
        return all_articles
    
    def get_latest_news(self, max_pages: int = 2) -> List[Dict]:
        """Get latest market news from Bisnis.com"""
        all_articles = []
        
        for page in range(1, max_pages + 1):
            try:
                page_url = f"{self.base_url}/index/index_saham?page={page}"
                soup = self._get_page_content(page_url)
                
                if not soup:
                    continue
                
                articles = soup.select('div.col-sm-8.col-md-9 div.list-news')
                
                if not articles:
                    break
                
                for article in articles:
                    try:
                        title_tag = article.select_one('h2 a')
                        date_tag = article.select_one('div.date')
                        
                        if title_tag and date_tag:
                            title = title_tag.text.strip()
                            url = title_tag['href']
                            date_str = date_tag.text.strip()
                            
                            article_data = {
                                'title': title,
                                'url': url,
                                'source': self.source_name,
                                'date': self._extract_date(date_str),
                                'ticker': None
                            }
                            
                            all_articles.append(article_data)
                    except Exception as e:
                        logger.error(f"Error parsing article: {str(e)}")
                
                # Add delay to avoid being blocked
                time.sleep(random.uniform(1, 3))
                
            except Exception as e:
                logger.error(f"Error fetching page {page}: {str(e)}")
        
        return all_articles
    
    def extract_article_content(self, url: str) -> Dict:
        """Extract content from a Bisnis.com article URL"""
        try:
            soup = self._get_page_content(url)
            
            if not soup:
                return {'url': url, 'content': '', 'error': 'Failed to fetch page'}
            
            # Extract article text
            article_body = soup.select_one('div.col-sm-10.col-sm-offset-1.col-md-8.col-md-offset-2')
            
            if article_body:
                # Get all paragraphs
                paragraphs = article_body.select('p')
                content = ' '.join([p.text.strip() for p in paragraphs])
                
                # Extract mentioned tickers
                ticker_mentions = self._extract_ticker_mentions(content)
                
                return {
                    'url': url,
                    'content': content,
                    'mentioned_tickers': ticker_mentions
                }
            
            return {'url': url, 'content': '', 'error': 'Article body not found'}
            
        except Exception as e:
            logger.error(f"Error extracting content from {url}: {str(e)}")
            return {'url': url, 'content': '', 'error': str(e)}
    
    def _extract_ticker_mentions(self, content: str) -> List[str]:
        """Extract stock ticker mentions from article content"""
        # Pattern for Indonesian stock tickers (e.g., "BBCA", "TLKM", etc.)
        # This will need refinement based on how tickers are mentioned
        ticker_pattern = r'\b[A-Z]{4}\b'
        found_tickers = re.findall(ticker_pattern, content)
        
        # De-duplicate
        return list(set(found_tickers))


# Factory to get appropriate scraper based on URL
def get_scraper_for_source(source_url: str) -> Optional[NewsScraperBase]:
    """Get the appropriate scraper for a news source based on URL"""
    domain = urlparse(source_url).netloc
    
    scrapers = {
        'market.bisnis.com': BisnisComScraper(),
        # Add other scrapers here as they are implemented
    }
    
    return scrapers.get(domain)


# Aggregate news from all sources
def aggregate_news(ticker: Optional[str] = None, days: int = 7) -> List[Dict]:
    """
    Aggregate news from all supported sources
    
    Args:
        ticker: Optional stock ticker to filter by
        days: Number of days to look back
        
    Returns:
        List of articles from all sources
    """
    all_articles = []
    
    # All supported news sources
    sources = [
        "https://market.bisnis.com/",
        # Add others as they are implemented
    ]
    
    for source_url in sources:
        try:
            scraper = get_scraper_for_source(source_url)
            
            if not scraper:
                logger.warning(f"No scraper available for {source_url}")
                continue
            
            if ticker:
                articles = scraper.search_by_ticker(ticker)
            else:
                articles = scraper.get_latest_news()
            
            all_articles.extend(articles)
            
        except Exception as e:
            logger.error(f"Error aggregating from {source_url}: {str(e)}")
    
    # Filter by date if specified
    if days > 0:
        cutoff_date = datetime.now() - timedelta(days=days)
        all_articles = [a for a in all_articles if a['date'] >= cutoff_date]
    
    # Sort by date (newest first)
    all_articles.sort(key=lambda x: x['date'], reverse=True)
    
    return all_articles
