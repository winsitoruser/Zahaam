# Data Flows

Dokumen ini menjelaskan aliran data dalam sistem backend Zahaam.

## Diagram Aliran Data Utama

```
┌─────────────┐      ┌──────────────┐      ┌────────────────┐
│ Data        │      │ Processing   │      │ Storage        │
│ Collection  │─────▶│ Pipeline     │─────▶│ Layer          │
└─────────────┘      └──────────────┘      └────────────────┘
      │                     │                     │
      │                     ▼                     │
      │              ┌──────────────┐             │
      └─────────────▶│ ML Pipeline  │◀────────────┘
                     └──────────────┘
                            │
                            ▼
┌─────────────┐      ┌──────────────┐      ┌────────────────┐
│ API         │◀─────│ Prediction   │◀─────│ Notification   │
│ Layer       │─────▶│ Service      │─────▶│ Service        │
└─────────────┘      └──────────────┘      └────────────────┘
```

## Aliran Data Pasar

### 1. Collection Flow

```
Market Data Sources (Yahoo Finance, IDX, etc.)
          │
          ▼
    MarketDataCollector
          │
          ├───────┐
          │       │
          ▼       ▼
    Raw Storage   Data Validation
  (InfluxDB Time  (Filter outliers,
   Series Data)   handle missing data)
                  │
                  ▼
            Data Transformation
            (Resampling, normalization)
                  │
                  ▼
            Feature Engineering
            (Technical indicators)
                  │
                  ▼
            Processed Storage
            (PostgreSQL)
```

**Implementasi:**

```python
# app/market_data/collector.py
class MarketDataCollector:
    """
    Collects market data from various sources and stores in InfluxDB/PostgreSQL
    """
    
    async def collect_data(self, symbols=None, timeframe="1d"):
        """
        Collect market data for specified symbols
        
        Args:
            symbols: List of stock symbols to collect data for
            timeframe: Data timeframe (1m, 5m, 1h, 1d, etc.)
            
        Returns:
            Dict collection results
        """
        # Implementation of data collection
        
        # 1. Fetch data from sources
        raw_data = await self._fetch_data_from_sources(symbols, timeframe)
        
        # 2. Validate and clean data
        validated_data = self._validate_data(raw_data)
        
        # 3. Store raw data in InfluxDB
        await self._store_raw_data(validated_data)
        
        # 4. Transform and process data
        processed_data = self._process_data(validated_data)
        
        # 5. Store processed data in PostgreSQL
        await self._store_processed_data(processed_data)
        
        return {
            "collected": len(raw_data),
            "validated": len(validated_data),
            "processed": len(processed_data)
        }
```

## Aliran Data Berita & Media Sosial

### 1. Collection Flow

```
News & Social Sources
(NewsAPI, Twitter, Reddit)
          │
          ▼
    NewsDataCollector
          │
          ├───────┐
          │       │
          ▼       ▼
    Raw Storage   Content Filtering
  (MongoDB/       (Relevance check,
   PostgreSQL)    duplicate removal)
                  │
                  ▼
            Content Processing
            (NLP preprocessing)
                  │
                  ▼
            Sentiment Analysis
            (VADER, FinBERT)
                  │
                  ▼
            Entity Extraction
            (Company, ticker recognition)
                  │
                  ▼
            Processed Storage
            (PostgreSQL)
```

**Implementasi:**

```python
# app/news_data/collectors.py
class NewsDataCollector:
    """
    Collects news data from various sources
    """
    
    async def collect_data(self, keywords=None, sources=None):
        """
        Collect news data based on keywords and sources
        
        Args:
            keywords: List of keywords to search for
            sources: List of sources to collect from
            
        Returns:
            Dict collection results
        """
        # Implementation of news collection
        
        # 1. Fetch data from sources
        articles = await self._fetch_from_sources(keywords, sources)
        
        # 2. Filter for relevance and duplicates
        filtered_articles = self._filter_articles(articles)
        
        # 3. Store raw articles
        await self._store_raw_articles(filtered_articles)
        
        # 4. Process content and extract entities
        processed_articles = await self._process_articles(filtered_articles)
        
        # 5. Analyze sentiment
        analyzed_articles = await self._analyze_sentiment(processed_articles)
        
        # 6. Store processed articles
        await self._store_processed_articles(analyzed_articles)
        
        return {
            "collected": len(articles),
            "filtered": len(filtered_articles),
            "processed": len(processed_articles)
        }
```

### 2. Sentiment Processing Flow

```
    Raw Text Content
          │
          ▼
    Text Preprocessing
    (Cleaning, normalization)
          │
          ▼
    Entity Recognition
    (Companies, people, events)
          │
          ├───────────────┐
          │               │
          ▼               ▼
    VADER Analysis     FinBERT Analysis
    (Rule-based)       (Transformer-based)
          │               │
          └───────┬───────┘
                  │
                  ▼
          Sentiment Aggregation
          (Weighted average)
                  │
                  ▼
          Entity-Sentiment Mapping
          (Link sentiment to entities)
                  │
                  ▼
          Sentiment Storage
          (PostgreSQL)
```

## ML Pipeline Data Flow

### 1. Training Flow

```
    Historical Data
    (Market + News)
          │
          ▼
    Feature Engineering
          │
          ▼
    Train/Test Split
          │
          ├───────────────┐
          │               │
          ▼               ▼
    Model Training    Hyperparameter
                      Optimization
          │               │
          └───────┬───────┘
                  │
                  ▼
          Model Validation
                  │
                  ▼
          Model Registration
          (Version control)
                  │
                  ▼
          Model Storage
          (File system + metadata DB)
```

### 2. Inference Flow

```
    Client Request
          │
          ▼
    API Gateway
          │
          ▼
    Feature Generation
    (Live data features)
          │
          ▼
    Model Selection
    (Choose best model)
          │
          ▼
    Inference
    (Generate prediction)
          │
          ▼
    Post-processing
    (Format, confidence intervals)
          │
          ▼
    Store Prediction
    (PostgreSQL)
          │
          ▼
    Return Result
    (API Response)
```

**Implementasi:**

```python
# app/ml/prediction.py
class StockPredictor:
    """
    Predicts stock prices using trained ML models
    """
    
    async def predict(self, ticker, days=7):
        """
        Generate prediction for a stock
        
        Args:
            ticker: Stock symbol
            days: Number of days to predict
            
        Returns:
            Dict prediction results
        """
        # Implementation of prediction
        
        # 1. Get latest data
        market_data = await self._get_latest_market_data(ticker)
        sentiment_data = await self._get_latest_sentiment_data(ticker)
        
        # 2. Generate features
        features = self._generate_features(market_data, sentiment_data)
        
        # 3. Select best model
        model = await self._select_model(ticker)
        
        # 4. Make prediction
        raw_predictions = await self._predict_with_model(model, features, days)
        
        # 5. Add confidence intervals
        predictions = self._add_confidence_intervals(raw_predictions)
        
        # 6. Store prediction
        await self._store_prediction(ticker, predictions)
        
        # 7. Format response
        return self._format_prediction_response(ticker, predictions)
```

## API Data Flow

### 1. User Authentication Flow

```
    Client
      │
      ▼
  API Gateway
      │
      ▼
  Authenticate
  (Validate JWT)
      │
      ▼
  Authorize
  (Check permissions)
      │
      ▼
  Route Request
      │
      ▼
  Process Request
```

### 2. Prediction API Flow

```
    Client Request
      │
      ▼
  API Gateway
      │
      ▼
  Auth Middleware
      │
      ▼
  Prediction Router
      │
      ├───────────┐
      │           │
      ▼           ▼
  Check Cache   Rate Limiter
      │           │
      └─────┬─────┘
            │
            ▼
    Prediction Service
            │
            ▼
    Format Response
            │
            ▼
    Return Result
```

## Integration between Components

### 1. News to ML Integration

```
News Collection
      │
      ▼
Sentiment Analysis
      │
      ▼
Entity Extraction
      │
      ▼
Sentiment Aggregation
      │
      ▼
Feature Generation ◀───── Market Data
      │                    Features
      │
      ▼
ML Model Input
```

### 2. User Actions to Notifications

```
User Sets Alerts
      │
      ▼
Alert Stored in DB
      │
      ▼
Background Monitor ◀───── New Market Data
      │                    New Predictions
      │                    New Sentiment
      │
      ▼
Alert Condition Check
      │
      ▼
Notification Generated
      │
      ▼
Notification Sent
      │
      ▼
Notification Logged
```

## Database Interactions

### 1. PostgreSQL AI Database

Menyimpan data machine learning, prediksi, dan data pasar terproses.

**Core Tables:**
- `stocks`: Informasi dasar tentang saham
- `stock_prices`: Data harga historis
- `stock_indicators`: Indikator teknikal
- `predictions`: Hasil prediksi ML
- `ml_models`: Metadata model ML
- `features`: Fitur untuk model ML

### 2. PostgreSQL User Database

Menyimpan data pengguna, preferensi, dan interaksi.

**Core Tables:**
- `users`: Data pengguna
- `watchlists`: Daftar pantau saham
- `portfolios`: Portfolio saham
- `alerts`: Alert settings
- `notifications`: Notifikasi pengguna
- `user_actions`: Log aktivitas pengguna

### 3. InfluxDB Time Series Database

Menyimpan data time series mentah dan metrics.

**Core Measurements:**
- `market_data`: Data pasar raw (OHLCV)
- `system_metrics`: Metrics sistem
- `app_metrics`: Metrics aplikasi
- `model_metrics`: Metrics model ML

## Data Transformation dan Enrichment

### 1. Market Data Enrichment

Raw market data diperkaya dengan:
1. Technical indicators (RSI, MACD, Bollinger, etc.)
2. Anomaly scores
3. Volatility measures
4. Correlation dengan index

### 2. News Data Enrichment

Raw news data diperkaya dengan:
1. Sentiment scores
2. Entity extraction
3. Topic classification
4. Relevance scoring

### 3. Data Integration Points

Proses integrasi data terjadi di:
1. Feature engineering untuk ML
2. Dashboard data untuk visualisasi
3. Alert processing untuk notifications

## Celery Tasks untuk Data Processing

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ Task Scheduler  │────▶│ Celery Queue    │────▶│ Celery Workers  │
│ (Celery Beat)   │     │ (Redis/RabbitMQ)│     │                 │
└─────────────────┘     └─────────────────┘     └─────────────────┘
                                                        │
                                                        ▼
                                                ┌─────────────────┐
                                                │ Task Processing │
                                                │                 │
                                                └─────────────────┘
                                                        │
                                                        ▼
                        ┌─────────────────┐     ┌─────────────────┐
                        │ Task Monitoring │◀────│ Result Storage  │
                        │ (Flower)        │     │ (Redis)         │
                        └─────────────────┘     └─────────────────┘
```

**Core Tasks:**
1. `collect_market_data`: Collect market data periodically
2. `collect_news_data`: Collect news and social media data
3. `analyze_sentiment`: Perform sentiment analysis
4. `generate_features`: Generate features for ML
5. `train_models`: Regular model training
6. `generate_predictions`: Generate batch predictions
7. `process_alerts`: Check for alert conditions

## Data Retention dan Archival

1. **Hot Data**: Akses cepat, simpan di PostgreSQL (last 3 months)
2. **Warm Data**: Akses medium, simpan di InfluxDB (3-12 months)
3. **Cold Data**: Akses jarang, simpan di object storage (> 12 months)

## Contoh End-to-End Data Flow

### Kasus: Prediksi Saham Berdasarkan News Event

1. **Collection Phase**:
   - NewsDataCollector mengumpulkan berita tentang saham target
   - MarketDataCollector mengumpulkan data pasar terbaru

2. **Processing Phase**:
   - Sentiment Analysis menghitung sentimen dari berita
   - Technical Analysis menghitung indikator teknikal

3. **Feature Engineering**:
   - Sentiment features digabungkan dengan technical features
   - Market context features ditambahkan

4. **Prediction Phase**:
   - ML Model membuat prediksi berdasarkan features
   - Confidence intervals dihitung

5. **Delivery Phase**:
   - Prediksi disimpan di database
   - Notifikasi dikirim ke pengguna yang mengaktifkan alerts
   - API mengembalikan hasil prediksi
