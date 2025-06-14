/**
 * dashboard-trading-signals.js
 * Menangani trading signals di dashboard ZAHAAM
 */

/**
 * Fetch trading signals dashboard data
 */
async function fetchTradingSignalsDashboard() {
    console.log('Fetching trading signals dashboard data...');
    
    // Get DOM elements
    const bullishTableBody = document.getElementById('topBullishTableBody');
    const bearishTableBody = document.getElementById('topBearishTableBody');
    
    // Skip if elements don't exist
    if (!bullishTableBody && !bearishTableBody) {
        console.log('Skipping signals dashboard fetch - elements not found');
        return;
    }
    
    // Show loading state
    setLoadingState(bullishTableBody);
    setLoadingState(bearishTableBody);
    
    try {
        // Try to fetch from API
        const data = await fetchSignalsFromApi();
        
        // Process bullish stocks
        if (bullishTableBody && data.top_bullish && Array.isArray(data.top_bullish)) {
            renderBullishTable(bullishTableBody, data.top_bullish);
        }
        
        // Process bearish stocks
        if (bearishTableBody && data.top_bearish && Array.isArray(data.top_bearish)) {
            renderBearishTable(bearishTableBody, data.top_bearish);
        }
    } catch (error) {
        console.error('Error fetching trading signals dashboard:', error);
        // Render sample data as fallback
        renderSampleSignals(bullishTableBody, bearishTableBody);
    }
}

/**
 * Set loading state for a table
 */
function setLoadingState(tableBody) {
    if (!tableBody) return;
    
    tableBody.innerHTML = `
        <tr>
            <td colspan="4" class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2">Memuat data...</span>
            </td>
        </tr>
    `;
}

/**
 * Fetch signals from API with error handling
 */
async function fetchSignalsFromApi() {
    let apiUrl;
    
    // Use API service if available
    if (typeof API_CONFIG !== 'undefined') {
        const baseUrl = API_CONFIG.baseUrl;
        apiUrl = `${baseUrl}${API_CONFIG.endpoints.signals}`;
        console.log(`Using API service: ${apiUrl}`);
    } else {
        // Fallback to direct API call
        const baseUrl = window.location.origin.includes('localhost') ? 'http://localhost:8000' : '';
        apiUrl = `${baseUrl}/api/signals/dashboard`;
        console.log(`Using direct API call: ${apiUrl}`);
    }
    
    // Get auth headers
    const headers = typeof getAuthHeaders === 'function' 
        ? getAuthHeaders() 
        : {
            'Accept': 'application/json',
            'Authorization': localStorage.getItem('token') ? `Bearer ${localStorage.getItem('token')}` : ''
        };
    
    // Make API request
    const response = await fetch(apiUrl, {
        headers: headers,
        credentials: 'include'
    });
    
    if (!response.ok) {
        throw new Error(`Error fetching signals dashboard: ${response.statusText}`);
    }
    
    return await response.json();
}

/**
 * Render bullish table with data
 */
function renderBullishTable(tableBody, stocks) {
    if (!tableBody || !stocks || !Array.isArray(stocks) || stocks.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-3">
                    <i class="bi bi-info-circle me-2"></i>Tidak ada sinyal bullish saat ini
                </td>
            </tr>
        `;
        return;
    }
    
    // Take top 5 stocks
    const topStocks = stocks.slice(0, 5);
    
    // Render table rows
    tableBody.innerHTML = topStocks.map(stock => {
        const symbol = stock.symbol || stock.ticker || '';
        const name = stock.name || stock.stock_name || '';
        const price = typeof stock.price !== 'undefined' ? formatCurrency(stock.price) : '-';
        const signal = stock.signal_type || 'Buy';
        
        return `
            <tr>
                <td><a href="stock-basic.html?symbol=${symbol}" class="fw-bold">${symbol}</a></td>
                <td>${name}</td>
                <td class="text-end">${price}</td>
                <td class="text-end"><span class="badge bg-success">${signal}</span></td>
            </tr>
        `;
    }).join('');
}

/**
 * Render bearish table with data
 */
function renderBearishTable(tableBody, stocks) {
    if (!tableBody || !stocks || !Array.isArray(stocks) || stocks.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-3">
                    <i class="bi bi-info-circle me-2"></i>Tidak ada sinyal bearish saat ini
                </td>
            </tr>
        `;
        return;
    }
    
    // Take top 5 stocks
    const topStocks = stocks.slice(0, 5);
    
    // Render table rows
    tableBody.innerHTML = topStocks.map(stock => {
        const symbol = stock.symbol || stock.ticker || '';
        const name = stock.name || stock.stock_name || '';
        const price = typeof stock.price !== 'undefined' ? formatCurrency(stock.price) : '-';
        const signal = stock.signal_type || 'Sell';
        
        return `
            <tr>
                <td><a href="stock-basic.html?symbol=${symbol}" class="fw-bold">${symbol}</a></td>
                <td>${name}</td>
                <td class="text-end">${price}</td>
                <td class="text-end"><span class="badge bg-danger">${signal}</span></td>
            </tr>
        `;
    }).join('');
}

/**
 * Render sample signals when API fails
 */
function renderSampleSignals(bullishTableBody, bearishTableBody) {
    const bullishSamples = [
        { symbol: 'BBRI', name: 'Bank BRI', price: 4250, signal: 'Strong Buy', change: '+2.45%' },
        { symbol: 'ASII', name: 'Astra International', price: 5875, signal: 'Buy', change: '+1.67%' },
        { symbol: 'TLKM', name: 'Telkom Indonesia', price: 3690, signal: 'Buy', change: '+1.32%' },
        { symbol: 'BBCA', name: 'Bank BCA', price: 9725, signal: 'Buy', change: '+0.89%' }
    ];
    
    const bearishSamples = [
        { symbol: 'UNVR', name: 'Unilever Indonesia', price: 4120, signal: 'Sell', change: '-1.24%' },
        { symbol: 'HMSP', name: 'HM Sampoerna', price: 1505, signal: 'Sell', change: '-1.58%' },
        { symbol: 'INDF', name: 'Indofood', price: 6750, signal: 'Strong Sell', change: '-2.17%' },
        { symbol: 'EXCL', name: 'XL Axiata', price: 2310, signal: 'Sell', change: '-0.93%' }
    ];
    
    // Render bullish samples
    if (bullishTableBody) {
        renderBullishTable(bullishTableBody, bullishSamples);
    }
    
    // Render bearish samples
    if (bearishTableBody) {
        renderBearishTable(bearishTableBody, bearishSamples);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    fetchTradingSignalsDashboard();
});
