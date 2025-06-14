# Backend Files Reference

Dokumen ini memberikan referensi dan penjelasan untuk file-file penting di backend Zahaam.

## Struktur Direktori

```
backend/
├── app/                        # Kode aplikasi utama
│   ├── api/                    # API routers
│   ├── core/                   # Core functionality
│   ├── market_data/            # Market data collection
│   ├── news_data/              # News & social media
│   ├── ml/                     # Machine learning models
│   ├── models/                 # Database models (ORM)
│   ├── schemas/                # Pydantic schemas
│   └── utils/                  # Utility functions
├── alembic/                    # Database migrations
├── docker/                     # Docker configurations
├── grafana/                    # Grafana dashboards
├── logs/                       # Application logs
└── tests/                      # Unit and integration tests
```

## API Modules (`app/api/`)

### `app/api/big_data.py`

Modul ini menangani API endpoints terkait big data dan integrasi machine learning.

**Endpoints Utama:**
- `GET /data/sources`: Mendapatkan daftar sumber data yang tersedia
- `POST /data/jobs`: Membuat job pemrosesan data baru
- `GET /models`: Mendapatkan daftar model ML yang tersedia
- `POST /models/train`: Melatih model ML baru
- `GET /predictions/{ticker}`: Mendapatkan prediksi untuk saham tertentu

**Contoh Fungsi:**
```python
@router.get("/data/sources")
async def get_data_sources(db: Session = Depends(get_db)):
    """Get list of available data sources"""
    # Get all configured data sources from database
    sources = db.query(DataSource).all()
    
    # Get data source manager to check which ones are currently available
    dsm = get_data_source_manager()
    available_sources = await dsm.get_available_sources()
    
    return {
        "sources": [
            {
                "id": source.id,
                "name": source.name,
                "description": source.description,
                "is_active": source.is_active,
                "is_available": source.name in available_sources,
                "last_checked": source.last_checked,
                "status": source.status
            } for source in sources
        ]
    }
```

### `app/api/user_api.py`

Menangani API endpoints terkait manajemen pengguna.

**Endpoints Utama:**
- `POST /register`: Mendaftarkan pengguna baru
- `POST /login`: Autentikasi pengguna
- `GET /me`: Mendapatkan profil pengguna saat ini
- `PUT /me`: Memperbarui profil pengguna

### `app/api/stocks_api.py`

Menangani API endpoints terkait data saham.

**Endpoints Utama:**
- `GET /stocks/{ticker}`: Mendapatkan data saham berdasarkan ticker
- `GET /stocks/{ticker}/historical`: Mendapatkan data historis saham
- `GET /stocks/{ticker}/indicators`: Mendapatkan indikator teknikal saham

## Core Modules (`app/core/`)

### `app/core/config.py`

Konfigurasi aplikasi dengan dukungan environment variables.

**Contoh Konfigurasi:**
```python
class Settings:
    PROJECT_NAME: str = "Zahaam Stock Prediction API"
    API_V1_STR: str = "/api/v1"
    
    # Database
    POSTGRES_AI_USER: str = os.getenv("POSTGRES_AI_USER", "postgres")
    POSTGRES_AI_PASSWORD: str = os.getenv("POSTGRES_AI_PASSWORD", "password")
    POSTGRES_AI_DB: str = os.getenv("POSTGRES_AI_DB", "postgres_ai")
    POSTGRES_AI_URL: str = os.getenv(
        "POSTGRES_AI_URL",
        f"postgresql://{POSTGRES_AI_USER}:{POSTGRES_AI_PASSWORD}@postgres_ai:5432/{POSTGRES_AI_DB}"
    )

    # Redis and Celery
    REDIS_URL: str = os.getenv("REDIS_URL", "redis://redis:6379/0")
    CELERY_BROKER_URL: str = REDIS_URL
    CELERY_RESULT_BACKEND: str = REDIS_URL
```

### `app/core/ml_engine.py`

Engine untuk machine learning, menangani training dan inference.

**Komponen Utama:**
- Model manager: Mengelola model ML
- Training engine: Untuk melatih model baru
- Inference engine: Untuk melakukan prediksi

### `app/core/database.py`

Konfigurasi dan koneksi database.

**Fungsi Utama:**
```python
def get_db():
    """
    Dependency untuk mendapatkan database session
    """
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
```

### `app/core/auth.py`

Menangani autentikasi dan otorisasi.

**Fungsi Utama:**
```python
def get_current_user(
    db: Session = Depends(get_db),
    token: str = Depends(oauth2_scheme)
) -> User:
    """
    Mendapatkan pengguna saat ini dari JWT token
    """
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    
    try:
        payload = jwt.decode(
            token, settings.SECRET_KEY, algorithms=[settings.ALGORITHM]
        )
        username: str = payload.get("sub")
        if username is None:
            raise credentials_exception
    except JWTError:
        raise credentials_exception
        
    user = db.query(User).filter(User.email == username).first()
    if user is None:
        raise credentials_exception
        
    return user
```

## Market Data (`app/market_data/`)

### `app/market_data/collector.py`

Mengumpulkan data pasar dari berbagai sumber.

**Komponen Utama:**
- Market data collectors untuk berbagai sumber
- Data validation dan preprocessing
- Storage ke InfluxDB/PostgreSQL

### `app/market_data/indicators.py`

Menghitung indikator teknikal dari data pasar.

**Indikator yang Didukung:**
- Moving Averages (SMA, EMA, WMA)
- Oscillators (RSI, MACD, Stochastic)
- Volatility (Bollinger Bands, ATR)
- Volume (OBV, Volume Profile)
- Support/Resistance levels

## News Data (`app/news_data/`)

### `app/news_data/collectors.py`

Mengumpulkan data berita dan media sosial.

**Sumber Data:**
- NewsAPI
- Twitter API
- Reddit API (planned)
- Web scraping khusus

### `app/news_data/sentiment_analysis.py`

Analisis sentimen teks berita dan media sosial.

**Fitur Utama:**
- Analisis sentimen dengan VADER dan FinBERT
- Entity extraction dalam konteks keuangan
- Deteksi kata kunci dan frasa penting
- Agregasi sentimen berdasarkan entitas

### `app/news_data/models.py`

Model database untuk data berita dan media sosial.

**Model Utama:**
- NewsArticle
- SocialMediaPost
- Author
- Source
- SentimentScore
- EntityMention

## ML Models (`app/ml/`)

### `app/ml/models.py`

Model machine learning untuk prediksi saham.

**Model yang Tersedia:**
- LSTM untuk time series prediction
- XGBoost untuk faktor-driven prediction
- Prophet untuk trend/seasonal forecasting
- Ensemble models untuk gabungan prediksi

### `app/ml/training.py`

Logic untuk training dan validasi model ML.

**Komponen Utama:**
- Training pipelines
- Cross-validation
- Hyperparameter tuning
- Model persistence

### `app/ml/features.py`

Feature engineering untuk model ML.

**Fitur yang Dihasilkan:**
- Technical indicators
- Market sentiment features
- Temporal features (day of week, seasonality)
- Macro-economic indicators

## Database Models (`app/models/`)

### `app/models/stocks.py`

Model ORM untuk data saham.

**Model Utama:**
- Stock: Metadata saham
- StockPrice: Data harga OHLCV
- StockIndicator: Indikator teknikal
- StockSector: Sektor industri

### `app/models/users.py`

Model ORM untuk pengguna dan preferensi.

**Model Utama:**
- User: Pengguna sistem
- UserPreference: Preferensi penggunaan
- Watchlist: Daftar saham pantauan
- Portfolio: Portfolio saham pengguna

### `app/models/big_data.py`

Model ORM untuk komponen big data dan ML.

**Model Utama:**
- DataSource: Sumber data
- MLModel: Model ML dan konfigurasi
- StockPrediction: Prediksi harga saham
- DataProcessingJob: Job untuk pemrosesan data

## Celery Tasks (`app/tasks/`)

### `app/tasks/data_collection.py`

Celery tasks untuk pengumpulan data periodik.

**Task Utama:**
```python
@celery_app.task
def collect_market_data(symbols=None):
    """
    Collect market data for specified symbols or all active symbols
    """
    collector = MarketDataCollector()
    return collector.collect_data(symbols)

@celery_app.task
def collect_news_data(keywords=None):
    """
    Collect news data for specified keywords or all active keywords
    """
    collector = NewsDataCollector()
    return collector.collect_data(keywords)
```

### `app/tasks/ml_tasks.py`

Celery tasks untuk operasi ML asynchronous.

**Task Utama:**
```python
@celery_app.task
def train_model(model_id, params=None):
    """
    Train ML model with specified parameters
    """
    trainer = ModelTrainer()
    return trainer.train(model_id, params)

@celery_app.task
def generate_predictions(ticker, days=7):
    """
    Generate predictions for ticker
    """
    predictor = StockPredictor()
    return predictor.predict(ticker, days)
```

## Utilitas (`app/utils/`)

### `app/utils/validators.py`

Validasi data dan input.

**Fungsi Utama:**
```python
def validate_ticker_symbol(ticker: str) -> bool:
    """
    Validates if a ticker symbol is in correct format
    """
    pattern = r'^[A-Z]{1,5}(\.[A-Z]{1,2})?$'
    return bool(re.match(pattern, ticker))

def validate_date_range(start_date: date, end_date: date) -> bool:
    """
    Validates if date range is valid
    """
    return start_date < end_date and (end_date - start_date).days <= 365
```

### `app/utils/formatters.py`

Format data untuk respons API.

**Fungsi Utama:**
```python
def format_stock_data(stock_data):
    """
    Formats stock data for API response
    """
    return {
        "ticker": stock_data.ticker,
        "company_name": stock_data.company_name,
        "prices": [
            {
                "date": price.date.strftime("%Y-%m-%d"),
                "open": float(price.open),
                "high": float(price.high),
                "low": float(price.low),
                "close": float(price.close),
                "volume": int(price.volume)
            }
            for price in stock_data.prices
        ]
    }
```

## Docker Files

### `Dockerfile.api`

Dockerfile untuk API services.

**Contoh:**
```dockerfile
FROM python:3.9-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
```

### `Dockerfile.celery`

Dockerfile untuk Celery workers.

**Contoh:**
```dockerfile
FROM python:3.9-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

COPY celery-entrypoint.sh /celery-entrypoint.sh
RUN chmod +x /celery-entrypoint.sh

CMD ["/celery-entrypoint.sh"]
```

## Kesimpulan

Backend Zahaam dibagi menjadi beberapa modul yang masing-masing menangani fungsi spesifik:

- **API**: Menangani HTTP requests dan respons
- **Core**: Fungsionalitas inti seperti konfigurasi dan keamanan
- **Market Data**: Pengumpulan dan pemrosesan data pasar 
- **News Data**: Pengumpulan data berita dan analisis sentimen
- **ML**: Model machine learning untuk prediksi
- **Models**: Definisi model database
- **Tasks**: Background tasks untuk operasi asynchronous
- **Utils**: Fungsi utilitas untuk berbagai kebutuhan
