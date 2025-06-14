# Machine Learning Models

Dokumen ini menjelaskan model-model machine learning yang digunakan dalam sistem Zahaam untuk prediksi pasar saham dan analisis sentimen.

## Arsitektur Machine Learning

Zahaam menggunakan pendekatan hybrid yang menggabungkan beberapa model machine learning untuk mendapatkan prediksi dan analisis yang akurat:

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ Market Data     │────▶│ Preprocessing   │────▶│ Feature         │
│ (OHLCV)         │     │ Pipeline        │     │ Engineering     │
└─────────────────┘     └─────────────────┘     └─────────────────┘
                                                        │
┌─────────────────┐                                     ▼
│  News & Social  │     ┌─────────────────┐     ┌─────────────────┐
│  Media Data     │────▶│ NLP Processing  │────▶│ Model           │
└─────────────────┘     └─────────────────┘     │ Ensemble        │
                                                └─────────────────┘
                                                        │
                                                        ▼
                        ┌─────────────────┐     ┌─────────────────┐
                        │ Prediction      │◀────│ Post-processing │
                        │ Results         │     │ & Calibration   │
                        └─────────────────┘     └─────────────────┘
```

## Model Prediksi Harga Saham

### 1. LSTM Model (Long Short-Term Memory)

Model recurrent neural network yang dirancang untuk prediksi time series.

```python
class LSTMModel:
    """
    LSTM model untuk prediksi harga saham.
    
    Menggunakan arsitektur deep learning dengan 2-3 layer LSTM
    untuk mempelajari pola dalam data time series.
    """
    
    def __init__(self, input_dim, hidden_dim, num_layers, output_dim):
        """
        Inisialisasi LSTM model
        
        Args:
            input_dim: Dimensi input features
            hidden_dim: Dimensi hidden state
            num_layers: Jumlah LSTM layers
            output_dim: Dimensi output (biasanya 1)
        """
        # Implementasi arsitektur model
        
    def train(self, X_train, y_train, epochs=100, batch_size=32):
        """
        Melatih model pada data historis
        
        Args:
            X_train: Data input training
            y_train: Target values
            epochs: Jumlah training iterations
            batch_size: Ukuran batch
            
        Returns:
            Training history
        """
        # Implementasi training logic
        
    def predict(self, X):
        """
        Melakukan prediksi harga
        
        Args:
            X: Input features
            
        Returns:
            Predicted prices
        """
        # Implementasi prediction logic
```

**Data Input:**
- Harga OHLCV (Open, High, Low, Close, Volume)
- Indikator teknikal (RSI, MACD, Bollinger Bands)
- Indikator makroekonomi
- Fitur berbasis waktu (day of week, month, etc.)

**Preprocessing:**
- Normalisasi dengan MinMaxScaler atau StandardScaler
- Konversi data ke format sequence untuk LSTM
- Feature selection

**Hyperparameter Tuning:**
- Learning rate: 0.001 - 0.0001
- Hidden layers: 2-4
- Units per layer: 32-128
- Dropout rate: 0.1-0.3
- Sequence length: 30-60 days

### 2. XGBoost Model

Model gradient boosting trees untuk prediksi jangka pendek dan menengah.

```python
class XGBoostPredictor:
    """
    XGBoost model untuk prediksi harga saham dan klasifikasi tren.
    
    Bekerja dengan baik untuk prediksi jangka pendek dan
    mengidentifikasi fitur-fitur penting.
    """
    
    def __init__(self, params=None):
        """
        Inisialisasi XGBoost model
        
        Args:
            params: Parameter model XGBoost
        """
        self.params = params or {
            'max_depth': 6,
            'learning_rate': 0.05,
            'n_estimators': 200,
            'objective': 'reg:squarederror',
            'subsample': 0.8,
            'colsample_bytree': 0.8
        }
        # Implementasi setup model
        
    def feature_importance(self):
        """
        Return feature importance dari model
        """
        # Implementasi feature importance analysis
```

**Feature Engineering:**
- Technical indicators (RSI, MACD, Bollinger Bands)
- Moving averages (MA20, MA50, MA200)
- Price momentum
- Volume indicators
- Volatility measures
- Correlation dengan index market

### 3. Prophet Model (Facebook)

Model time series untuk trend forecasting dan seasonal patterns dalam harga saham.

```python
class ProphetForecaster:
    """
    Facebook Prophet untuk forecasting dengan seasonal decomposition.
    
    Menangkap pola musiman dan tren dalam data saham.
    """
    
    def __init__(self, changepoint_prior_scale=0.05):
        """
        Inisialisasi Prophet model
        
        Args:
            changepoint_prior_scale: Flexibility dalam trend changes
        """
        # Implementasi setup model
        
    def add_market_features(self, market_data):
        """
        Menambahkan fitur market index sebagai regressor
        
        Args:
            market_data: Data market index
        """
        # Implementasi regressors addition
```

**Kekuatan:**
- Menangkap efek seasonal (mingguan, bulanan, kuartalan)
- Mengidentifikasi changepoints dalam trend
- Mengakomodasi holidays dan special events

## Model Ensemble

Sistem Zahaam menggunakan model ensemble untuk meningkatkan akurasi prediksi.

```python
class StockPredictionEnsemble:
    """
    Ensemble model yang mengkombinasikan prediksi dari multiple models
    
    Menggunakan metode weighted average berdasarkan recent performance
    dari setiap model.
    """
    
    def __init__(self, models, weights=None):
        """
        Inisialisasi ensemble
        
        Args:
            models: List model prediksi
            weights: Bobot untuk tiap model
        """
        self.models = models
        self.weights = weights
        
    def predict(self, X):
        """
        Membuat prediksi ensemble
        
        Args:
            X: Input features
            
        Returns:
            Weighted ensemble prediction
        """
        # Implementasi ensemble prediction
        
    def update_weights(self, performance_metrics):
        """
        Update bobot model berdasarkan performa
        
        Args:
            performance_metrics: Dict metrics untuk tiap model
        """
        # Implementasi weight updating berdasarkan performa
```

**Strategi Ensemble:**
- Weighted averaging berdasarkan recent RMSE
- Stacked generalization
- Bayesian combination
- Dynamic selection berdasarkan market conditions

## Model Analisis Sentimen

### 1. VADER Sentiment Analyzer

Model lexicon dan rule-based untuk analisis sentimen teks financial.

```python
class VADERSentimentAnalyzer:
    """
    Analisis sentimen menggunakan NLTK VADER
    
    Disesuaikan untuk konteks finansial dengan lexicon keuangan kustom.
    """
    
    def __init__(self, custom_lexicon=None):
        """
        Inisialisasi VADER analyzer
        
        Args:
            custom_lexicon: Kamus khusus untuk domain keuangan
        """
        # Implementasi dengan VADER dan custom lexicon
        
    def analyze(self, text):
        """
        Analisis sentimen teks
        
        Args:
            text: Teks untuk dianalisis
            
        Returns:
            Dict sentimen scores
        """
        # Implementasi analisis
```

**Preprocessing Text:**
- Tokenization
- Removing stop words
- Normalization dan stemming
- Named entity recognition untuk financial entities

### 2. FinBERT Sentiment Analyzer

Model transformer pre-trained untuk sentimen dalam domain keuangan.

```python
class FinBERTAnalyzer:
    """
    Sentimen analyzer berbasis BERT untuk teks finansial
    
    Menggunakan fine-tuned BERT model pada dataset finansial.
    """
    
    def __init__(self):
        """
        Load FinBERT model pre-trained
        """
        # Implementasi model loading
        
    def analyze_batch(self, texts, batch_size=8):
        """
        Batch processing texts untuk analisis sentimen
        
        Args:
            texts: List teks untuk dianalisis
            batch_size: Ukuran batch
            
        Returns:
            List sentiment scores
        """
        # Implementasi batch analysis
```

**Klasifikasi:**
- Positive, Negative, Neutral untuk text
- Sentiment score (-1.0 to 1.0)
- Confidence score (0.0 to 1.0)

## Pipeline Prediksi End-to-End

```python
class StockPredictionPipeline:
    """
    Pipeline end-to-end untuk prediksi saham
    
    Mengkombinasikan data market, preprocessing, prediksi model,
    dan integrasi dengan sentimen.
    """
    
    def __init__(self, ticker, models=None, sentiment_model=None):
        """
        Inisialisasi pipeline
        
        Args:
            ticker: Simbol saham
            models: Dict atau list model prediksi
            sentiment_model: Model sentimen
        """
        self.ticker = ticker
        # Implementasi setup pipeline
        
    def predict(self, days=7):
        """
        Generate prediksi untuk n hari
        
        Args:
            days: Jumlah hari untuk predict
            
        Returns:
            Dict prediction results
        """
        # Dapatkan historical data
        historical_data = self._fetch_historical_data()
        
        # Dapatkan sentimen data
        sentiment_data = self._fetch_sentiment_data()
        
        # Preprocess dan feature engineering
        features = self._prepare_features(historical_data, sentiment_data)
        
        # Generate prediksi dari model
        raw_predictions = self._generate_predictions(features, days)
        
        # Post-process dan combine prediksi
        processed_predictions = self._post_process(raw_predictions)
        
        # Generate confidence intervals
        final_predictions = self._add_confidence_intervals(processed_predictions)
        
        return {
            "ticker": self.ticker,
            "predictions": final_predictions,
            "model_info": self._get_model_info()
        }
        
    def _fetch_historical_data(self):
        """Fetch data historis saham"""
        # Implementasi data retrieval
        
    def _fetch_sentiment_data(self):
        """Fetch data sentimen untuk saham"""
        # Implementasi sentiment retrieval
        
    def _prepare_features(self, historical_data, sentiment_data):
        """Prepare features untuk model"""
        # Implementasi feature preparation
        
    def _generate_predictions(self, features, days):
        """Generate prediksi dari tiap model"""
        # Implementasi prediction
        
    def _post_process(self, raw_predictions):
        """Post-process prediksi, termasuk ensemble"""
        # Implementasi post-processing
        
    def _add_confidence_intervals(self, predictions):
        """Add confidence intervals untuk prediksi"""
        # Implementasi confidence interval calculation
        
    def _get_model_info(self):
        """Return informasi tentang model yang digunakan"""
        # Implementasi model info
```

## Model Evaluasi dan Monitoring

Sistem evaluasi model memantau performa dan memicu retraining saat diperlukan.

```python
class ModelEvaluator:
    """
    Evaluasi performa model dan monitoring
    
    Memantau model accuracy dan memicu retraining.
    """
    
    def evaluate(self, model, test_data):
        """
        Evaluasi performa prediksi model
        
        Args:
            model: Model untuk dievaluasi
            test_data: Data testing
            
        Returns:
            Dict metrics evaluasi
        """
        # Implementasi evaluation metrics
        return {
            "rmse": calculated_rmse,
            "mape": calculated_mape,
            "direction_accuracy": direction_accuracy,
            "timestamp": current_timestamp
        }
        
    def should_retrain(self, recent_metrics, threshold=0.1):
        """
        Check apakah model perlu retrain berdasarkan performa
        
        Args:
            recent_metrics: Recent performance metrics
            threshold: Threshold untuk trigger retraining
            
        Returns:
            Boolean apakah retraining diperlukan
        """
        # Implementasi retraining decision
```

**Metrik Evaluasi:**
- RMSE (Root Mean Squared Error)
- MAPE (Mean Absolute Percentage Error)
- Direction Accuracy (prediksi arah pergerakan harga)
- Profit/Loss pada simulasi trading berdasarkan sinyal model

## Integration dengan Pipeline Data

Model ML terintegrasi dengan pipeline collection data untuk update kontinyu.

```python
@app.task
def update_model_predictions(ticker):
    """
    Celery task untuk update model prediksi untuk ticker
    
    Args:
        ticker: Simbol saham
    """
    pipeline = StockPredictionPipeline(ticker)
    predictions = pipeline.predict()
    
    # Simpan hasil ke database
    save_predictions_to_db(ticker, predictions)
    
    # Update metrics di InfluxDB
    save_prediction_metrics_to_influxdb(ticker, predictions)
    
    return predictions
```

## Hyperparameter Optimization

Sistem menggunakan Bayesian optimization untuk tuning hyperparameter model.

```python
class ModelOptimizer:
    """
    Hyperparameter optimization untuk model ML
    
    Menggunakan Bayesian optimization untuk menemukan
    parameter optimal.
    """
    
    def optimize(self, model_class, param_space, X_train, y_train, X_val, y_val):
        """
        Run hyperparameter optimization
        
        Args:
            model_class: Class model
            param_space: Dict parameter space untuk dioptimasi
            X_train, y_train: Data training
            X_val, y_val: Data validasi
            
        Returns:
            Dict parameter optimal
        """
        # Implementasi Bayesian optimization
```

## Transfer Learning dan Transfer Knowledge

Model memanfaatkan transfer learning dari market data dan model umum ke saham spesifik.

```python
class MarketKnowledgeTransfer:
    """
    Transfer knowledge dari market general ke saham spesifik
    
    Menggunakan pretrained model pada keseluruhan market dan
    fine-tune untuk saham tertentu.
    """
    
    def transfer_knowledge(self, source_model, target_ticker, train_data):
        """
        Transfer dan fine-tune model untuk saham target
        
        Args:
            source_model: Model hasil training pada market
            target_ticker: Ticker saham target
            train_data: Data training untuk fine-tuning
            
        Returns:
            Model hasil fine-tuning
        """
        # Implementasi transfer learning
```

## Auto ML dan Feature Selection

Sistem memiliki kemampuan auto ML untuk feature selection dan arsitektur model.

```python
class AutoML:
    """
    Automated model selection dan feature engineering
    
    Menemukan kombinasi feature dan arsitektur yang optimal.
    """
    
    def find_best_model(self, X, y, time_budget=3600):
        """
        Find best model dan features
        
        Args:
            X: Input features
            y: Target
            time_budget: Time budget untuk pencarian (seconds)
            
        Returns:
            Tuple (best_model, best_features, score)
        """
        # Implementasi model dan feature search
```

## Model Monitoring di Production

Sistem melakukan monitoring model di production dan deteksi drift.

```python
class ModelMonitor:
    """
    Production monitoring untuk model ML
    
    Mendeteksi drift, penurunan performa, dan anomali.
    """
    
    def check_for_drift(self, recent_data, baseline_distribution):
        """
        Check data drift
        
        Args:
            recent_data: Data terkini
            baseline_distribution: Distribusi baseline
            
        Returns:
            Dict hasil deteksi drift
        """
        # Implementasi drift detection
        
    def record_prediction_metrics(self, ticker, predictions, actuals):
        """
        Record metrics prediksi untuk monitoring
        """
        # Implementasi metrics recording
```

## Roadmap Pengembangan Model

1. **Short-Term (1-3 months)**
   - Implementasi model ensemble dengan LSTM, XGBoost, dan Prophet
   - Integrasi sentimen untuk pengaruh jangka pendek pada prediksi harga
   - Evaluasi batch model pada berbagai instrumen

2. **Medium-Term (3-6 months)**
   - Transformer-based models untuk prediksi time series
   - Deep reinforcement learning untuk strategi trading
   - Analisis network untuk korelasi saham dan sektor

3. **Long-Term (6-12 months)**
   - Online learning dan continuous adaptation
   - Explainable AI untuk interpretasi prediksi
   - Multi-objective optimization untuk balancing return and risk
