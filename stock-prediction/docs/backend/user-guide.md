# Panduan Penggunaan Zahaam Backend

Dokumen ini menjelaskan cara menggunakan sistem backend Zahaam untuk berbagai kasus penggunaan.

## Akses API

### Authentication

Semua endpoint API yang memerlukan autentikasi menggunakan JWT-based authentication.

#### Login dan Mendapatkan Token

```bash
curl -X POST http://localhost:8000/api/users/login \
  -H "Content-Type: application/json" \
  -d '{"username": "user@example.com", "password": "secure_password"}'
```

Response:
```json
{
  "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": {
    "id": "user-id",
    "email": "user@example.com",
    "full_name": "User Name",
    "is_active": true
  }
}
```

#### Menggunakan Token untuk Request

```bash
curl -X GET http://localhost:8000/api/users/me \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

## Penggunaan API Prediksi Saham

### Mendapatkan Prediksi Harga

```bash
curl -X GET http://localhost:8001/api/prediction/AAPL?days=7 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

Response:
```json
{
  "ticker": "AAPL",
  "prediction_date": "2023-06-10T21:00:00Z",
  "prices": [
    {
      "date": "2023-06-11",
      "price": 182.45,
      "confidence": 0.87
    },
    ...
  ]
}
```

### Mendapatkan Data Sentimen

```bash
curl -X GET http://localhost:8001/api/sentiment/AAPL \
  -H "Authorization: Bearer YOUR_TOKEN"
```

Response:
```json
{
  "ticker": "AAPL",
  "analysis_date": "2023-06-10T21:00:00Z",
  "overall_sentiment": 0.65,
  "news_sentiment": 0.71,
  "social_sentiment": 0.58,
  "sources": {
    "news_count": 42,
    "twitter_count": 156,
    "reddit_count": 87
  }
}
```

## Penggunaan Data Berita dan Media Sosial

### Mendapatkan Artikel Berita Terkini

```bash
curl -X GET http://localhost:8001/api/news/AAPL?limit=5 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

Response:
```json
{
  "articles": [
    {
      "id": "article-id",
      "title": "Apple Announces New iPhone Features",
      "source": "Tech News",
      "published_at": "2023-06-10T14:30:00Z",
      "url": "https://example.com/article",
      "sentiment_score": 0.75
    },
    ...
  ]
}
```

### Mendapatkan Post Media Sosial Terkini

```bash
curl -X GET http://localhost:8001/api/social/AAPL?platform=twitter&limit=5 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

Response:
```json
{
  "posts": [
    {
      "id": "post-id",
      "platform": "twitter",
      "author": "user123",
      "content": "Just bought some $AAPL shares! Very optimistic about Q3 earnings.",
      "published_at": "2023-06-10T19:45:00Z",
      "sentiment_score": 0.82
    },
    ...
  ]
}
```

## Penggunaan API Admin

### Mendapatkan Statistik Sistem

```bash
curl -X GET http://localhost:8002/api/admin/stats \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

Response:
```json
{
  "users": {
    "total": 1250,
    "active": 980,
    "new_last_7_days": 75
  },
  "system": {
    "api_requests_today": 45621,
    "prediction_requests_today": 12450,
    "avg_response_time_ms": 120
  },
  "data_collection": {
    "market_data_status": "healthy",
    "news_data_status": "healthy",
    "twitter_data_status": "warning",
    "reddit_data_status": "healthy"
  }
}
```

### Mengelola Data Collector

```bash
curl -X PUT http://localhost:8002/api/admin/collectors/settings \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "newsapi_collection_interval": 30,
    "twitter_collection_interval": 15,
    "enable_reddit_collector": true
  }'
```

## Monitoring dan Metrics

### Mengakses Dashboard

Akses dashboard monitoring melalui Grafana di:

```
http://localhost:3000
```

Login dengan default credentials:
- Username: admin
- Password: admin

### Dashboard Utama

1. **System Dashboard**:
   Menampilkan status sistem, penggunaan resources, dan request metrics.

2. **Data Collection Dashboard**:
   Menampilkan status dan metrics collector data.

3. **ML Performance Dashboard**:
   Menampilkan accuracy dan performa model ML.

## Troubleshooting

### Memeriksa Status Service

```bash
curl -X GET http://localhost:8001/api/health \
  -H "Authorization: Bearer YOUR_TOKEN"
```

Response:
```json
{
  "status": "healthy",
  "services": {
    "database": "connected",
    "redis": "connected",
    "celery": "running",
    "market_data": "collecting"
  },
  "uptime": "5d 12h 30m"
}
```

### Memeriksa Logs

Melihat logs dari service tertentu:

```bash
docker-compose logs -f user_api
docker-compose logs -f news_data_collector
```

### API Response Errors

Jika API mengembalikan error, format errornya konsisten:

```json
{
  "error": {
    "code": "validation_error",
    "message": "Invalid ticker symbol format",
    "details": {
      "field": "ticker",
      "reason": "Must be uppercase and 1-5 characters"
    }
  }
}
```

## Contoh Kasus Penggunaan

### Kasus 1: Analisa Saham Baru

1. Tambahkan saham ke watchlist:
   ```bash
   curl -X POST http://localhost:8000/api/users/watchlist \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"ticker": "TSLA"}'
   ```

2. Dapatkan prediksi awal:
   ```bash
   curl -X GET http://localhost:8001/api/prediction/TSLA?days=30 \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

3. Dapatkan sentimen pasar:
   ```bash
   curl -X GET http://localhost:8001/api/sentiment/TSLA \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

4. Set notifikasi untuk perubahan sentimen:
   ```bash
   curl -X POST http://localhost:8000/api/users/alerts \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "ticker": "TSLA", 
       "conditions": {
         "sentiment_change": 0.2, 
         "price_change_percent": 5
       }
     }'
   ```

### Kasus 2: Monitoring Portfolio

1. Dapatkan overview portfolio:
   ```bash
   curl -X GET http://localhost:8000/api/users/portfolio \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

2. Dapatkan prediksi untuk semua saham portfolio:
   ```bash
   curl -X GET http://localhost:8001/api/prediction/portfolio \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

3. Dapatkan rekomendasi aksi:
   ```bash
   curl -X GET http://localhost:8001/api/recommendation/portfolio \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

## Kesimpulan

Backend Zahaam menyediakan API yang komprehensif untuk:
- Data pasar dan prediksi harga saham
- Analisis sentimen dari berita dan media sosial
- Monitoring portfolio dan notifikasi
- Administrasi sistem dan monitoring performa

Semua endpoint dapat diakses dengan autentikasi yang tepat melalui token JWT.
