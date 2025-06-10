"""
Configuration module for news and social media data collection services.

This module provides centralized configuration management for API keys,
endpoints, collection schedules, and other settings related to news and
social media data collection.
"""

import os
import json
import logging
from typing import Dict, List, Any, Optional, Union
from datetime import datetime, timedelta
import yaml

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class NewsApiConfig:
    """Configuration for NewsAPI integration"""
    
    def __init__(self):
        """Initialize NewsAPI configuration"""
        self.api_key = os.environ.get("NEWSAPI_KEY", "")
        self.base_url = "https://newsapi.org/v2"
        self.endpoints = {
            "everything": f"{self.base_url}/everything",
            "top_headlines": f"{self.base_url}/top-headlines"
        }
        self.max_results_per_page = 100
        self.default_language = "en"
        
        # News sources to prioritize for financial news
        self.financial_sources = [
            "bloomberg", "financial-times", "business-insider", "the-wall-street-journal", 
            "fortune", "cnbc", "reuters", "the-economist"
        ]
        
        # Default query parameters
        self.default_params = {
            "language": self.default_language,
            "sortBy": "publishedAt",
            "pageSize": 50
        }
        
        # Collection interval in minutes
        self.collection_interval = int(os.environ.get("NEWSAPI_COLLECTION_INTERVAL", "60"))
        
        # Rate limit settings
        self.max_daily_requests = 500  # Free tier limit
        self.current_daily_requests = 0
        self.rate_limit_reset_time = datetime.now() + timedelta(days=1)
        
        # Log config initialization
        if not self.api_key:
            logger.warning("NewsAPI key not set in environment variables")
        else:
            logger.info("NewsAPI configuration initialized")


class TwitterApiConfig:
    """Configuration for Twitter API integration"""
    
    def __init__(self):
        """Initialize Twitter API configuration"""
        # Twitter API v2 credentials
        self.bearer_token = os.environ.get("TWITTER_BEARER_TOKEN", "")
        self.api_key = os.environ.get("TWITTER_API_KEY", "")
        self.api_secret = os.environ.get("TWITTER_API_SECRET", "")
        self.access_token = os.environ.get("TWITTER_ACCESS_TOKEN", "")
        self.access_token_secret = os.environ.get("TWITTER_ACCESS_TOKEN_SECRET", "")
        self.client_id = os.environ.get("TWITTER_CLIENT_ID", "")
        self.client_secret = os.environ.get("TWITTER_CLIENT_SECRET", "")
        
        # API endpoints
        self.base_url_v2 = "https://api.twitter.com/2"
        
        # Default query parameters
        self.default_params = {
            "tweet.fields": "created_at,public_metrics,entities,author_id,referenced_tweets",
            "expansions": "author_id,referenced_tweets.id,entities.mentions.username",
            "user.fields": "name,username,verified,public_metrics,description",
            "max_results": 100
        }
        
        # Collection interval in minutes
        self.collection_interval = int(os.environ.get("TWITTER_COLLECTION_INTERVAL", "15"))
        
        # Rate limit settings (standard v2 API limits)
        self.rate_limits = {
            "app_rate_limit": {
                "tweets_search_recent": 450,  # requests per 15 minutes
                "users_lookup": 300,          # requests per 15 minutes
                "tweet_lookup": 300,          # requests per 15 minutes
            }
        }
        
        # Top financial influencers to track
        self.financial_influencers = self._load_financial_influencers()
        
        # Log config initialization
        if not self.bearer_token or not self.api_key:
            logger.warning("Twitter API credentials not fully set in environment variables")
        else:
            logger.info("Twitter API configuration initialized")
    
    def _load_financial_influencers(self) -> List[Dict[str, str]]:
        """Load list of financial influencers to track"""
        # Default influencers if file not available
        default_influencers = [
            {"username": "jimcramer", "name": "Jim Cramer", "category": "analyst"},
            {"username": "GoldmanSachs", "name": "Goldman Sachs", "category": "institution"},
            {"username": "elonmusk", "name": "Elon Musk", "category": "ceo"},
            {"username": "jpmorgan", "name": "JPMorgan", "category": "institution"},
            {"username": "federalreserve", "name": "Federal Reserve", "category": "institution"},
            {"username": "WarrenBuffett", "name": "Warren Buffett", "category": "investor"},
            {"username": "BillAckman", "name": "Bill Ackman", "category": "investor"},
            {"username": "Carl_C_Icahn", "name": "Carl Icahn", "category": "investor"},
            {"username": "TheStreet", "name": "TheStreet", "category": "news"},
            {"username": "MorganStanley", "name": "Morgan Stanley", "category": "institution"}
        ]
        
        # Path to custom influencers file
        influencers_file = os.environ.get("FINANCIAL_INFLUENCERS_FILE", "")
        
        if influencers_file and os.path.exists(influencers_file):
            try:
                with open(influencers_file, 'r') as file:
                    influencers = json.load(file)
                logger.info(f"Loaded {len(influencers)} financial influencers from file")
                return influencers
            except Exception as e:
                logger.error(f"Error loading financial influencers: {str(e)}")
        
        return default_influencers


class RedditApiConfig:
    """Configuration for Reddit API integration"""
    
    def __init__(self):
        """Initialize Reddit API configuration"""
        self.client_id = os.environ.get("REDDIT_CLIENT_ID", "")
        self.client_secret = os.environ.get("REDDIT_CLIENT_SECRET", "")
        self.user_agent = os.environ.get("REDDIT_USER_AGENT", "Zahaam/1.0")
        
        # Financial subreddits to monitor
        self.financial_subreddits = [
            "wallstreetbets", "investing", "stocks", "stockmarket", 
            "options", "finance", "personalfinance", "cryptocurrency"
        ]
        
        # Collection interval in minutes
        self.collection_interval = int(os.environ.get("REDDIT_COLLECTION_INTERVAL", "30"))
        
        # Log config initialization
        if not self.client_id or not self.client_secret:
            logger.warning("Reddit API credentials not fully set in environment variables")
        else:
            logger.info("Reddit API configuration initialized")


class StockConfig:
    """Configuration for stock and market data"""
    
    def __init__(self):
        """Initialize stock and market configuration"""
        # Top tickers to track (default to major US stocks)
        self.default_tickers = [
            # Major indices
            "^GSPC",    # S&P 500
            "^DJI",     # Dow Jones
            "^IXIC",    # NASDAQ Composite
            "^RUT",     # Russell 2000
            
            # Sector ETFs
            "XLK",      # Technology
            "XLF",      # Financial
            "XLE",      # Energy
            "XLV",      # Healthcare
            "XLY",      # Consumer Discretionary
            "XLP",      # Consumer Staples
            "XLI",      # Industrial
            "XLB",      # Materials
            "XLU",      # Utilities
            "XLRE",     # Real Estate
            
            # Major tech stocks
            "AAPL",     # Apple
            "MSFT",     # Microsoft
            "GOOGL",    # Alphabet
            "AMZN",     # Amazon
            "META",     # Meta Platforms
            "TSLA",     # Tesla
            "NVDA",     # NVIDIA
            
            # Financial institutions
            "JPM",      # JPMorgan Chase
            "BAC",      # Bank of America
            "GS",       # Goldman Sachs
            "MS",       # Morgan Stanley
            
            # Other major stocks
            "JNJ",      # Johnson & Johnson
            "PG",       # Procter & Gamble
            "KO",       # Coca-Cola
            "DIS",      # Disney
            "NFLX"      # Netflix
        ]
        
        # Classification of stocks by sector
        self.sector_classification = {
            "technology": ["AAPL", "MSFT", "GOOGL", "AMZN", "META", "TSLA", "NVDA"],
            "financial": ["JPM", "BAC", "GS", "MS"],
            "healthcare": ["JNJ"],
            "consumer": ["PG", "KO", "DIS", "NFLX"]
        }
        
        # Load additional tickers from file if available
        self.custom_tickers = self._load_custom_tickers()
        
        # Combine default and custom tickers
        self.tickers = list(set(self.default_tickers + self.custom_tickers))
    
    def _load_custom_tickers(self) -> List[str]:
        """Load custom tickers from file"""
        tickers_file = os.environ.get("CUSTOM_TICKERS_FILE", "")
        
        if tickers_file and os.path.exists(tickers_file):
            try:
                with open(tickers_file, 'r') as file:
                    tickers = [line.strip() for line in file if line.strip()]
                logger.info(f"Loaded {len(tickers)} custom tickers from file")
                return tickers
            except Exception as e:
                logger.error(f"Error loading custom tickers: {str(e)}")
        
        return []


class SentimentConfig:
    """Configuration for sentiment analysis"""
    
    def __init__(self):
        """Initialize sentiment analysis configuration"""
        # Model selection
        self.model_type = os.environ.get("SENTIMENT_MODEL_TYPE", "vader")
        self.use_finbert = os.environ.get("USE_FINBERT", "false").lower() == "true"
        
        # Custom sentiment lexicon file path
        self.custom_lexicon_file = os.environ.get("CUSTOM_SENTIMENT_LEXICON", "")
        
        # Sentiment score thresholds
        self.thresholds = {
            "very_positive": 0.5,
            "positive": 0.05,
            "neutral": -0.05,
            "negative": -0.5,
            "very_negative": -1.0
        }
        
        # Sentiment aggregation weights
        self.source_weights = {
            "news": 0.4,
            "twitter": 0.3,
            "reddit": 0.2,
            "stocktwits": 0.1
        }


class DatabaseConfig:
    """Configuration for database connections"""
    
    def __init__(self):
        """Initialize database configuration"""
        # PostgreSQL AI database
        self.ai_db_url = os.environ.get("POSTGRES_AI_URL", "postgresql://postgres:postgres@postgres_ai:5432/postgres_ai")
        self.ai_db_pool_size = int(os.environ.get("POSTGRES_AI_POOL_SIZE", "5"))
        
        # InfluxDB configuration
        self.influxdb_url = os.environ.get("INFLUXDB_URL", "http://influxdb:8086")
        self.influxdb_token = os.environ.get("INFLUXDB_TOKEN", "")
        self.influxdb_org = os.environ.get("INFLUXDB_ORG", "zahaam")
        self.influxdb_bucket = os.environ.get("INFLUXDB_BUCKET", "market_data")


class CollectorConfig:
    """Main configuration for all collectors"""
    
    def __init__(self):
        """Initialize collector configuration"""
        self.news_api = NewsApiConfig()
        self.twitter_api = TwitterApiConfig()
        self.reddit_api = RedditApiConfig()
        self.stock_config = StockConfig()
        self.sentiment_config = SentimentConfig()
        self.database_config = DatabaseConfig()
        
        # General collector settings
        self.logging_level = os.environ.get("COLLECTOR_LOGGING_LEVEL", "INFO")
        self.enable_metrics = os.environ.get("ENABLE_METRICS", "true").lower() == "true"
        
        # Enable/disable specific collectors
        self.enable_news_collector = os.environ.get("ENABLE_NEWS_COLLECTOR", "true").lower() == "true"
        self.enable_twitter_collector = os.environ.get("ENABLE_TWITTER_COLLECTOR", "true").lower() == "true"
        self.enable_reddit_collector = os.environ.get("ENABLE_REDDIT_COLLECTOR", "false").lower() == "true"
        self.enable_market_collector = os.environ.get("ENABLE_MARKET_COLLECTOR", "true").lower() == "true"
        
        # Retry settings
        self.max_retries = int(os.environ.get("COLLECTOR_MAX_RETRIES", "3"))
        self.retry_delay = int(os.environ.get("COLLECTOR_RETRY_DELAY", "5"))
        
        # Save configuration to file if requested
        if os.environ.get("SAVE_CONFIG", "false").lower() == "true":
            self.save_config()
    
    def save_config(self, file_path: str = "collector_config.yaml"):
        """Save current configuration to a YAML file (excluding sensitive data)"""
        # Create a dictionary of config values (excluding sensitive data)
        config_dict = {
            "logging_level": self.logging_level,
            "enable_metrics": self.enable_metrics,
            "news_collector": {
                "enabled": self.enable_news_collector,
                "collection_interval": self.news_api.collection_interval,
                "financial_sources": self.news_api.financial_sources
            },
            "twitter_collector": {
                "enabled": self.enable_twitter_collector,
                "collection_interval": self.twitter_api.collection_interval,
                "financial_influencers": [i["username"] for i in self.twitter_api.financial_influencers]
            },
            "reddit_collector": {
                "enabled": self.enable_reddit_collector,
                "collection_interval": self.reddit_api.collection_interval,
                "financial_subreddits": self.reddit_api.financial_subreddits
            },
            "market_collector": {
                "enabled": self.enable_market_collector
            },
            "stock_config": {
                "tickers_count": len(self.stock_config.tickers),
                "sectors": list(self.stock_config.sector_classification.keys())
            },
            "sentiment_config": {
                "model_type": self.sentiment_config.model_type,
                "use_finbert": self.sentiment_config.use_finbert,
                "thresholds": self.sentiment_config.thresholds
            }
        }
        
        try:
            with open(file_path, 'w') as file:
                yaml.dump(config_dict, file, default_flow_style=False)
            logger.info(f"Configuration saved to {file_path}")
        except Exception as e:
            logger.error(f"Error saving configuration: {str(e)}")


# Create a singleton instance
config = None

def get_config() -> CollectorConfig:
    """Get or create collector configuration singleton"""
    global config
    if config is None:
        config = CollectorConfig()
    return config


if __name__ == "__main__":
    # Test configuration loading
    collector_config = get_config()
    print("Configuration loaded:")
    print(f"News API key set: {'Yes' if collector_config.news_api.api_key else 'No'}")
    print(f"Twitter API credentials set: {'Yes' if collector_config.twitter_api.bearer_token else 'No'}")
    print(f"Tickers to track: {len(collector_config.stock_config.tickers)}")
    print(f"Sentiment model: {collector_config.sentiment_config.model_type}")
