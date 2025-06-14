"""
Factory for news scrapers
"""

from urllib.parse import urlparse
from typing import Dict, List, Optional, Any
import logging
from datetime import datetime, timedelta

# Import base class
from app.core.news_scrapers.base import NewsScraperBase

# Import all scrapers 
from app.core.news_scrapers.bisnis_com import BisnisComScraper

# Configure logging
logger = logging.getLogger(__name__)

# Register all scrapers
def get_scraper_for_source(source_url: str) -> Optional[NewsScraperBase]:
    """
    Get the appropriate scraper for a news source based on URL
    
    Args:
        source_url: URL of the news source
        
    Returns:
        NewsScraperBase: An appropriate scraper instance or None if no scraper available
    """
    domain = urlparse(source_url).netloc
    
    scrapers = {
        'market.bisnis.com': BisnisComScraper(),
        # Additional scrapers will be added as they are implemented
        # 'investasi.kontan.co.id': KontanScraper(),
        # 'www.cnbcindonesia.com': CNBCIndonesiaScraper(),
        # 'id.investing.com': InvestingComScraper(),
        # 'www.most.co.id': MostCoIdScraper(),
        # 'investor.id': InvestorIdScraper(),
        # 'www.metrotvnews.com': MetroTVScraper(),
        # 'id.tradingview.com': TradingViewScraper(),
        # 'www.sindonews.com': SindoNewsScraper(),
        # 'www.liputan6.com': Liputan6Scraper()
    }
    
    return scrapers.get(domain)


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
        # Other sources will be added as their scrapers are implemented
        # "https://investasi.kontan.co.id/",
        # "https://www.cnbcindonesia.com/tag/saham", 
        # "https://id.investing.com/news/stock-market-news",
        # "https://www.most.co.id/riset",
        # "https://investor.id/",
        # "https://www.metrotvnews.com/tag/2235/saham",
        # "https://id.tradingview.com/markets/stocks-indonesia/",
        # "https://www.sindonews.com/topic/707/saham",
        # "https://www.liputan6.com/saham"
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
