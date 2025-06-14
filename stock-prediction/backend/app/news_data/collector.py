"""
News data collector for Zahaam stock prediction system.

This module collects news articles and financial data from various sources
including NewsAPI and specialized financial news sites.
"""

import os
import logging
import time
from datetime import datetime, timedelta
import json
import requests
from typing import Dict, List, Optional, Any
import pandas as pd
from influxdb_client import InfluxDBClient, Point
from influxdb_client.client.write_api import SYNCHRONOUS

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class NewsCollector:
    """Collector for financial news from NewsAPI and other sources"""
    
    def __init__(self):
        """Initialize the news collector with API keys and configuration"""
        self.newsapi_key = os.environ.get("NEWSAPI_KEY", "demo")
        self.influxdb_url = os.environ.get("INFLUXDB_URL", "http://localhost:8086")
        self.influxdb_token = os.environ.get("INFLUXDB_TOKEN", "zahaam-token")
        self.influxdb_org = os.environ.get("INFLUXDB_ORG", "zahaam")
        self.influxdb_bucket = os.environ.get("INFLUXDB_BUCKET", "stock_metrics")
        
        # Initialize InfluxDB client for metrics
        self.influxdb_client = InfluxDBClient(
            url=self.influxdb_url,
            token=self.influxdb_token,
            org=self.influxdb_org
        )
        self.write_api = self.influxdb_client.write_api(write_options=SYNCHRONOUS)
        
        # List of financial news sources to track
        self.news_sources = [
            "bloomberg.com",
            "cnbc.com",
            "reuters.com",
            "wsj.com",
            "ft.com",
            "investors.com",
            "marketwatch.com",
            "barrons.com",
            "fool.com",
            "finance.yahoo.com"
        ]
        
        # List of financial topics/keywords to track
        self.financial_keywords = [
            "stock market",
            "inflation",
            "interest rates",
            "federal reserve",
            "market crash",
            "bull market",
            "bear market",
            "economic growth",
            "recession",
            "GDP",
            "unemployment",
            "earnings report",
            "quarterly results",
            "IPO",
            "merger",
            "acquisition"
        ]
        
        logger.info(f"NewsCollector initialized with {len(self.news_sources)} sources")
    
    def collect_general_market_news(self) -> List[Dict]:
        """Collect general market news articles from NewsAPI"""
        url = "https://newsapi.org/v2/everything"
        
        articles = []
        for source in self.news_sources:
            try:
                params = {
                    "apiKey": self.newsapi_key,
                    "domains": source,
                    "language": "en",
                    "sortBy": "publishedAt",
                    "pageSize": 100,  # Maximum allowed by NewsAPI
                    "from": (datetime.now() - timedelta(days=7)).isoformat()
                }
                
                response = requests.get(url, params=params)
                data = response.json()
                
                if response.status_code == 200 and data.get("status") == "ok":
                    source_articles = data.get("articles", [])
                    logger.info(f"Collected {len(source_articles)} articles from {source}")
                    
                    # Record metric for news collection
                    point = Point("news_data_collection") \
                        .tag("source", source) \
                        .tag("collector", "newsapi") \
                        .field("article_count", len(source_articles)) \
                        .time(datetime.utcnow())
                    self.write_api.write(bucket=self.influxdb_bucket, record=point)
                    
                    articles.extend(source_articles)
                else:
                    logger.error(f"Failed to collect from {source}: {data.get('message', 'Unknown error')}")
                    
                # Sleep to respect rate limits
                time.sleep(1)
                    
            except Exception as e:
                logger.error(f"Error collecting from {source}: {str(e)}")
        
        return articles
    
    def collect_stock_specific_news(self, ticker: str) -> List[Dict]:
        """Collect news specific to a stock ticker"""
        url = "https://newsapi.org/v2/everything"
        
        try:
            params = {
                "apiKey": self.newsapi_key,
                "q": ticker,
                "language": "en",
                "sortBy": "publishedAt",
                "pageSize": 100,
                "from": (datetime.now() - timedelta(days=30)).isoformat()  # Look back further for stock-specific news
            }
            
            response = requests.get(url, params=params)
            data = response.json()
            
            if response.status_code == 200 and data.get("status") == "ok":
                articles = data.get("articles", [])
                logger.info(f"Collected {len(articles)} articles for {ticker}")
                
                # Record metric for stock-specific news collection
                point = Point("stock_news_collection") \
                    .tag("ticker", ticker) \
                    .tag("collector", "newsapi") \
                    .field("article_count", len(articles)) \
                    .time(datetime.utcnow())
                self.write_api.write(bucket=self.influxdb_bucket, record=point)
                
                return articles
            else:
                logger.error(f"Failed to collect for {ticker}: {data.get('message', 'Unknown error')}")
                return []
                
        except Exception as e:
            logger.error(f"Error collecting for {ticker}: {str(e)}")
            return []
    
    def collect_financial_topics(self) -> Dict[str, List[Dict]]:
        """Collect news based on specific financial topics/keywords"""
        url = "https://newsapi.org/v2/everything"
        results = {}
        
        for keyword in self.financial_keywords:
            try:
                params = {
                    "apiKey": self.newsapi_key,
                    "q": keyword,
                    "language": "en",
                    "sortBy": "relevancy",
                    "pageSize": 20,  # Fewer articles per keyword
                    "from": (datetime.now() - timedelta(days=7)).isoformat()
                }
                
                response = requests.get(url, params=params)
                data = response.json()
                
                if response.status_code == 200 and data.get("status") == "ok":
                    keyword_articles = data.get("articles", [])
                    logger.info(f"Collected {len(keyword_articles)} articles for topic '{keyword}'")
                    results[keyword] = keyword_articles
                else:
                    logger.error(f"Failed to collect for topic '{keyword}': {data.get('message', 'Unknown error')}")
                    results[keyword] = []
                    
                # Sleep to respect rate limits
                time.sleep(1)
                    
            except Exception as e:
                logger.error(f"Error collecting for topic '{keyword}': {str(e)}")
                results[keyword] = []
        
        return results
    
    def store_news_data(self, articles: List[Dict], category: str = "general") -> bool:
        """Store collected news data to database for later analysis"""
        # This would connect to the PostgreSQL AI database
        # For now, just log that we would store the data
        logger.info(f"Would store {len(articles)} {category} news articles to database")
        
        # Record metrics
        point = Point("news_storage") \
            .tag("category", category) \
            .field("article_count", len(articles)) \
            .time(datetime.utcnow())
        self.write_api.write(bucket=self.influxdb_bucket, record=point)
        
        return True
    
    def run_collection_cycle(self):
        """Run a full collection cycle for all news data"""
        logger.info("Starting news collection cycle")
        
        # 1. Collect general market news
        general_news = self.collect_general_market_news()
        self.store_news_data(general_news, "general")
        
        # 2. Collect news for major US indexes
        for index in ["SPY", "QQQ", "DIA", "IWM"]:
            index_news = self.collect_stock_specific_news(index)
            self.store_news_data(index_news, f"index_{index}")
        
        # 3. Collect news for major sectors
        sectors = ["technology", "healthcare", "finance", "energy", "consumer"]
        for sector in sectors:
            sector_news = self.collect_stock_specific_news(sector + " stocks")
            self.store_news_data(sector_news, f"sector_{sector}")
        
        # 4. Collect financial topics
        topics_news = self.collect_financial_topics()
        for topic, articles in topics_news.items():
            self.store_news_data(articles, f"topic_{topic}")
            
        logger.info("Completed news collection cycle")


# Main execution point when the module is run
if __name__ == "__main__":
    logger.info("Starting Zahaam News Collector")
    collector = NewsCollector()
    
    # Main collection loop
    while True:
        try:
            collector.run_collection_cycle()
            # Run collection every hour
            logger.info("Sleeping for 1 hour before next collection cycle")
            time.sleep(3600)
        except KeyboardInterrupt:
            logger.info("News collector stopped by user")
            break
        except Exception as e:
            logger.error(f"Error in collection cycle: {str(e)}")
            # Sleep for 5 minutes and try again
            time.sleep(300)
