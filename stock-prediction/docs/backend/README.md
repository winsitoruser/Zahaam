# Zahaam Backend Documentation

## Overview

Zahaam backend adalah sistem prediksi pasar saham yang komprehensif dengan kemampuan analisis sentimen pasar terintegrasi. Backend dirancang dengan arsitektur microservice yang dikelola melalui Docker Compose dan mencakup API untuk berbagai kebutuhan seperti prediksi AI, manajemen pengguna, dan administrasi sistem.

## Arsitektur Sistem

Sistem backend Zahaam terdiri dari beberapa komponen utama:

```
backend/
├── app/
│   ├── api/                # API routes untuk berbagai layanan
│   │   ├── admin_api.py    # API untuk administrasi sistem
│   │   ├── ai_lab.py       # API untuk eksperimen AI
│   │   ├── backtesting.py  # API untuk backtesting strategi
│   │   ├── dashboard.py    # API untuk data dashboard
│   │   ├── ml_prediction.py # API untuk prediksi ML
│   │   ├── notifications.py # API untuk notifikasi
│   │   ├── portfolio.py    # API untuk manajemen portofolio
│   │   ├── stock_search.py # API untuk pencarian saham
│   │   ├── strategy.py     # API untuk manajemen strategi
│   │   ├── technical_analysis.py # API untuk analisis teknikal
│   │   ├── tasks_api.py    # API untuk manajemen tugas
│   │   └── user_api.py     # API untuk manajemen pengguna
│   │
│   ├── core/               # Fungsi inti sistem
│   │   ├── auth.py         # Otentikasi dan otorisasi
│   │   ├── celery_app.py   # Konfigurasi Celery untuk background tasks
│   │   ├── config.py       # Konfigurasi sistem
│   │   ├── news_scraper.py # Web scraper untuk berita finansial
│   │   └── security.py     # Keamanan sistem
│   │
│   ├── market_data/        # Modul pengumpulan data pasar
│   │   ├── __init__.py
│   │   └── collector.py    # Kolektor data pasar saham (menggunakan yfinance)
│   │
│   ├── news_data/          # Modul pengumpulan & analisis data berita
│   │   ├── __init__.py
│   │   ├── collector.py    # Kolektor data berita (NewsAPI)
│   │   ├── config.py       # Konfigurasi pengumpulan data
│   │   ├── models.py       # Model database untuk data berita & media sosial
│   │   ├── sentiment_analysis.py # Analisis sentimen lanjutan
│   │   └── social_media.py # Kolektor data media sosial (Twitter/X)
│   │
│   ├── models/             # Model database
│   │   ├── ai_lab.py       # Model untuk eksperimen AI
│   │   ├── backtesting.py  # Model untuk backtesting
│   │   ├── portfolio.py    # Model untuk portofolio
│   │   └── user.py         # Model untuk pengguna
│   │
│   ├── tasks/              # Background tasks dengan Celery
│   │   ├── __init__.py
│   │   ├── maintenance_tasks.py # Tugas-tugas pemeliharaan
│   │   ├── ml_tasks.py     # Tugas-tugas machine learning
│   │   └── stock_tasks.py  # Tugas-tugas pengumpulan data saham
│   │
│   ├── utils/              # Utilitas
│   │   ├── influxdb_client.py # Klien InfluxDB untuk time series
│   │   └── monitoring.py   # Monitoring sistem
│   │
│   └── main.py             # Aplikasi FastAPI utama
│
├── Dockerfile              # Dockerfile untuk layanan utama
├── Dockerfile.celery       # Dockerfile untuk worker Celery
├── docker-compose.yml      # Konfigurasi Docker Compose
└── requirements.txt        # Dependensi Python
```

## Microservices

Sistem backend Zahaam menggunakan arsitektur microservice yang dikelola melalui Docker Compose. Berikut komponen utamanya:

### 1. API Services

- **User API** (Port 8000): Menangani autentikasi, registrasi, dan manajemen profil pengguna
- **AI Prediction API** (Port 8001): Menangani prediksi AI dan analisis sentimen pasar
- **Admin API** (Port 8002): Menangani administrasi sistem dan monitoring

### 2. Database Services

- **PostgreSQL AI**: Database untuk model AI, data prediksi, dan analisis sentimen
- **PostgreSQL User**: Database untuk data pengguna, autentikasi, dan preferensi
- **InfluxDB**: Database time-series untuk data pasar real-time dan metrik sistem

### 3. Middleware Services

- **Redis**: Cache untuk data yang sering diakses dan session management
- **RabbitMQ**: Message broker untuk komunikasi antar layanan dan background tasks

### 4. Data Collection Services

- **Market Data Collector**: Mengumpulkan data pasar saham dari Yahoo Finance
- **News Data Collector**: Mengumpulkan data berita dan media sosial

### 5. Monitoring Services

- **Grafana**: Visualisasi dashboard untuk monitoring sistem dan data pasar
- **Flower**: Monitoring untuk tugas-tugas Celery

## Integrasi API Berita dan Media Sosial

Fitur terbaru pada backend Zahaam adalah integrasi API berita dan media sosial yang komprehensif untuk menganalisis sentimen pasar.

### 1. Pengumpulan Data Berita

Sistem mengumpulkan berita finansial dari berbagai sumber menggunakan NewsAPI:

```python
# Contoh kode penggunaan NewsAPI
class NewsAPICollector:
    def __init__(self):
        self.api_key = os.environ.get("NEWSAPI_KEY")
        self.base_url = "https://newsapi.org/v2"
        
    def get_financial_news(self, query, from_date, to_date, page=1):
        # Kode pengambilan berita finansial
```

Fitur-fitur utama:
- Pengumpulan berita dari sumber-sumber finansial terkemuka (Bloomberg, CNBC, Reuters, dll)
- Pengumpulan berita berdasarkan ticker saham spesifik
- Pengumpulan berita berdasarkan keyword keuangan (inflasi, suku bunga, dll)
- Penyimpanan artikel berita di PostgreSQL AI dan metrik di InfluxDB

### 2. Pengumpulan Data Media Sosial

Sistem mengumpulkan data dari Twitter/X dan platform media sosial lainnya:

```python
# Contoh kode penggunaan Twitter API
class TwitterCollector:
    def __init__(self):
        self.bearer_token = os.environ.get("TWITTER_BEARER_TOKEN")
        self.api_key = os.environ.get("TWITTER_API_KEY")
        self.api_secret = os.environ.get("TWITTER_API_SECRET")
        
    def get_cashtag_tweets(self, ticker, max_results=100):
        # Kode pengambilan tweet dengan cashtag $TICKER
```

Fitur-fitur utama:
- Pengumpulan tweet dengan cashtag saham ($AAPL, $MSFT, dll)
- Tracking influencer keuangan dan analis
- Pengumpulan data sentimen pasar dan sektor
- Penyimpanan dan analisis tweet di PostgreSQL AI dan metrik di InfluxDB

### 3. Analisis Sentimen Lanjutan

Modul analisis sentimen menggunakan teknik NLP canggih untuk menganalisis sentimen berita dan media sosial:

```python
class FinancialSentimentAnalyzer:
    def __init__(self):
        self.model_type = os.environ.get("SENTIMENT_MODEL_TYPE", "vader")
        
    def analyze_text(self, text, include_entities=True):
        # Kode analisis sentimen teks finansial
```

Fitur-fitur utama:
- Analisis sentimen menggunakan VADER dan FinBERT
- Ekstraksi entitas dan relasi
- Kustomisasi leksikon terminologi keuangan
- Agregasi sentimen untuk ticker dan sektor
- Perhitungan skor sentimen yang disesuaikan dengan konteks keuangan

### 4. Model Database untuk Data Berita dan Media Sosial

Sistem menggunakan model SQLAlchemy ORM yang komprehensif:

```python
class NewsArticle(Base):
    __tablename__ = 'news_articles'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    # Detail model database untuk artikel berita
```

Model-model utama:
- `DataSource`: Sumber data berita atau media sosial
- `NewsArticle`: Artikel berita finansial
- `SocialMediaAuthor`: Penulis/akun media sosial
- `SocialMediaPost`: Post dari platform media sosial
- `SentimentAggregate`: Agregasi sentimen untuk ticker dan pasar
- `SentimentImpact`: Analisis dampak sentimen pada harga saham

## Konfigurasi dengan Docker Compose

Layanan dikelola dan diorkestrasi menggunakan Docker Compose:

```yaml
version: '3.8'

services:
  postgres_ai:
    image: postgres:15
    environment:
      - POSTGRES_USER=postgres
      - POSTGRES_PASSWORD=postgres
      - POSTGRES_DB=postgres_ai
    volumes:
      - postgres_ai_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"

  market_data_collector:
    build:
      context: .
      dockerfile: Dockerfile.celery
    # Konfigurasi market data collector
    
  news_data_collector:
    build:
      context: .
      dockerfile: Dockerfile.celery
    # Konfigurasi news data collector
```

## Environment Variables

Berikut adalah environment variables utama yang digunakan oleh sistem:

### API Keys
- `NEWSAPI_KEY`: API key untuk NewsAPI.org
- `TWITTER_BEARER_TOKEN`, `TWITTER_API_KEY`, `TWITTER_API_SECRET`: Kredensial Twitter API
- `INFLUXDB_TOKEN`: Token untuk akses InfluxDB

### Database URLs
- `POSTGRES_AI_URL`: URL koneksi ke database PostgreSQL AI
- `POSTGRES_USER_URL`: URL koneksi ke database PostgreSQL User

### Configuration
- `SENTIMENT_MODEL_TYPE`: Tipe model untuk analisis sentimen (`vader` atau `finbert`)
- `NEWSAPI_COLLECTION_INTERVAL`: Interval pengumpulan berita dalam menit
- `TWITTER_COLLECTION_INTERVAL`: Interval pengumpulan tweet dalam menit

### Security
- `SECRET_KEY`: Secret key untuk enkripsi
- `ALGORITHM`: Algoritma untuk JWT authentication

## API Endpoints

### User API (Port 8000)

- `POST /api/users/register`: Registrasi pengguna baru
- `POST /api/users/login`: Login pengguna
- `GET /api/users/me`: Informasi pengguna
- `PUT /api/users/me`: Update informasi pengguna

### AI Prediction API (Port 8001)

- `GET /api/prediction/{ticker}`: Prediksi harga saham
- `GET /api/sentiment/{ticker}`: Analisis sentimen untuk saham
- `GET /api/sentiment/market`: Analisis sentimen pasar keseluruhan

### Admin API (Port 8002)

- `GET /api/admin/stats`: Statistik sistem
- `GET /api/admin/users`: Manajemen pengguna
- `GET /api/admin/data-collectors`: Status data collector

## Monitoring dan Observability

Sistem menggunakan InfluxDB dan Grafana untuk monitoring komprehensif:

- **Metrik pengumpulan data**: Jumlah artikel/tweet, waktu pemrosesan, error
- **Metrik analisis sentimen**: Distribusi sentimen, akurasi, confidence
- **Metrik sistem**: CPU, memory, disk usage, request rate

Grafana dashboard tersedia di:
```
http://localhost:3000
```

Flower untuk monitoring Celery tasks tersedia di:
```
http://localhost:5555
```

## Arsitektur Microservice

Berikut adalah diagram arsitektur tingkat tinggi dari sistem backend Zahaam:

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│     Client      │──────▶│   API Gateway   │──────▶│  FastAPI User   │
└─────────────────┘       └─────────────────┘       └─────────────────┘
                                   │                          │
                                   ▼                          ▼
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│ News Collector  │─────┐ │ FastAPI AI Pred │◀──────│ PostgreSQL User │
└─────────────────┘     │ └─────────────────┘       └─────────────────┘
                        │           │
┌─────────────────┐     │           ▼                ┌─────────────────┐
│Market Collector │─────┤ ┌─────────────────┐       │   Monitoring    │
└─────────────────┘     │ │ PostgreSQL AI   │◀──────│   (Grafana)     │
                        │ └─────────────────┘       └─────────────────┘
┌─────────────────┐     │           │
│ Social Collect  │─────┘           ▼                ┌─────────────────┐
└─────────────────┘       ┌─────────────────┐       │ Celery Workers  │
                          │    InfluxDB     │──────▶│    (Flower)     │
                          └─────────────────┘       └─────────────────┘
```

## Kontribusi dan Pengembangan

Untuk kontribusi pada backend Zahaam:

1. Fork repository
2. Buat branch fitur: `git checkout -b feature/nama-fitur`
3. Commit perubahan: `git commit -m 'Tambah fitur baru'`
4. Push ke branch: `git push origin feature/nama-fitur`
5. Buat Pull Request

## Lisensi

Zahaam Backend dilisensikan di bawah MIT License.
