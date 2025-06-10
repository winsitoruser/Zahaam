# Router Modules

Dokumen ini menjelaskan cara kerja dan alur implementasi dari modul-modul router di backend Zahaam. Router modul mengatur routing HTTP request ke handler yang sesuai.

## Struktur Router

```
app/api/
├── admin_api.py        # Routing untuk admin API
├── ai_lab.py           # Routing untuk eksperimen AI/ML
├── backtesting.py      # Routing untuk backtesting strategi trading
├── dashboard.py        # Routing untuk data dashboard
├── ml_prediction.py    # Routing untuk prediksi machine learning
├── notifications.py    # Routing untuk sistem notifikasi
├── portfolio.py        # Routing untuk manajemen portfolio
├── stock_search.py     # Routing untuk pencarian saham
├── strategy.py         # Routing untuk strategi trading
├── technical_analysis.py # Routing untuk analisis teknikal
├── tasks_api.py        # Routing untuk manajemen background tasks
└── user_api.py         # Routing untuk manajemen pengguna
```

## Implementasi Router dengan FastAPI

Semua router menggunakan FastAPI Router untuk mengatur endpoint API. Berikut adalah pola implementasi standar yang digunakan dalam semua modul router:

```python
from fastapi import APIRouter, Depends, HTTPException, status
from typing import List, Optional

from app.core.auth import get_current_user
from app.models.user import User
from app.schemas import response_models

router = APIRouter(
    prefix="/api/module-name",
    tags=["module-name"],
    responses={404: {"description": "Not found"}}
)

@router.get("/", response_model=response_models.ResponseModel)
async def get_items(
    current_user: User = Depends(get_current_user),
    param1: str = None,
    param2: int = 0
):
    """
    Dokumentasi endpoint untuk mengambil data.
    """
    # Implementasi logika
    return {"items": [...]}
```

## Router Flow

### Flow Request/Response Standar

```
Client Request
     │
     ▼
FastAPI Router (app/api/module_name.py)
     │
     ▼
Endpoint Function (@router.method)
     │
     ├─────► Authentication/Authorization (Depends)
     │            │
     │            ▼
     │       JWT Validation
     │
     ├─────► Request Validation (Pydantic Model)
     │
     ├─────► Business Logic
     │            │
     │            ├─────► Database Access
     │            │
     │            ├─────► External API Calls
     │            │
     │            └─────► Data Processing
     │
     ▼
Response (Pydantic Model)
     │
     ▼
Client
```

## Detail Implementasi Modul Router

### 1. User API (`user_api.py`)

Router untuk autentikasi dan manajemen pengguna.

```python
@router.post("/register", response_model=schemas.UserResponse)
async def register_user(user_data: schemas.UserCreate, db: Session = Depends(get_db)):
    """
    Register a new user.
    """
    user = await crud.get_user_by_email(db, user_data.email)
    if user:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Email already registered"
        )
    return await crud.create_user(db, user_data)
```

**Flow Registrasi Pengguna:**
1. Client mengirim data pendaftaran
2. FastAPI memvalidasi format data dengan Pydantic schema
3. Router memeriksa apakah email sudah terdaftar
4. User dibuat di database
5. Respons dikembalikan ke client

### 2. ML Prediction API (`ml_prediction.py`)

Router untuk prediksi machine learning dan analisis saham.

```python
@router.get("/{ticker}", response_model=schemas.PredictionResponse)
async def get_prediction(
    ticker: str, 
    days: int = 7, 
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """
    Get stock price prediction for specified ticker.
    """
    try:
        prediction = await prediction_service.predict_stock_price(ticker, days)
        # Log prediction request
        await crud.log_prediction_request(db, current_user.id, ticker, days)
        return prediction
    except Exception as e:
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail=f"Prediction error: {str(e)}"
        )
```

**Flow Prediksi Saham:**
1. Client mengirim request dengan ticker dan parameter
2. FastAPI memvalidasi parameter dan autentikasi pengguna
3. Service prediksi dipanggil untuk membuat prediksi
4. Request prediksi dicatat ke database
5. Hasil prediksi dikembalikan ke client

### 3. Notifications API (`notifications.py`)

Router untuk manajemen notifikasi pengguna.

```python
@router.get("/", response_model=schemas.NotificationList)
async def get_notifications(
    read: Optional[bool] = None,
    type: Optional[str] = None,
    skip: int = 0,
    limit: int = 100,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """
    Get notifications for the current user.
    """
    notifications = await crud.get_user_notifications(
        db, 
        user_id=current_user.id,
        read=read,
        notification_type=type,
        skip=skip, 
        limit=limit
    )
    
    total_count = await crud.count_user_notifications(db, current_user.id, read=None)
    unread_count = await crud.count_user_notifications(db, current_user.id, read=False)
    
    return {
        "notifications": notifications,
        "total_count": total_count,
        "unread_count": unread_count
    }
```

**Flow Notifikasi:**
1. Client meminta daftar notifikasi dengan filter opsional
2. Autentikasi pengguna divalidasi
3. Notifikasi diambil dari database berdasarkan filter
4. Jumlah total dan belum dibaca dihitung
5. Hasil dikembalikan ke client

### 4. Technical Analysis API (`technical_analysis.py`)

Router untuk analisis teknikal saham.

```python
@router.get("/signal/{ticker}", response_model=schemas.TechnicalSignal)
async def get_technical_signals(
    ticker: str,
    period: str = "1y",
    interval: str = "1d",
    current_user: User = Depends(get_current_user)
):
    """
    Get technical analysis signals for a stock.
    """
    try:
        signals = await technical_service.get_signals(ticker, period, interval)
        return signals
    except ValueError as e:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=str(e)
        )
    except Exception as e:
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail=f"Technical analysis error: {str(e)}"
        )
```

**Flow Analisis Teknikal:**
1. Client meminta sinyal teknikal untuk saham tertentu
2. Autentikasi pengguna divalidasi
3. Service analisis teknikal menghitung indikator dan sinyal
4. Hasil analisis dikembalikan ke client

### 5. Admin API (`admin_api.py`)

Router untuk administrasi sistem.

```python
@router.get("/stats", response_model=schemas.SystemStats)
async def get_system_stats(
    current_user: User = Depends(get_admin_user),
    db: Session = Depends(get_db)
):
    """
    Get system statistics. Admin only.
    """
    user_stats = await admin_service.get_user_stats(db)
    system_stats = await admin_service.get_system_status()
    data_stats = await admin_service.get_data_collection_stats()
    
    return {
        "users": user_stats,
        "system": system_stats,
        "data_collection": data_stats
    }
```

**Flow Admin API:**
1. Client meminta statistik sistem
2. Admin privileges divalidasi (bukan hanya autentikasi biasa)
3. Service admin mengumpulkan berbagai statistik dari sistem
4. Data statistik dikembalikan ke client

## Dependency Injection Pattern

Router menggunakan dependency injection pattern dari FastAPI untuk:

1. **Database Sessions**
```python
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
```

2. **User Authentication**
```python
def get_current_user(
    db: Session = Depends(get_db),
    token: str = Depends(oauth2_scheme)
) -> User:
    # JWT validation logic
    return user
```

3. **Admin Authorization**
```python
def get_admin_user(
    current_user: User = Depends(get_current_user),
) -> User:
    if not current_user.is_admin:
        raise HTTPException(status_code=403, detail="Not authorized")
    return current_user
```

## API Response Handling

Router konsisten dalam menangani respons:

1. **Success Response**
   - HTTP Status 200/201/204
   - Data yang divalidasi dengan Pydantic model
   - Format struktur respons konsisten

2. **Error Handling**
   - HTTP Status 4xx/5xx yang sesuai
   - Detail error yang informatif
   - Format error yang konsisten

```python
# Format error response konsisten
{
    "error": {
        "code": "error_type",
        "message": "Detailed error message",
        "details": { ... } # Optional
    }
}
```

## Router Request Limits

Router dilengkapi dengan rate limiting untuk mencegah abuse:

```python
@router.get("/resource")
@limiter.limit("10/minute")
async def get_resource(request: Request):
    # Implementation
```

## CORS Configuration

Router dikonfigurasi dengan CORS untuk memfasilitasi akses dari frontend:

```python
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:3000"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
```

## Testing Router

Tiap router memiliki test yang mencakup:

1. **Unit Test** untuk fungsi individual
2. **Integration Test** untuk endpoint lengkap
3. **Authentication Test** untuk memverifikasi keamanan endpoint

```python
def test_get_notifications(client, test_user_token):
    response = client.get(
        "/api/notifications/",
        headers={"Authorization": f"Bearer {test_user_token}"}
    )
    assert response.status_code == 200
    data = response.json()
    assert "notifications" in data
    assert "total_count" in data
    assert "unread_count" in data
```
