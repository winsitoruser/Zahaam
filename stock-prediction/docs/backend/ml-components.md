# Komponen Machine Learning (ML)

Dokumen ini menjelaskan komponen-komponen machine learning yang digunakan dalam sistem Zahaam.

## Arsitektur ML

Zahaam menggunakan arsitektur ML modular dengan komponen-komponen berikut:

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ Data Collection │────▶│ Feature         │────▶│ Model Training  │
│                 │     │ Engineering     │     │                 │
└─────────────────┘     └─────────────────┘     └─────────────────┘
                                                        │
                                                        ▼
                        ┌─────────────────┐     ┌─────────────────┐
                        │ Client          │◀────│ Prediction      │
                        │ Applications    │     │ Service         │
                        └─────────────────┘     └─────────────────┘
```

## Direktori ML

Struktur direktori ML dalam backend:

```
app/ml/
├── models/                    # Implementasi model ML
│   ├── __init__.py
│   ├── lstm_model.py         # LSTM untuk time series
│   ├── xgboost_model.py      # XGBoost untuk prediksi
│   └── ensemble.py           # Model ensemble
├── features/                  # Feature engineering
│   ├── __init__.py
│   ├── technical.py          # Technical indicators
│   ├── sentiment.py          # Sentiment features
│   └── market.py             # Market features
├── training/                  # Training pipelines
│   ├── __init__.py
│   ├── trainer.py            # Model trainer
│   └── validation.py         # Validasi model
├── prediction/                # Prediction pipelines
│   ├── __init__.py
│   ├── predictor.py          # Stock predictor
│   └── backtester.py         # Backtester
└── utils/                     # ML utilities
    ├── __init__.py
    ├── data_utils.py         # Utilitas data
    └── model_registry.py     # Model registry
```

## Model-Model ML

### 1. LSTM Model (`app/ml/models/lstm_model.py`)

Model LSTM untuk prediksi time series harga saham.

**Fitur Utama:**
- Sequence modeling untuk time series
- Multi-layer LSTM dengan dropout
- Konfigurasi window size dan sequence length
- Normalization dan preprocessing terintegrasi

**Contoh Konfigurasi:**
```python
config = {
    "sequence_length": 30,
    "batch_size": 64,
    "hidden_units": [128, 64],
    "dropout": 0.2,
    "learning_rate": 0.001
}
```

### 2. XGBoost Model (`app/ml/models/xgboost_model.py`)

Model XGBoost untuk prediksi berbasis feature.

**Fitur Utama:**
- Feature importance analysis
- Hyperparameter tuning terintegrasi
- Support untuk multiple prediction horizons
- Handling missing data

**Contoh Konfigurasi:**
```python
config = {
    "max_depth": 6,
    "learning_rate": 0.05,
    "n_estimators": 200,
    "objective": "reg:squarederror",
    "subsample": 0.8,
    "colsample_bytree": 0.8
}
```

### 3. Ensemble Model (`app/ml/models/ensemble.py`)

Model ensemble yang menggabungkan prediksi dari beberapa model.

**Fitur Utama:**
- Weighted averaging
- Dynamic weight adjustment
- Model selection berdasarkan recent performance
- Confidence interval calculation

### 4. Sentiment Analysis Models (`app/news_data/sentiment_analysis.py`)

Model analisis sentimen untuk berita dan media sosial.

**Model yang Digunakan:**
- VADER: Lexicon-based sentiment analysis
- FinBERT: BERT fine-tuned untuk teks finansial

## Feature Engineering

### Technical Indicators (`app/ml/features/technical.py`)

Indikator teknikal sebagai features untuk ML.

**Indikator yang Digunakan:**
- Moving Averages (SMA, EMA, WMA)
- Momentum indicators (RSI, MACD, ROC)
- Volatility indicators (Bollinger Bands, ATR)
- Volume indicators (OBV, VWAP)

### Sentiment Features (`app/ml/features/sentiment.py`)

Features sentimen dari analisis berita dan media sosial.

**Features yang Diekstrak:**
- Sentiment score (positive/negative)
- Sentiment volume dan momentum
- Entity sentiment (per company/sector)
- Topic analysis dan clustering

### Market Features (`app/ml/features/market.py`)

Features pasar dan makroekonomi.

**Features yang Digunakan:**
- Market index data
- Sector performance
- Economic indicators
- Seasonality dan calendar effects

## Model Training

### Model Trainer (`app/ml/training/trainer.py`)

Komponen untuk melatih dan saving model.

**Fitur Utama:**
- Training loop dengan early stopping
- Cross-validation
- Hyperparameter optimization
- Model serialization dan versioning

### Validation Framework (`app/ml/training/validation.py`)

Framework untuk validasi model ML.

**Metrics yang Digunakan:**
- RMSE (Root Mean Squared Error)
- MAPE (Mean Absolute Percentage Error)
- Direction Accuracy
- Profit/Loss pada simulasi trading

## Prediction Service

### Stock Predictor (`app/ml/prediction/predictor.py`)

Service untuk menghasilkan prediksi saham.

**Fitur Utama:**
- Multi-horizon prediction (1d, 3d, 7d, 30d)
- Prediction dengan confidence intervals
- Support untuk multiple models
- Caching dan optimasi performa

**Contoh Output:**
```json
{
  "ticker": "AAPL",
  "timestamp": "2023-06-10T15:30:00Z",
  "predictions": [
    {
      "date": "2023-06-11",
      "price": 182.45,
      "lower_bound": 178.92,
      "upper_bound": 186.21,
      "confidence": 0.85
    },
    ...
  ],
  "model_info": {
    "model_type": "ensemble",
    "version": "1.2.3",
    "last_trained": "2023-06-09T12:00:00Z",
    "features_used": 24
  }
}
```

### Backtester (`app/ml/prediction/backtester.py`)

Framework untuk backtesting model pada data historis.

**Fitur Utama:**
- Walk-forward testing
- Multiple time periods testing
- Performance metrics calculation
- Comparison dengan baseline

## ML Utilities

### Data Utilities (`app/ml/utils/data_utils.py`)

Utility functions untuk data preprocessing.

**Fungsi Utama:**
- Data normalization
- Time series resampling
- Handling missing data
- Train/test/validation splitting

### Model Registry (`app/ml/utils/model_registry.py`)

Registry untuk mengelola model ML.

**Fitur Utama:**
- Model versioning
- Metadata storage
- Model loading dan switching
- Performance tracking

## Integrations

### Celery Tasks for ML (`app/tasks/ml_tasks.py`)

Celery tasks untuk operasi ML asynchronous.

**Tasks Utama:**
- Model training
- Feature generation
- Batch prediction
- Model evaluation

### API Endpoints for ML (`app/api/ml_prediction.py`)

API endpoints untuk layanan ML.

**Endpoints Utama:**
- GET /api/prediction/{ticker}: Prediksi harga saham
- GET /api/backtesting/{ticker}: Backtest strategy
- GET /api/model/metrics: Model performance metrics

## ML Pipeline Flow

### Training Pipeline

```
Data Collection -> Feature Engineering -> Model Training -> 
Model Validation -> Model Registration -> Deployment
```

### Prediction Pipeline

```
Request -> Feature Generation -> Model Selection -> 
Prediction -> Post-processing -> Response
```

## Continuous Learning

Sistem menggunakan continuous learning dengan:

1. **Scheduled Retraining**
   - Retrain models dengan data baru secara berkala
   - Update feature importance dan weights

2. **Model Evaluation**
   - Monitor prediction accuracy
   - Compare model performance
   - Detect concept drift

3. **Model Selection**
   - Dynamically select best model untuk specific conditions
   - A/B testing untuk model variants

## Technology Stack

- **Data Processing**: NumPy, Pandas
- **ML Frameworks**: TensorFlow, PyTorch, XGBoost
- **Time Series**: Prophet, statsmodels
- **NLP**: NLTK, Transformers, spaCy
- **Visualization**: Matplotlib, Plotly
- **Deployment**: FastAPI, Celery

## Model Deployment

Models di-deploy sebagai:
1. **Serialized Models**: Untuk batch predictions
2. **REST API**: Untuk real-time predictions
3. **Embedded Models**: Untuk light-weight predictions
