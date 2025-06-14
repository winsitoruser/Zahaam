/**
 * Konfigurasi aplikasi Zahaam
 * File ini berisi variabel konfigurasi global yang digunakan di seluruh aplikasi
 */

// Konfigurasi API
const API_CONFIG = {
    baseUrl: 'http://localhost:5005/api',
    // Tambahkan konfigurasi API lainnya di sini
};

// Konfigurasi URL halaman
const PAGE_URLS = {
    // Halaman utama
    dashboard: 'dashboard.html',
    login: 'login.html',
    register: 'register.html',
    
    // Halaman saham
    stockDetail: 'stock-basic.html',
    stockPrediction: 'prediction.html',
    stockList: 'stocks.html',
    
    // Halaman pengguna
    userProfile: 'profile.html',
    userNotifications: 'notifications.html',
    
    // Halaman lainnya
    strategy: 'strategy.html'
};

// Fungsi utilitas untuk menghasilkan URL dengan parameter
function generatePageUrl(pageKey, params = {}) {
    // Pastikan pageKey valid
    if (!PAGE_URLS[pageKey]) {
        console.error(`Page key "${pageKey}" tidak ditemukan dalam konfigurasi`);
        return '#'; // Fallback ke anchor kosong
    }
    
    // Dapatkan URL dasar
    let url = PAGE_URLS[pageKey];
    
    // Jika ada parameter, tambahkan sebagai query string
    if (Object.keys(params).length > 0) {
        const queryParams = Object.entries(params)
            .filter(([_, value]) => value !== null && value !== undefined)
            .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
            .join('&');
            
        url = `${url}?${queryParams}`;
    }
    
    return url;
}

// Export fungsi dan konstanta
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        API_CONFIG,
        PAGE_URLS,
        generatePageUrl
    };
}
