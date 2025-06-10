# Deployment dan Penggunaan

## Persiapan Environment

Sebelum deployment, pastikan anda memiliki dependensi berikut:

- Docker dan Docker Compose (versi terbaru)
- Git
- Python 3.8+ (untuk pengembangan lokal)
- API Key untuk layanan eksternal (NewsAPI, Twitter API)

## Langkah Deployment

### 1. Clone Repository

```bash
git clone https://github.com/winsitoruser/Zahaam.git
cd Zahaam/stock-prediction
```

### 2. Konfigurasi Environment Variables

Buat file `.env` di direktori `backend/`:

```bash
# Database
POSTGRES_AI_USER=postgres
POSTGRES_AI_PASSWORD=your_secure_password
POSTGRES_AI_DB=postgres_ai
POSTGRES_AI_URL=postgresql://postgres:your_secure_password@postgres_ai:5432/postgres_ai

POSTGRES_USER_USER=postgres
POSTGRES_USER_PASSWORD=your_secure_password
POSTGRES_USER_DB=postgres_user
POSTGRES_USER_URL=postgresql://postgres:your_secure_password@postgres_user:5432/postgres_user

# Redis
REDIS_URL=redis://redis:6379/0

# InfluxDB
INFLUXDB_URL=http://influxdb:8086
INFLUXDB_TOKEN=your_influxdb_token
INFLUXDB_ORG=zahaam
INFLUXDB_BUCKET=market_data

# API Keys
NEWSAPI_KEY=your_newsapi_key
TWITTER_BEARER_TOKEN=your_twitter_bearer_token
TWITTER_API_KEY=your_twitter_api_key
TWITTER_API_SECRET=your_twitter_api_secret

# Collector Settings
NEWSAPI_COLLECTION_INTERVAL=60
TWITTER_COLLECTION_INTERVAL=15
ENABLE_NEWS_COLLECTOR=true
ENABLE_TWITTER_COLLECTOR=true

# Security
SECRET_KEY=your_secret_key_at_least_32_characters_long
ALGORITHM=HS256
ACCESS_TOKEN_EXPIRE_MINUTES=60

# Sentiment Analysis
SENTIMENT_MODEL_TYPE=vader  # atau finbert
```

### 3. Build dan Run dengan Docker Compose

```bash
cd backend
docker-compose up -d
```

Ini akan men-deploy service berikut:
- API services (User API, AI Prediction API, Admin API)
- Database services (PostgreSQL AI, PostgreSQL User, InfluxDB)
- Middleware (Redis, RabbitMQ)
- Data collectors (Market Data, News Data, Social Media)
- Monitoring (Grafana, Flower)

### 4. Verify Deployment

Pastikan semua service berjalan:

```bash
docker-compose ps
```

Check logs untuk service tertentu:

```bash
docker-compose logs -f user_api
docker-compose logs -f news_data_collector
```

### 5. Akses API dan Service

- User API: http://localhost:8000
- AI Prediction API: http://localhost:8001
- Admin API: http://localhost:8002
- Grafana Dashboard: http://localhost:3000 (username: admin, password: admin)
- Flower (Celery Monitoring): http://localhost:5555

## Penggunaan Sistem

### 1. Registrasi dan Login

Registrasi pengguna baru:

```bash
curl -X POST http://localhost:8000/api/users/register \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "secure_password", "full_name": "Example User"}'
```

Login dan dapatkan token:

```bash
curl -X POST http://localhost:8000/api/users/login \
  -H "Content-Type: application/json" \
  -d '{"username": "user@example.com", "password": "secure_password"}'
```

Copy access token dari response untuk digunakan pada API calls selanjutnya.

### 2. Mendapatkan Data Sentimen

Mendapatkan sentimen untuk saham tertentu:

```bash
curl -X GET http://localhost:8001/api/sentiment/AAPL \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

Mendapatkan sentimen pasar keseluruhan:

```bash
curl -X GET http://localhost:8001/api/sentiment/market \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### 3. Mendapatkan Prediksi Saham

```bash
curl -X GET http://localhost:8001/api/prediction/AAPL?days=7 \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### 4. Mengubah Pengaturan Data Collector

Mengganti interval pengumpulan data (memerlukan admin access):

```bash
curl -X PUT http://localhost:8002/api/admin/collectors/settings \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"newsapi_collection_interval": 30, "twitter_collection_interval": 10}'
```

## Troubleshooting

### Database Connection Issues

Jika layanan tidak dapat terhubung ke database:

1. Periksa log container database:
   ```bash
   docker-compose logs postgres_ai
   ```

2. Verifikasi environment variables:
   ```bash
   docker-compose config
   ```

3. Coba restart container database:
   ```bash
   docker-compose restart postgres_ai
   ```

### API Key Issues

Jika kolektor data eksternal gagal:

1. Periksa log collector:
   ```bash
   docker-compose logs news_data_collector
   ```

2. Verifikasi API keys di environment variables

3. Periksa status rate limit layanan eksternal di dashboard admin

### Scaling

Untuk meningkatkan skalabilitas sistem:

```bash
# Scale jumlah worker
docker-compose up -d --scale celery_worker=3

# Scale service API
docker-compose up -d --scale user_api=2
```

## Monitoring dan Metrik

### Grafana Dashboard

Grafana menyediakan visualisasi untuk berbagai metrik:

1. **System Overview Dashboard**
   - CPU, memory, dan disk usage
   - Request rate dan latency
   - Error rate

2. **Data Collection Dashboard**
   - Collection throughput
   - Success rate
   - API rate limits

3. **Sentiment Analysis Dashboard**
   - Sentiment distribution
   - Processing time
   - Entity extraction accuracy

### Alerting

Grafana dapat dikonfigurasi untuk mengirim alert ketika:

1. Service down atau error rate tinggi
2. Collection rate di bawah threshold
3. Sentimen pasar berubah signifikan
4. Prediksi AI menunjukkan perubahan drastis

## Update dan Maintenance

### Update System

Untuk update sistem ke versi terbaru:

```bash
# Pull perubahan terbaru
git pull origin backend

# Rebuild dan restart containers
docker-compose down
docker-compose build
docker-compose up -d
```

### Database Maintenance

Backup database secara reguler:

```bash
docker-compose exec postgres_ai pg_dump -U postgres postgres_ai > backup_ai_$(date +%Y%m%d).sql
docker-compose exec postgres_user pg_dump -U postgres postgres_user > backup_user_$(date +%Y%m%d).sql
```

### Logging

Log tersedia melalui Docker:

```bash
# View logs for all services
docker-compose logs

# View logs for specific service with follow
docker-compose logs -f user_api

# Filter logs by time
docker-compose logs --since 30m user_api
```
