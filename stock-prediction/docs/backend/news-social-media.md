# News & Social Media Integration

Dokumentasi ini menjelaskan integrasi API berita dan media sosial yang komprehensif di backend Zahaam untuk meningkatkan analisis sentimen pasar dan kapabilitas prediksi saham.

## Arsitektur Modul News & Social Media

```
news_data/
├── __init__.py
├── collector.py       # Collector untuk NewsAPI dan sumber berita lainnya
├── config.py          # Konfigurasi untuk news & social media collection
├── models.py          # Model database untuk berita & media sosial
├── sentiment_analysis.py  # Analisis sentimen lanjutan untuk teks finansial
└── social_media.py    # Collector untuk Twitter dan platform media sosial lainnya
```

## Pengumpulan Data Berita dengan NewsAPI

Modul collector.py menyediakan fungsi untuk mengumpulkan berita finansial dari NewsAPI.org dan sumber lainnya.

### NewsAPI Collector

```python
class NewsAPICollector:
    """
    Kolektor berita dari NewsAPI.org
    
    Mengumpulkan berita finansial berdasarkan query, ticker, dan sumber berita.
    Mendukung berbagai parameter filter seperti tanggal, bahasa, dan sortir.
    """
    
    def __init__(self, config=None):
        """Inisialisasi collector dengan konfigurasi opsional"""
        self.config = config or ConfigManager()
        self.api_key = self.config.get("newsapi.api_key") or os.environ.get("NEWSAPI_KEY")
        self.base_url = "https://newsapi.org/v2"
        self.default_sources = self.config.get("newsapi.default_sources")
        
    def get_everything(self, query=None, sources=None, from_date=None, to_date=None, 
                      language="en", sort_by="publishedAt", page=1, page_size=100):
        """
        Mendapatkan berita dari endpoint /everything
        
        Args:
            query: Keyword pencarian
            sources: Sumber berita (dipisahkan koma)
            from_date: Tanggal awal format YYYY-MM-DD
            to_date: Tanggal akhir format YYYY-MM-DD
            language: Bahasa artikel (default: en)
            sort_by: Urutan (relevancy, popularity, publishedAt)
            page: Halaman hasil
            page_size: Jumlah artikel per halaman
            
        Returns:
            Dict berisi artikel berita dan metadata
        """
        # Implementasi request ke NewsAPI
        
    def get_financial_news(self, ticker=None, query=None, sources=None, days_back=7):
        """
        Mendapatkan berita finansial dengan filter khusus
        
        Args:
            ticker: Ticker saham (opsional)
            query: Query pencarian tambahan
            sources: Sumber berita
            days_back: Ambil berita berapa hari ke belakang
            
        Returns:
            List artikel berita finansial
        """
        # Implementasi pencarian berita finansial dengan filter
        
    def save_to_database(self, articles, session=None):
        """
        Menyimpan artikel berita ke database
        
        Args:
            articles: List artikel berita dari NewsAPI
            session: SQLAlchemy session
        """
        # Implementasi penyimpanan ke database
```

### Penggunaan NewsAPI Collector

```python
# Contoh penggunaan
collector = NewsAPICollector()

# Dapatkan berita terkait Apple
apple_news = collector.get_financial_news(ticker="AAPL")

# Dapatkan berita pasar saham Indonesia
idn_news = collector.get_financial_news(
    query="IDX OR Indonesia stock market OR BEI", 
    sources="cnbc,bloomberg,reuters"
)

# Dapatkan berita tentang inflasi
inflation_news = collector.get_financial_news(query="inflation impact economy")
```

## Pengumpulan Data Media Sosial

Modul social_media.py mengimplementasikan collector untuk berbagai platform media sosial, dengan fokus utama pada Twitter/X.

### Twitter Collector

```python
class TwitterCollector:
    """
    Kolektor data dari Twitter API v2
    
    Mengumpulkan tweet berdasarkan cashtag, hashtag, dan akun influencer.
    Mendukung pencarian berdasarkan keyword dan advanced search.
    """
    
    def __init__(self, config=None):
        """Inisialisasi collector dengan konfigurasi"""
        self.config = config or ConfigManager()
        self.bearer_token = self.config.get("twitter.bearer_token") or os.environ.get("TWITTER_BEARER_TOKEN")
        self.api_key = self.config.get("twitter.api_key") or os.environ.get("TWITTER_API_KEY")
        self.api_secret = self.config.get("twitter.api_secret") or os.environ.get("TWITTER_API_SECRET")
        
    def get_cashtag_tweets(self, ticker, max_results=100):
        """
        Mendapatkan tweet yang memuat cashtag tertentu
        
        Args:
            ticker: Ticker saham (tanpa simbol $)
            max_results: Jumlah maksimum tweet yang diambil
            
        Returns:
            List tweet dengan cashtag
        """
        # Implementasi pencarian tweet dengan cashtag
        
    def get_influencer_tweets(self, influencer_ids=None, max_results=100):
        """
        Mendapatkan tweet dari influencer keuangan
        
        Args:
            influencer_ids: List ID Twitter influencer
            max_results: Jumlah maksimum tweet
            
        Returns:
            Dict tweet influencer per ID
        """
        # Implementasi pengambilan tweet influencer
        
    def search_financial_tweets(self, query, max_results=100):
        """
        Mencari tweet dengan keyword keuangan
        
        Args:
            query: Query pencarian
            max_results: Jumlah maksimum tweet
            
        Returns:
            List tweet hasil pencarian
        """
        # Implementasi pencarian tweet finansial
        
    def save_to_database(self, tweets, author_data=None, session=None):
        """
        Menyimpan tweet ke database
        
        Args:
            tweets: List tweet
            author_data: Data author tweet
            session: SQLAlchemy session
        """
        # Implementasi penyimpanan tweet ke database
```

### Penggunaan Twitter Collector

```python
# Contoh penggunaan
twitter = TwitterCollector()

# Dapatkan tweet tentang Apple
apple_tweets = twitter.get_cashtag_tweets("AAPL")

# Dapatkan tweet dari influencer keuangan
influencer_tweets = twitter.get_influencer_tweets([
    "1445831128",  # Bloomberg
    "20402945",    # CNBC
    "60420160"     # MarketWatch
])

# Cari tweet tentang pasar Indonesia
idn_tweets = twitter.search_financial_tweets("Indonesian stock market OR IDX OR BEI")
```

## Integrasi Reddit (Planned)

Modul ini direncanakan untuk mengumpulkan data dari subreddit finansial seperti r/investing, r/stocks, dan r/wallstreetbets.

```python
class RedditCollector:
    """
    Kolektor data dari Reddit API
    
    Mengumpulkan post dan komentar dari subreddit finansial.
    """
    
    def __init__(self, config=None):
        """Inisialisasi collector dengan konfigurasi"""
        self.config = config or ConfigManager()
        self.client_id = self.config.get("reddit.client_id") or os.environ.get("REDDIT_CLIENT_ID")
        self.client_secret = self.config.get("reddit.client_secret") or os.environ.get("REDDIT_CLIENT_SECRET")
        self.user_agent = self.config.get("reddit.user_agent") or os.environ.get("REDDIT_USER_AGENT")
        
    def get_subreddit_posts(self, subreddit, limit=25, time_filter="day"):
        """
        Mendapatkan post dari subreddit tertentu
        
        Args:
            subreddit: Nama subreddit
            limit: Jumlah post maksimum
            time_filter: Periode waktu (hour, day, week, month, year, all)
            
        Returns:
            List post dari subreddit
        """
        # Implementasi pengambilan post subreddit
        
    def search_stock_mentions(self, ticker, limit=100):
        """
        Mencari post yang menyebutkan ticker saham tertentu
        
        Args:
            ticker: Ticker saham
            limit: Jumlah post maksimum
            
        Returns:
            List post yang menyebutkan ticker
        """
        # Implementasi pencarian post dengan ticker
```

## Konfigurasi Modul News & Social Media

Modul config.py menyediakan manajemen konfigurasi untuk pengumpulan data berita dan media sosial.

### Configuration Manager

```python
class ConfigManager:
    """
    Manajer konfigurasi untuk news_data dan social_media
    
    Menyediakan akses ke konfigurasi dengan prioritas:
    1. Environment variables
    2. Config file
    3. Default values
    """
    
    def __init__(self, config_file=None):
        """
        Inisialisasi ConfigManager
        
        Args:
            config_file: Path ke file konfigurasi YAML (opsional)
        """
        self.config = {
            # Default configurations
            "newsapi": {
                "api_key": None,
                "default_sources": "bloomberg,cnbc,reuters,financial-times,the-wall-street-journal",
                "collection_interval": 60,  # minutes
                "max_requests_per_day": 100,
            },
            "twitter": {
                "bearer_token": None,
                "api_key": None, 
                "api_secret": None,
                "collection_interval": 15,  # minutes
                "default_influencers": [
                    "1445831128",  # Bloomberg
                    "20402945",    # CNBC
                    "60420160"     # MarketWatch
                ],
                "default_cashtags": ["AAPL", "MSFT", "GOOGL", "AMZN", "META"]
            },
            # Konfigurasi lainnya
        }
        
        # Load dari file konfigurasi jika ada
        if config_file and os.path.exists(config_file):
            with open(config_file, 'r') as f:
                yaml_config = yaml.safe_load(f)
                self._deep_update(self.config, yaml_config)
                
        # Override dengan environment variables
        self._load_from_env()
        
    def get(self, key_path, default=None):
        """
        Mendapatkan value dari config dengan dot notation
        
        Args:
            key_path: Path ke config menggunakan dot notation
                      (e.g. "newsapi.api_key")
            default: Nilai default jika key tidak ditemukan
            
        Returns:
            Config value atau default
        """
        # Implementasi pengambilan config dengan dot notation
        
    def save_to_file(self, file_path):
        """
        Menyimpan konfigurasi ke file YAML
        
        Args:
            file_path: Path ke file YAML
        """
        # Implementasi penyimpanan ke file YAML
```

### Penggunaan Config Manager

```python
# Contoh penggunaan
config = ConfigManager()

# Dapatkan API key
api_key = config.get("newsapi.api_key")

# Dapatkan interval pengumpulan data
interval = config.get("twitter.collection_interval", 15)  # default 15 menit

# Dapatkan list default cashtags
cashtags = config.get("twitter.default_cashtags", [])
```

## Integrasi dengan Database

Modul ini menggunakan SQLAlchemy untuk menyimpan data berita dan media sosial ke database PostgreSQL.

### Proses Pengumpulan dan Penyimpanan Data

1. Collector mengambil data dari API eksternal
2. Data diproses dan dibersihkan
3. Entitas diidentifikasi (ticker, perusahaan, dll)
4. Sentimen dianalisis menggunakan `FinancialSentimentAnalyzer`
5. Data disimpan ke dalam tabel yang sesuai
6. Metrik collection disimpan ke InfluxDB untuk monitoring

### Contoh Flow Integrasi

```python
# Contoh flow integrasi lengkap

# Inisialisasi komponen
config = ConfigManager()
news_collector = NewsAPICollector(config)
twitter_collector = TwitterCollector(config)
sentiment_analyzer = FinancialSentimentAnalyzer()
db_session = get_db_session()

# Pengumpulan data berita
financial_news = news_collector.get_financial_news(ticker="AAPL")

# Analisis sentimen berita
for article in financial_news:
    sentiment_result = sentiment_analyzer.analyze_text(article['content'])
    article['sentiment'] = sentiment_result

# Penyimpanan ke database
news_collector.save_to_database(financial_news, session=db_session)

# Pengumpulan tweet
apple_tweets = twitter_collector.get_cashtag_tweets("AAPL")

# Analisis sentimen tweet
for tweet in apple_tweets:
    sentiment_result = sentiment_analyzer.analyze_text(tweet['text'])
    tweet['sentiment'] = sentiment_result

# Penyimpanan tweet ke database
twitter_collector.save_to_database(apple_tweets, session=db_session)

# Commit perubahan
db_session.commit()

# Simpan metrik ke InfluxDB
save_collection_metrics({
    'source': 'newsapi',
    'articles_collected': len(financial_news),
    'tweets_collected': len(apple_tweets),
    'execution_time_ms': execution_time
})
```

## Environment Variables

Berikut adalah environment variables untuk konfigurasi modul news_data dan social_media:

| Variable | Deskripsi | Default |
| --- | --- | --- |
| `NEWSAPI_KEY` | API key untuk NewsAPI.org | - |
| `NEWSAPI_COLLECTION_INTERVAL` | Interval pengumpulan berita (menit) | 60 |
| `TWITTER_BEARER_TOKEN` | Bearer token untuk Twitter API v2 | - |
| `TWITTER_API_KEY` | API key untuk Twitter API | - |
| `TWITTER_API_SECRET` | API secret untuk Twitter API | - |
| `TWITTER_COLLECTION_INTERVAL` | Interval pengumpulan tweet (menit) | 15 |
| `REDDIT_CLIENT_ID` | Client ID untuk Reddit API | - |
| `REDDIT_CLIENT_SECRET` | Client secret untuk Reddit API | - |
| `ENABLE_NEWS_COLLECTOR` | Enable/disable news collector | true |
| `ENABLE_TWITTER_COLLECTOR` | Enable/disable Twitter collector | true |
| `ENABLE_REDDIT_COLLECTOR` | Enable/disable Reddit collector | false |
