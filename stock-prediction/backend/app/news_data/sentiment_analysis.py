"""
Advanced Sentiment Analysis module for financial news and social media content.

This module provides NLP-based sentiment analysis for financial content
with specialized handling of financial terminology and market context.
"""

import os
import logging
import time
import re
import json
import numpy as np
from datetime import datetime
from typing import Dict, List, Any, Union, Optional, Tuple

# NLP libraries
import nltk
from nltk.sentiment.vader import SentimentIntensityAnalyzer
from nltk.tokenize import word_tokenize, sent_tokenize
from nltk.corpus import stopwords
import spacy

# For more advanced models when available
try:
    from transformers import pipeline, AutoModelForSequenceClassification, AutoTokenizer
    TRANSFORMERS_AVAILABLE = True
except ImportError:
    TRANSFORMERS_AVAILABLE = False

# Set up logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class FinancialSentimentAnalyzer:
    """Advanced sentiment analyzer for financial text"""
    
    def __init__(self):
        """Initialize the sentiment analyzer with NLP models"""
        self.model_type = os.environ.get("SENTIMENT_MODEL_TYPE", "vader")
        
        # Download necessary NLTK resources
        try:
            nltk.download('vader_lexicon', quiet=True)
            nltk.download('punkt', quiet=True)
            nltk.download('stopwords', quiet=True)
            self.vader = SentimentIntensityAnalyzer()
            logger.info("VADER sentiment analyzer initialized")
        except Exception as e:
            logger.error(f"Error initializing VADER: {str(e)}")
            self.vader = None
        
        # Initialize spaCy for NER and dependency parsing
        try:
            self.nlp = spacy.load("en_core_web_sm")
            logger.info("spaCy NLP model loaded")
        except Exception as e:
            logger.error(f"Error loading spaCy model: {str(e)}")
            self.nlp = None
        
        # Initialize FinBERT if available
        self.finbert = None
        if TRANSFORMERS_AVAILABLE and self.model_type == "finbert":
            try:
                model_name = "ProsusAI/finbert"
                self.finbert = pipeline("sentiment-analysis", model=model_name)
                logger.info("FinBERT model initialized")
            except Exception as e:
                logger.error(f"Error initializing FinBERT: {str(e)}")
        
        # Load financial sentiment lexicon (custom dictionary)
        self.financial_lexicon = self._load_financial_lexicon()
        
        # Specific negation handling for financial contexts
        self.negation_terms = ["not", "no", "never", "neither", "nor", "none", 
                               "aren't", "isn't", "wasn't", "weren't", "haven't", 
                               "hasn't", "hadn't", "doesn't", "don't", "didn't"]
        
        # Financial entities of interest for NER
        self.financial_entities = ["ORG", "PERSON", "GPE", "MONEY", "PERCENT"]
        
    def _load_financial_lexicon(self) -> Dict[str, float]:
        """Load custom financial sentiment lexicon"""
        # This would normally load from a file, here we define a small subset
        lexicon = {
            # Positive financial terms
            "bullish": 0.8, "uptrend": 0.7, "growth": 0.6, "profit": 0.7,
            "rally": 0.7, "upgrade": 0.6, "beat": 0.6, "outperform": 0.7,
            "strong": 0.5, "positive": 0.6, "gain": 0.6, "rise": 0.5,
            "recovery": 0.6, "opportunity": 0.5, "breakthrough": 0.7,
            
            # Negative financial terms
            "bearish": -0.8, "downtrend": -0.7, "recession": -0.8, "loss": -0.7,
            "decline": -0.6, "downgrade": -0.6, "miss": -0.5, "underperform": -0.7,
            "weak": -0.5, "negative": -0.6, "fall": -0.5, "drop": -0.6,
            "bankruptcy": -0.9, "crisis": -0.8, "volatility": -0.4,
            
            # Neutral or context-dependent terms
            "volatile": -0.3, "flat": 0.0, "unchanged": 0.0,
            "expected": 0.1, "forecast": 0.0, "guidance": 0.0
        }
        
        logger.info(f"Loaded {len(lexicon)} terms in financial lexicon")
        return lexicon
    
    def _preprocess_text(self, text: str) -> str:
        """Preprocess text for sentiment analysis"""
        if not text:
            return ""
            
        # Convert to lowercase
        text = text.lower()
        
        # Remove URLs
        text = re.sub(r'https?://\S+|www\.\S+', '', text)
        
        # Remove stock tickers (e.g., $AAPL)
        tickers = re.findall(r'\$[A-Za-z]+', text)
        clean_text = re.sub(r'\$[A-Za-z]+', '', text)
        
        # Remove extra whitespace
        clean_text = re.sub(r'\s+', ' ', clean_text).strip()
        
        return clean_text
    
    def _extract_entities(self, text: str) -> List[Dict[str, Any]]:
        """Extract named entities from text using spaCy"""
        if not self.nlp or not text:
            return []
            
        try:
            doc = self.nlp(text)
            entities = []
            
            for ent in doc.ents:
                if ent.label_ in self.financial_entities:
                    entities.append({
                        "text": ent.text,
                        "label": ent.label_,
                        "start": ent.start_char,
                        "end": ent.end_char
                    })
            
            return entities
        except Exception as e:
            logger.error(f"Error extracting entities: {str(e)}")
            return []
    
    def _analyze_with_vader(self, text: str) -> Dict[str, float]:
        """Analyze sentiment using VADER"""
        if not self.vader or not text:
            return {"compound": 0.0, "pos": 0.0, "neu": 0.0, "neg": 0.0}
            
        try:
            # Apply VADER sentiment analysis
            scores = self.vader.polarity_scores(text)
            
            # Apply financial lexicon additions
            words = word_tokenize(text.lower())
            
            for i, word in enumerate(words):
                if word in self.financial_lexicon:
                    # Check for negation in 3-word window before the term
                    negation = False
                    for j in range(max(0, i-3), i):
                        if words[j] in self.negation_terms:
                            negation = True
                            break
                    
                    # Apply lexicon score, inverting if negated
                    lex_score = self.financial_lexicon[word]
                    if negation:
                        lex_score *= -0.7  # Partial inversion to reflect real-world usage
                    
                    # Adjust compound score (weighted)
                    scores["compound"] = scores["compound"] * 0.7 + lex_score * 0.3
                    
                    # Ensure compound stays in [-1, 1]
                    scores["compound"] = max(-1.0, min(1.0, scores["compound"]))
            
            return scores
        except Exception as e:
            logger.error(f"Error in VADER analysis: {str(e)}")
            return {"compound": 0.0, "pos": 0.0, "neu": 0.0, "neg": 0.0}
    
    def _analyze_with_finbert(self, text: str) -> Dict[str, float]:
        """Analyze sentiment using FinBERT model"""
        if not self.finbert or not text:
            return {"label": "neutral", "score": 0.5}
            
        try:
            # FinBERT works better with shorter texts, so chunk if necessary
            max_length = 512
            if len(text) > max_length:
                chunks = [text[i:i+max_length] for i in range(0, len(text), max_length)]
                results = []
                
                for chunk in chunks:
                    result = self.finbert(chunk)
                    results.extend(result)
                
                # Aggregate results
                labels = [r["label"] for r in results]
                scores = [r["score"] for r in results]
                
                # Find most common label
                pos_count = labels.count("positive")
                neg_count = labels.count("negative")
                neu_count = labels.count("neutral")
                
                if pos_count >= neg_count and pos_count >= neu_count:
                    final_label = "positive"
                elif neg_count > pos_count and neg_count > neu_count:
                    final_label = "negative"
                else:
                    final_label = "neutral"
                
                # Average score
                final_score = sum(scores) / len(scores)
                
                return {"label": final_label, "score": final_score}
            else:
                result = self.finbert(text)[0]
                return {"label": result["label"], "score": result["score"]}
                
        except Exception as e:
            logger.error(f"Error in FinBERT analysis: {str(e)}")
            return {"label": "neutral", "score": 0.5}
    
    def analyze_text(self, text: str, include_entities: bool = True) -> Dict[str, Any]:
        """
        Analyze sentiment of financial text
        
        Args:
            text: The text to analyze
            include_entities: Whether to extract named entities
            
        Returns:
            Dict with sentiment analysis results
        """
        if not text:
            return {
                "sentiment": "neutral",
                "score": 0.0,
                "confidence": 0.0,
                "analysis": {},
                "entities": []
            }
        
        start_time = time.time()
        
        # Preprocess text
        clean_text = self._preprocess_text(text)
        
        # Extract named entities if requested
        entities = self._extract_entities(clean_text) if include_entities else []
        
        # Analyze sentiment using selected model
        if self.model_type == "finbert" and self.finbert:
            analysis = self._analyze_with_finbert(clean_text)
            sentiment = analysis["label"]
            score = (analysis["score"] - 0.5) * 2  # Convert to [-1, 1] range
            confidence = analysis["score"]
        else:
            # Default to VADER
            analysis = self._analyze_with_vader(clean_text)
            
            # Convert compound score to sentiment label
            compound = analysis["compound"]
            if compound >= 0.05:
                sentiment = "positive"
            elif compound <= -0.05:
                sentiment = "negative"
            else:
                sentiment = "neutral"
                
            score = compound
            confidence = max(analysis["pos"], analysis["neg"], analysis["neu"])
        
        duration = time.time() - start_time
        
        return {
            "sentiment": sentiment,
            "score": score,
            "confidence": confidence,
            "analysis": analysis,
            "entities": entities,
            "processing_time": duration
        }
    
    def analyze_news_article(self, article: Dict[str, Any]) -> Dict[str, Any]:
        """
        Analyze sentiment of a news article
        
        Args:
            article: Dict containing news article data
            
        Returns:
            Dict with article data and sentiment analysis
        """
        # Extract text from article
        title = article.get("title", "")
        description = article.get("description", "")
        content = article.get("content", "")
        
        # Combine text, giving higher weight to title
        full_text = f"{title}. {title}. {description}. {content}"
        
        # Analyze sentiment
        sentiment = self.analyze_text(full_text)
        
        # Add sentiment to article data
        result = article.copy()
        result["sentiment_analysis"] = sentiment
        
        # Determine sentiment direction and magnitude
        score = sentiment["score"]
        if score >= 0.5:
            result["sentiment_direction"] = "very_positive"
        elif 0.05 <= score < 0.5:
            result["sentiment_direction"] = "positive"
        elif -0.05 < score < 0.05:
            result["sentiment_direction"] = "neutral"
        elif -0.5 < score <= -0.05:
            result["sentiment_direction"] = "negative"
        else:
            result["sentiment_direction"] = "very_negative"
            
        return result
    
    def analyze_tweet(self, tweet: Dict[str, Any]) -> Dict[str, Any]:
        """
        Analyze sentiment of a tweet
        
        Args:
            tweet: Dict containing tweet data
            
        Returns:
            Dict with tweet data and sentiment analysis
        """
        # Extract text from tweet
        text = tweet.get("text", "")
        
        # Analyze sentiment
        sentiment = self.analyze_text(text)
        
        # Add sentiment to tweet data
        result = tweet.copy()
        result["sentiment_analysis"] = sentiment
        
        # Determine sentiment direction and magnitude
        score = sentiment["score"]
        if score >= 0.5:
            result["sentiment_direction"] = "very_positive"
        elif 0.05 <= score < 0.5:
            result["sentiment_direction"] = "positive"
        elif -0.05 < score < 0.05:
            result["sentiment_direction"] = "neutral"
        elif -0.5 < score <= -0.05:
            result["sentiment_direction"] = "negative"
        else:
            result["sentiment_direction"] = "very_negative"
            
        return result
    
    def analyze_bulk(self, items: List[Dict[str, Any]], item_type: str = "article") -> List[Dict[str, Any]]:
        """
        Analyze sentiment for multiple items
        
        Args:
            items: List of items to analyze
            item_type: Type of items ('article' or 'tweet')
            
        Returns:
            List of items with sentiment analysis
        """
        results = []
        
        for item in items:
            if item_type == "article":
                result = self.analyze_news_article(item)
            elif item_type == "tweet":
                result = self.analyze_tweet(item)
            else:
                # Generic analysis
                text = item.get("text", item.get("content", ""))
                sentiment = self.analyze_text(text)
                result = item.copy()
                result["sentiment_analysis"] = sentiment
                
            results.append(result)
            
        return results
    
    def get_aggregated_sentiment(self, items: List[Dict[str, Any]]) -> Dict[str, Any]:
        """
        Calculate aggregated sentiment across multiple items
        
        Args:
            items: List of items with sentiment analysis
            
        Returns:
            Dict with aggregated sentiment metrics
        """
        if not items:
            return {
                "overall": "neutral",
                "score": 0.0,
                "confidence": 0.0,
                "distribution": {
                    "very_positive": 0,
                    "positive": 0,
                    "neutral": 0,
                    "negative": 0,
                    "very_negative": 0
                },
                "count": 0
            }
        
        # Initialize counters
        distribution = {
            "very_positive": 0,
            "positive": 0,
            "neutral": 0,
            "negative": 0,
            "very_negative": 0
        }
        
        scores = []
        confidences = []
        
        # Process each item
        for item in items:
            direction = item.get("sentiment_direction", "neutral")
            distribution[direction] += 1
            
            analysis = item.get("sentiment_analysis", {})
            score = analysis.get("score", 0.0)
            confidence = analysis.get("confidence", 0.0)
            
            scores.append(score)
            confidences.append(confidence)
        
        # Calculate weighted average score based on confidence
        if scores and confidences:
            total_confidence = sum(confidences)
            if total_confidence > 0:
                weighted_score = sum(s * c for s, c in zip(scores, confidences)) / total_confidence
            else:
                weighted_score = sum(scores) / len(scores)
        else:
            weighted_score = 0.0
        
        # Determine overall sentiment
        if weighted_score >= 0.5:
            overall = "very_positive"
        elif 0.05 <= weighted_score < 0.5:
            overall = "positive"
        elif -0.05 < weighted_score < 0.05:
            overall = "neutral"
        elif -0.5 < weighted_score <= -0.05:
            overall = "negative"
        else:
            overall = "very_negative"
        
        # Calculate average confidence
        avg_confidence = sum(confidences) / len(confidences) if confidences else 0.0
        
        return {
            "overall": overall,
            "score": weighted_score,
            "confidence": avg_confidence,
            "distribution": distribution,
            "count": len(items)
        }


# Singleton instance for reuse
sentiment_analyzer = None

def get_sentiment_analyzer() -> FinancialSentimentAnalyzer:
    """Get or create sentiment analyzer singleton"""
    global sentiment_analyzer
    if sentiment_analyzer is None:
        sentiment_analyzer = FinancialSentimentAnalyzer()
    return sentiment_analyzer


if __name__ == "__main__":
    # Test the sentiment analyzer
    analyzer = get_sentiment_analyzer()
    
    test_texts = [
        "The company reported strong earnings, beating expectations.",
        "The stock plunged after the CEO resigned amid scandal.",
        "Markets remained flat as investors await the Fed's decision.",
        "Despite a challenging quarter, the outlook remains positive.",
        "Analysts have downgraded the stock due to concerns about growth."
    ]
    
    for text in test_texts:
        print(f"\nText: {text}")
        result = analyzer.analyze_text(text)
        print(f"Sentiment: {result['sentiment']}, Score: {result['score']:.2f}")
