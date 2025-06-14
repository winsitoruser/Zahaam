# Arsitektur Backend Zahaam

## Overview Arsitektur

Zahaam backend diimplementasikan dengan arsitektur microservice berbasis Docker yang membagi sistem menjadi komponen-komponen terpisah yang saling berkomunikasi. Arsitektur ini memungkinkan skalabilitas, pemeliharaan, dan pengembangan yang lebih mudah.

## Diagram Arsitektur

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

## Komponen Utama

### API Services

1. **User API (Port 8000)**
   - Menangani autentikasi dan registrasi pengguna
   - Manajemen profil dan preferensi
   - Pengaturan notifikasi dan alert

2. **AI Prediction API (Port 8001)**
   - Prediksi AI dan machine learning
   - Analisis sentimen pasar dan media sosial
   - Analisis teknikal dan signal generator

3. **Admin API (Port 8002)**
   - Manajemen sistem dan monitoring
   - Pengaturan dan konfigurasi sistem
   - Dashboard administratif

### Database Services

1. **PostgreSQL AI**
   - Menyimpan model AI dan prediksi
   - Data sentimen pasar dan analisis media sosial
   - History analisis teknikal dan prediksi

2. **PostgreSQL User**
   - Data pengguna dan autentikasi
   - Preferensi dan settings pengguna
   - Riwayat aktivitas pengguna

3. **InfluxDB**
   - Data time series untuk harga saham
   - Metrik real-time untuk monitoring sistem
   - Data kinerja dan throughput koleksi data

### Middleware Services

1. **Redis**
   - Caching untuk API responses
   - Session management
   - Rate limiting dan request queueing

2. **RabbitMQ**
   - Message broker untuk Celery tasks
   - Komunikasi asynchronous antar services
   - Event-driven processing

### Data Collection Services

1. **Market Data Collector**
   - Mengumpulkan data pasar dari Yahoo Finance
   - Ekstraksi harga saham historis dan real-time
   - Processing dan transformasi data pasar

2. **News Data Collector**
   - Mengumpulkan berita finansial dari NewsAPI
   - Web scraping untuk sumber berita lokal
   - Analisis sentimen berita

3. **Social Media Collector**
   - Mengumpulkan data dari Twitter/X
   - Pengambilan post dari subreddit finansial
   - Analisis sentimen media sosial

### Background Processing

1. **Celery Workers**
   - Menjalankan tugas background periodic
   - Pengumpulan data terjadwal
   - Pemrosesan data asynchronous

## Flow Komunikasi Data

### Flow Pengumpulan dan Analisis Data Berita

```
1. News Collector ───▶ NewsAPI Request
                        │
2. Data Received ◀────── Response
   │
3. Data Processing
   │
4. Sentiment Analysis ◀─── NLP Models
   │
5. Save to PostgreSQL
   │
6. Metrics to InfluxDB
   │
7. Generate Notifications (if needed)
```

### Flow Pengumpulan dan Analisis Data Media Sosial

```
1. Social Media Collector ───▶ Twitter API Request
                               │
2. Data Received ◀────────────── Response
   │
3. Data Processing
   │
4. Sentiment Analysis ◀─── NLP Models
   │
5. Save to PostgreSQL
   │
6. Metrics to InfluxDB
   │
7. Generate Notifications (if needed)
```

### Flow Prediksi Saham

```
1. User Request ───▶ FastAPI AI Prediction
   │                 │
2. Load Historical ◀─┘
   Data               │
   │                  │
3. Load Sentiment ◀───┘
   Data               │
   │                  │
4. Run ML Model ◀─────┘
   │
5. Store Result
   │
6. Return Prediction
```

## Struktur Direktori

```
backend/
├── app/
│   ├── api/                # API routes untuk berbagai layanan
│   │   ├── admin_api.py    # API untuk administrasi sistem
│   │   ├── ai_lab.py       # API untuk eksperimen AI
│   │   ├── notifications.py # API untuk notifikasi
│   │   ├── ml_prediction.py # API untuk prediksi ML
│   │   └── user_api.py     # API untuk manajemen pengguna
│   │
│   ├── core/               # Fungsi inti sistem
│   │   ├── auth.py         # Otentikasi dan otorisasi
│   │   ├── celery_app.py   # Konfigurasi Celery
│   │   ├── config.py       # Konfigurasi sistem
│   │   └── news_scraper.py # Web scraper untuk berita
│   │
│   ├── market_data/        # Modul pengumpulan data pasar
│   │   ├── __init__.py
│   │   └── collector.py    # Kolektor data pasar saham
│   │
│   ├── news_data/          # Modul pengumpulan & analisis data berita
│   │   ├── __init__.py
│   │   ├── collector.py    # Kolektor data berita
│   │   ├── config.py       # Konfigurasi pengumpulan data
│   │   ├── models.py       # Model database untuk data berita
│   │   ├── sentiment_analysis.py # Analisis sentimen
│   │   └── social_media.py # Kolektor data media sosial
│   │
│   ├── models/             # Model database
│   │   ├── ai_lab.py       # Model untuk eksperimen AI
│   │   └── user.py         # Model untuk pengguna
│   │
│   ├── tasks/              # Background tasks dengan Celery
│   │   ├── __init__.py
│   │   ├── maintenance_tasks.py # Tugas pemeliharaan
│   │   ├── ml_tasks.py     # Tugas machine learning
│   │   └── stock_tasks.py  # Tugas pengumpulan data saham
│   │
│   └── main.py             # Aplikasi FastAPI utama
```

## Keamanan dan Ketersediaan

### Keamanan

- JWT Authentication untuk API
- HTTPS untuk semua komunikasi eksternal
- Environment variables untuk credential
- Enkripsi data sensitif
- Rate limiting untuk mencegah DoS

### High Availability

- Container orchestration dengan Docker Compose
- Health checks untuk service monitoring
- Automatic failover untuk database
- Logging komprehensif
- Monitoring real-time dengan Grafana

## Skalabilitas

Arsitektur microservice Zahaam dirancang untuk skalabilitas:

- Horizontal scaling untuk API services
- Database sharding untuk data dengan volume tinggi
- Load balancing untuk distribusi traffic
- Caching untuk mengurangi beban database
- Asynchronous processing untuk tugas-tugas berat
