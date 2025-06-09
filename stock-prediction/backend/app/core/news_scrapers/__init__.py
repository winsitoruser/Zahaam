"""
News scrapers package initialization
"""
from app.core.news_scrapers.bisnis_com import BisnisComScraper
from app.core.news_scrapers.kontan import KontanScraper  
from app.core.news_scrapers.cnbc_indonesia import CNBCIndonesiaScraper
from app.core.news_scrapers.investing_com import InvestingComScraper
from app.core.news_scrapers.most_co_id import MostCoIdScraper
from app.core.news_scrapers.investor_id import InvestorIdScraper
from app.core.news_scrapers.metrotv import MetroTVScraper
from app.core.news_scrapers.tradingview import TradingViewScraper
from app.core.news_scrapers.sindonews import SindoNewsScraper
from app.core.news_scrapers.liputan6 import Liputan6Scraper

# Import scraper factory
from app.core.news_scrapers.factory import get_scraper_for_source, aggregate_news

__all__ = [
    'BisnisComScraper', 
    'KontanScraper',
    'CNBCIndonesiaScraper',
    'InvestingComScraper',
    'MostCoIdScraper',
    'InvestorIdScraper',
    'MetroTVScraper',
    'TradingViewScraper',
    'SindoNewsScraper',
    'Liputan6Scraper',
    'get_scraper_for_source',
    'aggregate_news'
]
