"""
Base class for news scrapers
"""

import requests
from bs4 import BeautifulSoup
import logging
from typing import Dict, List, Optional, Any
import time
import random
from datetime import datetime

# Configure logging
logger = logging.getLogger(__name__)

class NewsScraperBase:
    """Base class for all news scrapers"""
    
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
    
    def _add_delay(self, min_seconds=1, max_seconds=3):
        """Add a random delay to avoid being blocked"""
        delay = random.uniform(min_seconds, max_seconds)
        time.sleep(delay)
    
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
    
    def _extract_ticker_mentions(self, content: str) -> List[str]:
        """Extract stock ticker mentions from article content"""
        # This should be implemented by subclasses based on specific patterns
        # for each news source
        raise NotImplementedError
