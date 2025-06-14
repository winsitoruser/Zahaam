"""
Scraper for market.bisnis.com
"""

import re
from bs4 import BeautifulSoup
from datetime import datetime
import time
from typing import Dict, List, Optional

from app.core.news_scrapers.base import NewsScraperBase
import logging

# Configure logging
logger = logging.getLogger(__name__)

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
            date_parts = date_str.split('|')
            date_part = date_parts[0].strip()
            
            # Convert Indonesian month names to numbers
            id_months = {
                'januari': '01', 'februari': '02', 'maret': '03', 'april': '04',
                'mei': '05', 'juni': '06', 'juli': '07', 'agustus': '08',
                'september': '09', 'oktober': '10', 'november': '11', 'desember': '12'
            }
            
            for id_month, num_month in id_months.items():
                date_part = re.sub(id_month, num_month, date_part.lower())
            
            # Parse with day, month, year format
            parts = date_part.split()
            if len(parts) != 3:
                logger.warning(f"Unexpected date format: {date_str}")
                return datetime.now()
                
            day, month, year = parts
            
            time_part = "00:00"
            if len(date_parts) > 1 and ":" in date_parts[1]:
                time_part = date_parts[1].strip().split()[0]
            
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
                self._add_delay()
                
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
                self._add_delay()
                
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
        ticker_pattern = r'\b[A-Z]{4}\b'
        found_tickers = re.findall(ticker_pattern, content)
        
        # De-duplicate
        return list(set(found_tickers))
