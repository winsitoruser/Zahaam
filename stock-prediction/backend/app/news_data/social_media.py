"""
Social media data collector for Zahaam stock prediction system.

This module collects social media data related to stocks and financial markets
for sentiment analysis and market prediction.
"""

import os
import logging
import time
from datetime import datetime, timedelta
import json
import tweepy
import requests
from typing import Dict, List, Optional, Any
import pandas as pd
from influxdb_client import InfluxDBClient, Point
from influxdb_client.client.write_api import SYNCHRONOUS

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class TwitterCollector:
    """Collector for financial data and sentiment from Twitter/X"""
    
    def __init__(self):
        """Initialize the Twitter collector with API keys and configuration"""
        self.api_key = os.environ.get("TWITTER_API_KEY")
        self.api_secret = os.environ.get("TWITTER_API_SECRET")
        self.access_token = os.environ.get("TWITTER_ACCESS_TOKEN")
        self.access_token_secret = os.environ.get("TWITTER_ACCESS_SECRET")
        
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
        
        # Initialize Twitter API client
        self.api = None
        self.client = None
        if all([self.api_key, self.api_secret, self.access_token, self.access_token_secret]):
            try:
                # Initialize v1.1 API (for some functionalities)
                auth = tweepy.OAuth1UserHandler(
                    self.api_key, self.api_secret,
                    self.access_token, self.access_token_secret
                )
                self.api = tweepy.API(auth)
                
                # Initialize v2 API client (for newer endpoints)
                self.client = tweepy.Client(
                    consumer_key=self.api_key,
                    consumer_secret=self.api_secret,
                    access_token=self.access_token,
                    access_token_secret=self.access_token_secret
                )
                logger.info("Twitter API client initialized successfully")
            except Exception as e:
                logger.error(f"Failed to initialize Twitter API: {str(e)}")
        else:
            logger.warning("Twitter API credentials not fully configured. Set environment variables for production.")
            
        # List of financial influencers and accounts to track
        self.financial_accounts = [
            "jimcramer",
            "elerianm",
            "carlquintanilla",
            "TheStalwart",
            "SarahEisen",
            "ScottWapnerCNBC",
            "lizannlaptop",
            "BeckyQuick",
            "andrewrsorkin",
            "MorganStanley",
            "GoldmanSachs",
            "jpmorgan",
            "blackrock",
            "federalreserve",
            "CNBC",
            "WSJ",
            "Bloomberg",
            "FT",
            "IBDinvestors"
        ]
        
        # List of important cashtags to track
        self.important_tickers = [
            "SPY",    # S&P 500
            "QQQ",    # Nasdaq 100
            "DIA",    # Dow Jones
            "IWM",    # Russell 2000
            "AAPL",   # Apple
            "MSFT",   # Microsoft
            "GOOGL",  # Google
            "AMZN",   # Amazon
            "META",   # Meta
            "TSLA",   # Tesla
            "NVDA",   # Nvidia
            "BRK.B",  # Berkshire Hathaway
            "V",      # Visa
            "JPM",    # JPMorgan
            "WMT",    # Walmart
            "XLE",    # Energy Sector ETF
            "XLF",    # Financial Sector ETF
            "XLK",    # Technology Sector ETF
            "XLV",    # Healthcare Sector ETF
            "XLC"     # Communication Sector ETF
        ]
    
    def collect_cashtag_tweets(self, ticker: str, count: int = 100) -> List[Dict]:
        """Collect tweets with cashtag for a specific ticker"""
        if not self.client:
            logger.error("Twitter client not initialized")
            return []
            
        try:
            # Search for tweets containing the cashtag
            query = f"${ticker} -is:retweet lang:en"
            
            tweets = []
            response = self.client.search_recent_tweets(
                query=query,
                max_results=count,
                tweet_fields=["created_at", "public_metrics", "author_id", "context_annotations"]
            )
            
            if response.data:
                for tweet in response.data:
                    tweets.append({
                        "id": tweet.id,
                        "text": tweet.text,
                        "created_at": str(tweet.created_at),
                        "author_id": tweet.author_id,
                        "retweets": tweet.public_metrics["retweet_count"],
                        "replies": tweet.public_metrics["reply_count"],
                        "likes": tweet.public_metrics["like_count"]
                    })
            
            logger.info(f"Collected {len(tweets)} tweets for ${ticker}")
            
            # Record metrics for tweet collection
            point = Point("twitter_collection") \
                .tag("ticker", ticker) \
                .tag("query_type", "cashtag") \
                .field("tweet_count", len(tweets)) \
                .time(datetime.utcnow())
            self.write_api.write(bucket=self.influxdb_bucket, record=point)
            
            return tweets
            
        except Exception as e:
            logger.error(f"Error collecting tweets for ${ticker}: {str(e)}")
            return []
    
    def collect_financial_account_tweets(self, username: str, count: int = 100) -> List[Dict]:
        """Collect tweets from a specific financial account"""
        if not self.client:
            logger.error("Twitter client not initialized")
            return []
            
        try:
            # Get user ID from username
            user = self.client.get_user(username=username)
            if not user.data:
                logger.error(f"User {username} not found")
                return []
                
            user_id = user.data.id
            
            # Get recent tweets from the user
            tweets = []
            response = self.client.get_users_tweets(
                id=user_id,
                max_results=count,
                tweet_fields=["created_at", "public_metrics"]
            )
            
            if response.data:
                for tweet in response.data:
                    tweets.append({
                        "id": tweet.id,
                        "text": tweet.text,
                        "created_at": str(tweet.created_at),
                        "retweets": tweet.public_metrics["retweet_count"],
                        "replies": tweet.public_metrics["reply_count"],
                        "likes": tweet.public_metrics["like_count"]
                    })
            
            logger.info(f"Collected {len(tweets)} tweets from @{username}")
            
            # Record metrics for account tweet collection
            point = Point("twitter_collection") \
                .tag("account", username) \
                .tag("query_type", "account") \
                .field("tweet_count", len(tweets)) \
                .time(datetime.utcnow())
            self.write_api.write(bucket=self.influxdb_bucket, record=point)
            
            return tweets
            
        except Exception as e:
            logger.error(f"Error collecting tweets from @{username}: {str(e)}")
            return []
    
    def collect_market_sentiment(self) -> Dict[str, Any]:
        """
        Collect overall market sentiment based on various ticker cashtags
        """
        results = {}
        
        # Collect tweets for major market indices
        for index in ["SPY", "QQQ", "DIA", "IWM"]:
            results[index] = self.collect_cashtag_tweets(index, count=50)
            
            # Respect rate limits
            time.sleep(2)
        
        return results
    
    def collect_sector_sentiment(self) -> Dict[str, Any]:
        """
        Collect sentiment for different market sectors based on sector ETFs
        """
        sectors = {
            "technology": "XLK",
            "finance": "XLF",
            "healthcare": "XLV",
            "energy": "XLE",
            "communications": "XLC"
        }
        
        results = {}
        for sector_name, ticker in sectors.items():
            results[sector_name] = self.collect_cashtag_tweets(ticker, count=30)
            
            # Respect rate limits
            time.sleep(2)
            
        return results
    
    def collect_influencer_opinions(self) -> Dict[str, Any]:
        """
        Collect tweets from financial influencers and experts
        """
        results = {}
        
        # Only collect from a subset of influencers each time to respect rate limits
        sample_accounts = self.financial_accounts[:5]  # Adjust based on rate limits
        
        for account in sample_accounts:
            results[account] = self.collect_financial_account_tweets(account, count=20)
            
            # Respect rate limits
            time.sleep(2)
            
        return results
    
    def store_twitter_data(self, data: Dict[str, Any], category: str) -> bool:
        """Store collected Twitter data for later analysis"""
        # This would connect to the PostgreSQL AI database
        # For now, just log that we would store the data
        total_tweets = sum(len(tweets) for tweets in data.values())
        logger.info(f"Would store {total_tweets} tweets for category '{category}' to database")
        
        # Record metrics
        point = Point("twitter_storage") \
            .tag("category", category) \
            .field("tweet_count", total_tweets) \
            .time(datetime.utcnow())
        self.write_api.write(bucket=self.influxdb_bucket, record=point)
        
        return True
        
    def run_collection_cycle(self):
        """Run a full collection cycle for all Twitter data"""
        logger.info("Starting Twitter data collection cycle")
        
        # 1. Collect market sentiment
        market_data = self.collect_market_sentiment()
        self.store_twitter_data(market_data, "market_indices")
        
        # 2. Collect sector sentiment
        sector_data = self.collect_sector_sentiment()
        self.store_twitter_data(sector_data, "market_sectors")
        
        # 3. Collect influencer opinions
        influencer_data = self.collect_influencer_opinions()
        self.store_twitter_data(influencer_data, "financial_influencers")
        
        # 4. Collect specific ticker data (rotated subset each time)
        ticker_subset = self.important_tickers[5:10]  # Rotate through different tickers each cycle
        ticker_data = {ticker: self.collect_cashtag_tweets(ticker, count=30) for ticker in ticker_subset}
        self.store_twitter_data(ticker_data, "specific_tickers")
        
        logger.info("Completed Twitter data collection cycle")


# Main execution point when the module is run directly
if __name__ == "__main__":
    logger.info("Starting Zahaam Twitter Collector")
    collector = TwitterCollector()
    
    # Main collection loop
    while True:
        try:
            collector.run_collection_cycle()
            # Run collection every 2 hours (Twitter has stricter rate limits)
            logger.info("Sleeping for 2 hours before next collection cycle")
            time.sleep(7200)
        except KeyboardInterrupt:
            logger.info("Twitter collector stopped by user")
            break
        except Exception as e:
            logger.error(f"Error in collection cycle: {str(e)}")
            # Sleep for 10 minutes and try again
            time.sleep(600)
