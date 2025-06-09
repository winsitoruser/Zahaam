"""
Test script for news sentiment API endpoints
"""
import requests
import json
from pprint import pprint

# Base URL for API
BASE_URL = "http://localhost:5005/api/news-sentiment"

def test_analyze_article_with_storage():
    """Test analyzing an article with database storage"""
    url = "https://investasi.kontan.co.id/news/harga-emas-antam-hari-ini-jumat-582022-naik-rp-9000-ke-rp-942000-per-gram"
    response = requests.get(f"{BASE_URL}/analyze-article", params={"url": url, "store": True})
    
    print("\n=== ANALYZE ARTICLE WITH STORAGE ===")
    if response.status_code == 200:
        data = response.json()
        print(f"Success: {data.get('success')}")
        print(f"Article ID: {data.get('article_id')}")
        print(f"Sentiment Analysis ID: {data.get('sentiment_analysis_id')}")
        print(f"Ticker Mentions: {data.get('ticker_mentions')}")
        
        # Print sentiment info
        sentiment = data.get('analysis_result', {}).get('sentiment', {})
        print(f"Sentiment Score: {sentiment.get('adjusted_compound')}")
    else:
        print(f"Error: {response.status_code}")
        print(response.text)

def test_market_sentiment_with_storage():
    """Test market sentiment analysis with database storage"""
    response = requests.get(f"{BASE_URL}/market", params={"days": 1, "store": True})
    
    print("\n=== MARKET SENTIMENT WITH STORAGE ===")
    if response.status_code == 200:
        data = response.json()
        print(f"Success: {data.get('success')}")
        print(f"Market Summary ID: {data.get('market_summary_id')}")
        
        # Print sentiment info
        result = data.get('analysis_result', {})
        print(f"Articles Analyzed: {result.get('articles_analyzed')}")
        print(f"Sentiment Score: {result.get('sentiment_score')}")
        print(f"Sentiment Label: {result.get('sentiment_label')}")
        
        # Print ticker info
        ticker_sentiments = result.get('ticker_sentiments', {})
        print(f"Tickers mentioned: {len(ticker_sentiments)}")
        
        # Print top positive tickers
        print("\nTop Positive Tickers:")
        sorted_tickers = sorted(ticker_sentiments.items(), key=lambda x: x[1]['sentiment_score'], reverse=True)[:3]
        for ticker, data in sorted_tickers:
            print(f"  {ticker}: {data['sentiment_score']:.3f} ({data['sentiment_label']})")
    else:
        print(f"Error: {response.status_code}")
        print(response.text)

def test_ticker_sentiment_with_storage():
    """Test ticker sentiment analysis with database storage"""
    ticker = "BBRI"
    response = requests.get(f"{BASE_URL}/ticker/{ticker}", params={"days": 3, "store": True})
    
    print(f"\n=== TICKER SENTIMENT WITH STORAGE ({ticker}) ===")
    if response.status_code == 200:
        data = response.json()
        print(f"Success: {data.get('success')}")
        print(f"Ticker Sentiment ID: {data.get('ticker_sentiment_id')}")
        
        # Print sentiment info
        result = data.get('analysis_result', {})
        print(f"Articles Analyzed: {result.get('articles_analyzed')}")
        print(f"Sentiment Score: {result.get('sentiment_score')}")
        print(f"Sentiment Label: {result.get('sentiment_label')}")
    else:
        print(f"Error: {response.status_code}")
        print(response.text)

def test_get_db_market_sentiment():
    """Test retrieving market sentiment from database"""
    response = requests.get(f"{BASE_URL}/db/market")
    
    print("\n=== DB MARKET SENTIMENT ===")
    if response.status_code == 200:
        data = response.json()
        market_sentiment = data.get('market_sentiment', {})
        
        print(f"Date: {market_sentiment.get('date')}")
        print(f"Sentiment Score: {market_sentiment.get('overall_sentiment')}")
        print(f"Sentiment Label: {market_sentiment.get('sentiment_label')}")
        print(f"Articles Analyzed: {market_sentiment.get('article_count')}")
        
        # Print positive tickers
        print("\nPositive Tickers:")
        for ticker in data.get('positive_tickers', [])[:3]:
            print(f"  {ticker.get('ticker')}: {ticker.get('sentiment_score'):.3f}")
            
        # Print latest news
        print("\nLatest News:")
        for article in data.get('latest_news', [])[:2]:
            print(f"  {article.get('title')}")
            print(f"  Source: {article.get('source')}, Score: {article.get('sentiment_score', 'N/A')}")
    else:
        print(f"Error: {response.status_code}")
        print(response.text)

def test_get_db_ticker_sentiment():
    """Test retrieving ticker sentiment from database"""
    ticker = "BBRI"
    response = requests.get(f"{BASE_URL}/db/ticker/{ticker}")
    
    print(f"\n=== DB TICKER SENTIMENT ({ticker}) ===")
    if response.status_code == 200:
        data = response.json()
        print(f"Ticker: {data.get('ticker')}")
        
        # Print sentiment history
        history = data.get('sentiment_history', [])
        print(f"History Entries: {len(history)}")
        if history:
            last_entry = history[-1]
            print(f"Latest Entry: {last_entry.get('date')}")
            print(f"Sentiment Score: {last_entry.get('sentiment_score')}")
            print(f"Sentiment Label: {last_entry.get('sentiment_label')}")
            print(f"Articles: {last_entry.get('article_count')}")
        
        # Print news
        print("\nNews Articles:")
        for article in data.get('latest_news', [])[:2]:
            print(f"  {article.get('title')}")
            print(f"  Source: {article.get('source')}, Score: {article.get('sentiment_score', 'N/A')}")
    else:
        print(f"Error: {response.status_code}")
        print(response.text)

if __name__ == "__main__":
    print("Testing News Sentiment API with Database Storage")
    print("==============================================")
    
    # First test analyzing and storing
    test_analyze_article_with_storage()
    test_market_sentiment_with_storage()
    test_ticker_sentiment_with_storage()
    
    # Then test retrieving from DB
    test_get_db_market_sentiment()
    test_get_db_ticker_sentiment()
    
    print("\nTests completed.")
