// api-service.js - Layanan API untuk mengambil data saham dari backend

/**
 * Konfigurasi API
 */
const API_CONFIG = {
    baseUrl: window.location.origin.includes('localhost') ? 'http://localhost:5005' : '',
    endpoints: {
        stocks: '/api/stocks',
        stockDetail: '/api/stocks/detail',
        predictions: '/api/ml/predictions',
        signals: '/api/signals'
    },
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
};

/**
 * Mendapatkan headers untuk request API dengan token autentikasi
 * @returns {Object} Headers untuk request API
 */
function getAuthHeaders() {
    const token = localStorage.getItem('token');
    return {
        ...API_CONFIG.headers,
        'Authorization': token ? `Bearer ${token}` : ''
    };
}

/**
 * Mendapatkan daftar saham dari API
 * @param {Object} options - Opsi tambahan untuk request
 * @param {string} options.sector - Filter berdasarkan sektor (opsional)
 * @param {string} options.search - Keyword pencarian (opsional)
 * @param {number} options.page - Halaman data untuk pagination (opsional)
 * @param {number} options.limit - Jumlah data per halaman (opsional)
 * @returns {Promise<Object>} Promise yang me-resolve data saham dan metadata
 */
async function fetchStocks(options = {}) {
    try {
        console.log('Memulai fetching data saham dari API...');
        
        // Buat URL dengan query parameters jika ada
        let url = new URL(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.stocks}`);
        
        // Tambahkan query parameters jika ada
        if (options.sector && options.sector !== 'all') {
            url.searchParams.append('sector', options.sector);
        }
        
        if (options.search && options.search.trim() !== '') {
            url.searchParams.append('search', options.search.trim());
        }
        
        if (options.page && options.page > 0) {
            url.searchParams.append('page', options.page);
        }
        
        if (options.limit && options.limit > 0) {
            url.searchParams.append('limit', options.limit);
        }
        
        console.log(`Memanggil API: ${url.toString()}`);
        
        const response = await fetch(url.toString(), {
            method: 'GET',
            headers: getAuthHeaders(),
            credentials: 'include',
            timeout: 8000 // 8 detik timeout
        });

        if (!response.ok) {
            console.warn(`API Error (${response.status}): ${response.statusText}`);
            throw new Error(`Error fetching stocks: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        console.log('Data saham berhasil diterima:', data);
        
        // Struktur data yang diharapkan: { stocks: [...], meta: { total, page, limit } }
        // Jika tidak ada meta, gunakan nilai default
        const result = {
            stocks: data.stocks || data.data || [],
            meta: data.meta || { 
                total: (data.stocks || data.data || []).length,
                page: options.page || 1,
                limit: options.limit || (data.stocks || data.data || []).length
            }
        };
        
        // Validasi data yang diterima
        if (!Array.isArray(result.stocks)) {
            console.error('Format data yang diterima dari API tidak valid');
            throw new Error('Invalid data format from API');
        }
        
        // Pastikan setiap item saham memiliki semua field yang dibutuhkan
        result.stocks = result.stocks.map(stock => ({
            code: stock.code || stock.symbol || '',
            name: stock.name || stock.company_name || '',
            sector: stock.sector || stock.industry || '',
            price: parseFloat(stock.price || stock.last_price || 0),
            change: parseFloat(stock.change || stock.price_change_percent || 0),
            volume: parseInt(stock.volume || 0, 10),
            // Tambahkan data lain dari API jika ada
            ...stock
        }));
        
        return result;
    } catch (error) {
        console.error('Failed to fetch stocks from API:', error);
        
        // Buat struktur data yang konsisten untuk fallback
        return { 
            stocks: indonesianStocks,
            meta: {
                total: indonesianStocks.length,
                page: options.page || 1,
                limit: options.limit || indonesianStocks.length
            },
            isOffline: true // Tandai bahwa ini adalah data offline
        };
    }
}

/**
 * Mendapatkan detail saham dari API
 * @param {string} symbol - Kode saham
 * @param {Object} options - Opsi tambahan untuk request
 * @param {string} options.timeframe - Jangka waktu grafik (1d, 1w, 1m, dll.)
 * @returns {Promise<Object>} Promise yang me-resolve detail saham
 */
async function fetchStockDetail(symbol, options = {}) {
    try {
        if (!symbol) {
            throw new Error('Kode saham (symbol) harus disediakan');
        }
        
        console.log(`Memulai fetching detail saham ${symbol} dari API...`);
        
        // Buat URL dengan query parameters jika ada
        let url = new URL(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.stockDetail}/${symbol}`);
        
        // Tambahkan query parameter timeframe jika ada
        if (options.timeframe) {
            url.searchParams.append('timeframe', options.timeframe);
        }
        
        console.log(`Memanggil API: ${url.toString()}`);
        
        const response = await fetch(url.toString(), {
            method: 'GET',
            headers: getAuthHeaders(),
            credentials: 'include',
            timeout: 10000 // 10 detik timeout
        });

        if (!response.ok) {
            console.warn(`API Error (${response.status}): ${response.statusText}`);
            throw new Error(`Error fetching stock detail: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        console.log(`Detail saham ${symbol} berhasil diterima:`, data);
        
        // Normalisasi data jika diperlukan
        const stockDetail = data.stock || data.data || data;
        
        // Periksa apakah data yang diterima valid
        if (!stockDetail || typeof stockDetail !== 'object') {
            console.error(`Format data detail saham ${symbol} tidak valid`);
            throw new Error('Invalid data format from API');
        }
        
        return {
            // Data dasar saham
            symbol: stockDetail.code || stockDetail.symbol || symbol,
            name: stockDetail.name || stockDetail.company_name || '',
            sector: stockDetail.sector || stockDetail.industry || '',
            price: parseFloat(stockDetail.price || stockDetail.last_price || 0),
            change: parseFloat(stockDetail.change || stockDetail.price_change_percent || 0),
            volume: parseInt(stockDetail.volume || 0, 10),
            
            // Data grafik jika ada
            chart: stockDetail.chart || stockDetail.chartData || null,
            
            // Data fundamental jika ada
            fundamentals: stockDetail.fundamentals || null,
            
            // Data lainnya
            ...stockDetail
        };
    } catch (error) {
        console.error(`Failed to fetch detail for ${symbol}:`, error);
        
        // Fallback ke data statis jika API gagal
        const staticStock = indonesianStocks.find(stock => stock.code === symbol);
        
        if (!staticStock) {
            console.warn(`No static data found for symbol: ${symbol}`);
            return null;
        }
        
        console.log(`Using static data for ${symbol}`);
        return {
            ...staticStock,
            isOffline: true // Tandai bahwa ini adalah data offline
        };
    }
}

// Mendapatkan parameter URL
function getUrlParameter(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
}
