# API Documentation

## Overview API

Backend Zahaam menyediakan berbagai API endpoint untuk mengakses data pasar, melakukan analisis sentimen, dan mengelola data pengguna. API diimplementasikan menggunakan FastAPI dan terbagi dalam beberapa service.

## User API (Port 8000)

### Authentication

```
POST /api/users/register
```
Membuat akun pengguna baru.

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "secure_password",
  "full_name": "Example User"
}
```

**Response:**
```json
{
  "id": "uuid-string",
  "email": "user@example.com",
  "full_name": "Example User",
  "created_at": "2025-06-10T12:00:00Z"
}
```

```
POST /api/users/login
```
Login dan mendapatkan token akses.

**Request Body:**
```json
{
  "username": "user@example.com",
  "password": "secure_password"
}
```

**Response:**
```json
{
  "access_token": "jwt-token-string",
  "token_type": "bearer",
  "expires_at": "2025-06-10T16:00:00Z",
  "user_id": "uuid-string"
}
```

### User Management

```
GET /api/users/me
```
Mendapatkan informasi pengguna yang sedang login.

**Response:**
```json
{
  "id": "uuid-string",
  "email": "user@example.com",
  "full_name": "Example User",
  "preferences": {
    "notification_frequency": "daily",
    "default_ticker": "AAPL",
    "dark_mode": true
  }
}
```

```
PUT /api/users/me
```
Memperbarui informasi pengguna.

**Request Body:**
```json
{
  "full_name": "Updated User Name",
  "preferences": {
    "notification_frequency": "real-time",
    "default_ticker": "GOOGL"
  }
}
```

## AI Prediction API (Port 8001)

### Stock Prediction

```
GET /api/prediction/{ticker}
```
Mendapatkan prediksi harga saham.

**Parameters:**
- `ticker` (path): Kode saham (contoh: AAPL)
- `days` (query, optional): Jumlah hari ke depan untuk prediksi (default: 7)

**Response:**
```json
{
  "ticker": "AAPL",
  "predictions": [
    {
      "date": "2025-06-11",
      "predicted_price": 185.23,
      "confidence": 0.89,
      "upper_bound": 190.15,
      "lower_bound": 180.31
    },
    {
      "date": "2025-06-12",
      "predicted_price": 187.56,
      "confidence": 0.85,
      "upper_bound": 192.48,
      "lower_bound": 182.64
    }
  ],
  "model_info": {
    "model_type": "lstm",
    "trained_on": "2025-06-01",
    "accuracy": 0.91
  }
}
```

### Sentiment Analysis

```
GET /api/sentiment/{ticker}
```
Mendapatkan analisis sentimen untuk saham tertentu.

**Parameters:**
- `ticker` (path): Kode saham (contoh: AAPL)
- `days_back` (query, optional): Jumlah hari ke belakang untuk data sentimen (default: 7)

**Response:**
```json
{
  "ticker": "AAPL",
  "overall_sentiment": {
    "score": 0.68,
    "classification": "positive",
    "confidence": 0.83
  },
  "daily_sentiment": [
    {
      "date": "2025-06-09",
      "news_count": 23,
      "social_media_count": 158,
      "sentiment_score": 0.71,
      "classification": "positive",
      "top_entities": ["iPhone", "market share", "revenue growth"]
    },
    {
      "date": "2025-06-10",
      "news_count": 18,
      "social_media_count": 142,
      "sentiment_score": 0.65,
      "classification": "positive",
      "top_entities": ["quarterly earnings", "supply chain", "AI"]
    }
  ],
  "sources": {
    "news_sources": ["Bloomberg", "CNBC", "Reuters"],
    "social_media": ["Twitter", "Reddit"]
  }
}
```

```
GET /api/sentiment/market
```
Mendapatkan sentimen keseluruhan pasar.

**Parameters:**
- `market` (query, optional): Market untuk dianalisis (default: "US")
- `days_back` (query, optional): Jumlah hari ke belakang untuk data sentimen (default: 7)

**Response:**
```json
{
  "market": "US",
  "overall_sentiment": {
    "score": 0.52,
    "classification": "neutral",
    "confidence": 0.76
  },
  "sectors": [
    {
      "sector": "Technology",
      "sentiment_score": 0.68,
      "classification": "positive"
    },
    {
      "sector": "Financial",
      "sentiment_score": 0.44,
      "classification": "neutral"
    }
  ]
}
```

## Technical Analysis API

```
GET /api/technical/signal/{ticker}
```
Mendapatkan sinyal teknikal untuk saham tertentu.

**Parameters:**
- `ticker` (path): Kode saham (contoh: AAPL)
- `period` (query, optional): Periode data (default: "1y")
- `interval` (query, optional): Interval data (default: "1d")

**Response:**
```json
{
  "ticker": "AAPL",
  "current_price": 184.92,
  "signals": {
    "rsi": {
      "value": 58.31,
      "signal": "neutral",
      "description": "RSI is in neutral territory"
    },
    "macd": {
      "value": 1.42,
      "signal": "buy",
      "description": "MACD crossed above signal line"
    },
    "bollinger": {
      "upper": 191.35,
      "middle": 182.45,
      "lower": 173.55,
      "signal": "neutral",
      "description": "Price is within Bollinger Bands"
    },
    "moving_averages": {
      "ma50": 180.25,
      "ma200": 175.83,
      "signal": "buy",
      "description": "Price is above MA50 and MA200"
    }
  },
  "overall_signal": "buy"
}
```

## Admin API (Port 8002)

```
GET /api/admin/stats
```
Mendapatkan statistik sistem (memerlukan admin access).

**Response:**
```json
{
  "users": {
    "total": 1248,
    "active_today": 287,
    "new_this_week": 53
  },
  "system": {
    "services_status": {
      "user_api": "healthy",
      "ai_prediction": "healthy",
      "market_collector": "healthy",
      "news_collector": "warning"
    },
    "database": {
      "postgres_ai_size_mb": 1245.8,
      "postgres_user_size_mb": 387.2,
      "influxdb_size_mb": 2456.3
    }
  },
  "data_collection": {
    "market_data": {
      "stocks_total": 3287,
      "last_updated": "2025-06-10T15:30:00Z",
      "success_rate": 0.998
    },
    "news_data": {
      "articles_today": 1823,
      "sources_count": 17,
      "success_rate": 0.985
    },
    "social_media": {
      "posts_today": 15782,
      "sources_count": 2,
      "success_rate": 0.978
    }
  }
}
```

## Notification API

```
GET /api/notifications
```
Mendapatkan notifikasi untuk pengguna.

**Parameters:**
- `read` (query, optional): Filter berdasarkan status baca (true/false)
- `type` (query, optional): Filter berdasarkan jenis notifikasi (price_alert, sentiment_alert, system)

**Response:**
```json
{
  "notifications": [
    {
      "id": "uuid-string",
      "type": "price_alert",
      "ticker": "AAPL",
      "message": "AAPL price reached your target of $185",
      "created_at": "2025-06-10T14:23:11Z",
      "read": false
    },
    {
      "id": "uuid-string",
      "type": "sentiment_alert",
      "ticker": "MSFT",
      "message": "Significant negative sentiment detected for MSFT",
      "created_at": "2025-06-10T10:15:32Z",
      "read": true
    }
  ],
  "total_count": 2,
  "unread_count": 1
}
```

```
POST /api/notifications/settings
```
Memperbarui pengaturan notifikasi.

**Request Body:**
```json
{
  "price_alerts": true,
  "sentiment_alerts": true,
  "system_notifications": false,
  "frequency": "immediate",
  "channels": ["email", "push"]
}
```

## Error Responses

Semua API endpoint mengikuti format error yang konsisten:

**400 Bad Request:**
```json
{
  "error": {
    "code": "bad_request",
    "message": "Invalid parameters",
    "details": {
      "ticker": "Ticker symbol not found"
    }
  }
}
```

**401 Unauthorized:**
```json
{
  "error": {
    "code": "unauthorized",
    "message": "Authentication required"
  }
}
```

**403 Forbidden:**
```json
{
  "error": {
    "code": "forbidden",
    "message": "Insufficient permissions"
  }
}
```

**404 Not Found:**
```json
{
  "error": {
    "code": "not_found",
    "message": "Resource not found"
  }
}
```

**500 Internal Server Error:**
```json
{
  "error": {
    "code": "internal_error",
    "message": "An internal server error occurred",
    "request_id": "uuid-string"
  }
}
```

## API Authentication

Semua API endpoint (kecuali `/api/users/register` dan `/api/users/login`) memerlukan autentikasi menggunakan JWT token.

**HTTP Headers:**
```
Authorization: Bearer <jwt-token>
```

## Rate Limiting

API memiliki pembatasan rate untuk mencegah abuse:

- User API: 60 requests per menit
- AI Prediction API: 30 requests per menit
- Admin API: 120 requests per menit

Rate limit headers akan disertakan dalam respons:

```
X-Rate-Limit-Limit: 60
X-Rate-Limit-Remaining: 58
X-Rate-Limit-Reset: 1623341987
```
