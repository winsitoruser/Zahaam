# Monitoring dan Observability

Dokumen ini menjelaskan sistem monitoring dan observability yang digunakan dalam backend Zahaam.

## Arsitektur Monitoring

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ Aplikasi        │────▶│ Metrics         │────▶│ Grafana         │
│ (Instrumentasi) │     │ (InfluxDB)      │     │ (Dashboards)    │
└─────────────────┘     └─────────────────┘     └─────────────────┘
        │                       │                      │
        ▼                       ▼                      ▼
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ Logs            │────▶│ Log Aggregator  │────▶│ Log Explorer    │
│ (Application)   │     │                 │     │                 │
└─────────────────┘     └─────────────────┘     └─────────────────┘
        │
        ▼
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ Celery Tasks    │────▶│ Flower          │────▶│ Task Monitor    │
│                 │     │                 │     │                 │
└─────────────────┘     └─────────────────┘     └─────────────────┘
```

## Komponen Monitoring

### 1. Metrics Collection

Sistem Zahaam menggunakan InfluxDB untuk menyimpan time series metrics dan Telegraf untuk collection.

**Metrik yang Dicoleksi:**

- **System Metrics:**
  - CPU usage
  - Memory usage
  - Disk I/O
  - Network traffic

- **Application Metrics:**
  - Request rate
  - Response time
  - Error rate
  - Endpoint usage

- **Business Metrics:**
  - Jumlah prediksi
  - Akurasi model
  - Jumlah berita yang diproses
  - Jumlah data pasar yang dicoleksi

**Contoh Konfigurasi InfluxDB:**

```yaml
# /backend/grafana/provisioning/datasources/influxdb.yaml
apiVersion: 1

datasources:
  - name: InfluxDB
    type: influxdb
    access: proxy
    url: http://influxdb:8086
    jsonData:
      defaultBucket: market_data
      organization: zahaam
      version: Flux
    secureJsonData:
      token: ${INFLUXDB_TOKEN}
```

### 2. Grafana Dashboards

Grafana digunakan untuk visualisasi metrics dan alerting.

**Dashboard Utama:**

1. **System Overview:**
   - Health status semua services
   - Resource utilization
   - Error rates

2. **API Performance:**
   - Request rates
   - Response times
   - Error rates per endpoint
   - Slow queries

3. **Data Collection:**
   - Collection rates
   - Success rates
   - Processing latency
   - Data freshness

4. **ML Performance:**
   - Model accuracy
   - Prediction latency
   - Feature importance
   - Drift detection

**Akses Dashboard:**

```
http://localhost:3000
```

Default credentials:
- Username: admin
- Password: admin

### 3. Celery Monitoring dengan Flower

Flower digunakan untuk memonitor Celery tasks.

**Fitur Utama:**
- Real-time monitoring tasks
- Task history dan statistics
- Worker status dan management
- Task inspection

**Akses Flower:**

```
http://localhost:5555
```

**Contoh Command untuk Inspect Task:**

```bash
curl http://localhost:5555/api/task/info/task-id
```

### 4. Logging System

Sistem logging terstruktur menggunakan Python logging dan aggregation.

**Level Logging:**
- DEBUG: Informasi detail untuk debugging
- INFO: Informasi umum tentang operasi normal
- WARNING: Potensi masalah yang perlu diperhatikan
- ERROR: Error yang tidak mengganggu aplikasi
- CRITICAL: Error kritis yang menyebabkan application failure

**Format Log:**

```json
{
  "timestamp": "2023-06-10T21:34:12.345Z",
  "level": "INFO",
  "service": "news_collector",
  "message": "Successfully collected 42 articles",
  "details": {
    "sources": ["NewsAPI", "BisnisComScraper"],
    "processing_time_ms": 1254
  },
  "request_id": "req-123456"
}
```

**Contoh Konfigurasi Logging:**

```python
# app/core/logging.py
import logging
import json
import datetime

class JSONFormatter(logging.Formatter):
    def format(self, record):
        log_record = {
            "timestamp": datetime.datetime.utcnow().isoformat(),
            "level": record.levelname,
            "service": record.name,
            "message": record.getMessage(),
            "request_id": getattr(record, "request_id", None)
        }
        
        if hasattr(record, "details") and record.details:
            log_record["details"] = record.details
            
        return json.dumps(log_record)
```

### 5. Health Checks

Endpoint health check untuk monitoring service availability.

**Endpoint:**

```
GET /api/health
```

**Response:**

```json
{
  "status": "healthy",
  "version": "1.2.3",
  "timestamp": "2023-06-10T21:35:00Z",
  "services": {
    "database": {
      "status": "connected",
      "latency_ms": 5
    },
    "redis": {
      "status": "connected",
      "latency_ms": 2
    },
    "market_data_collector": {
      "status": "running",
      "last_collection": "2023-06-10T21:30:00Z"
    },
    "news_collector": {
      "status": "running",
      "last_collection": "2023-06-10T21:32:00Z" 
    }
  }
}
```

## Alerting

Sistem alerting untuk mendeteksi masalah dan mengirim notifikasi.

### Grafana Alerts

**Alert Rules:**
1. **High Error Rate:**
   - Condition: Error rate > 5% dalam 5 menit
   - Severity: Critical
   - Notification: Email, Slack

2. **Slow API Response:**
   - Condition: P95 response time > 500ms dalam 5 menit
   - Severity: Warning
   - Notification: Slack

3. **Data Collection Failure:**
   - Condition: Collection success rate < 90% dalam 15 menit
   - Severity: Critical
   - Notification: Email, Slack

4. **High Resource Usage:**
   - Condition: CPU usage > 80% atau Memory > 85% dalam 10 menit
   - Severity: Warning
   - Notification: Slack

### Notification Channels

Grafana dikonfigurasi untuk mengirim notifikasi melalui:
- Email
- Slack
- Webhook ke sistem notifikasi internal

**Contoh Konfigurasi Slack:**

```yaml
# /backend/grafana/provisioning/notifiers/slack.yaml
apiVersion: 1

notifiers:
  - name: Slack
    type: slack
    uid: slack-notifications
    org_id: 1
    is_default: true
    settings:
      url: ${SLACK_WEBHOOK_URL}
      recipient: "#alerts"
```

## Metrik Bisnis Kunci

### Data Collection Metrics

- **Collection Rates:**
  - `market_data.collection.rate`: Market data points per minute
  - `news_data.collection.rate`: News articles per hour
  - `social_data.collection.rate`: Social posts per hour

- **Latency Metrics:**
  - `processing.latency.news`: Waktu untuk memproses berita (ms)
  - `processing.latency.sentiment`: Waktu untuk analisis sentimen (ms)
  - `processing.latency.prediction`: Waktu untuk prediksi harga (ms)

### User Engagement Metrics

- `user.requests.predictions`: Jumlah request prediksi
- `user.requests.sentiment`: Jumlah request sentimen
- `user.active_daily`: Pengguna aktif harian
- `user.new_registrations`: Pendaftaran baru

### Model Performance Metrics

- `model.accuracy.rmse`: Root Mean Squared Error
- `model.accuracy.direction`: Direction accuracy (%)
- `model.drift.feature`: Feature drift score
- `model.drift.prediction`: Prediction drift score

## Capacity Planning

Metrik digunakan untuk kapasitas planning:

1. **Database Growth:**
   - `database.size.postgres_ai`: Ukuran PostgreSQL AI database
   - `database.size.postgres_user`: Ukuran PostgreSQL User database
   - `database.size.influxdb`: Ukuran InfluxDB

2. **Processing Requirements:**
   - `celery.queue.length`: Panjang queue Celery
   - `celery.task.processing_time`: Waktu pemrosesan task

3. **Storage Forecasting:**
   - `storage.forecast.days_remaining`: Estimasi hari sebelum storage penuh
   - `storage.growth.rate_per_day`: Pertumbuhan storage per hari

## Dashboard Templates

Berikut adalah template dashboard yang tersedia:

### 1. System Overview Dashboard

![System Dashboard](placeholder-image-system-dashboard.png)

**Panels:**
- Service Health Status
- API Request Rate
- Error Rate
- System Resources
- Database Connections

### 2. Data Collection Dashboard

![Data Collection Dashboard](placeholder-image-data-dashboard.png)

**Panels:**
- Collection Success Rate
- Data Freshness
- Processing Queue
- API Rate Limits
- Collection Timing

### 3. ML Performance Dashboard

![ML Dashboard](placeholder-image-ml-dashboard.png)

**Panels:**
- Prediction Accuracy
- Model Drift
- Feature Importance
- Training Performance
- Inference Latency

## Praktik Terbaik Monitoring

1. **Service Level Indicators (SLIs):**
   - Availability: 99.9% uptime
   - Latency: P95 < 300ms
   - Error Rate: < 0.1%

2. **Correlation IDs:**
   Setiap request diberi unique correlation ID yang diteruskan ke semua services untuk tracing.

3. **Log Levels:**
   Gunakan log levels yang tepat:
   - `DEBUG` untuk informasi detailed developer
   - `INFO` untuk operasi normal
   - `WARNING` untuk potensi masalah
   - `ERROR` untuk masalah yang memerlukan intervensi

4. **Structured Logging:**
   Gunakan JSON formatted logs untuk memudahkan parsing dan analisis.

## Troubleshooting dengan Monitoring

### Contoh Kasus: Slow API Responses

1. **Deteksi:**
   - Dashboard Grafana menunjukkan peningkatan response time
   - Alert triggered untuk "Slow API Response"

2. **Diagnosis:**
   1. Cek CPU dan memory usage di dashboard System
   2. Cek database connection pool di dashboard Database
   3. Cek request rate di dashboard API
   4. Review slow queries di log database

3. **Resolusi:**
   - Jika database overloaded: scale database resources
   - Jika API service overloaded: scale API instances
   - Jika query inefficient: optimize query dan index

### Contoh Kasus: Data Collection Failure

1. **Deteksi:**
   - Alert triggered untuk "Data Collection Failure"
   - Dashboard menunjukkan data gap

2. **Diagnosis:**
   1. Cek logs di collector service
   2. Verifikasi API status eksternal
   3. Cek rate limits dan quotas

3. **Resolusi:**
   - Jika API eksternal down: switch ke alternate source
   - Jika rate limited: adjust collection frequency
   - Jika error parsing: update parser logic
