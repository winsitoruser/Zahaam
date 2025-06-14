/**
 * dashboard-predictions.js
 * Menangani widget prediksi multi-timeframe dan signal trading di dashboard ZAHAAM
 */

// Global variables
let dashboardPredictionChart = null;
let dashboardCurrentSymbol = 'BBRI';
let dashboardPredictionData = null;

// Timeframe mapping untuk API dan display
const dashboardTimeframeMap = {
    'hour1': {
        apiValue: '1h',
        displayName: '1 Jam',
        chartColor: '#00C7B1',
        icon: 'bi-clock'
    },
    'hour3': {
        apiValue: '3h',
        displayName: '3 Jam',
        chartColor: '#36A2EB',
        icon: 'bi-clock-history'
    },
    'hour24': {
        apiValue: '24h',
        displayName: '24 Jam',
        chartColor: '#4C51BF',
        icon: 'bi-calendar-day'
    },
    'day7': {
        apiValue: '7d',
        displayName: '7 Hari',
        chartColor: '#9061F9',
        icon: 'bi-calendar-week'
    },
    'day30': {
        apiValue: '30d',
        displayName: '30 Hari',
        chartColor: '#6875F5',
        icon: 'bi-calendar-month'
    }
};

/**
 * Inisialisasi prediksi dashboard dan trading signals
 */
function initDashboardPredictions() {
    console.log('Initializing dashboard predictions widget...');
    
    // Setup event listeners untuk simbol saham
    const symbolSelect = document.getElementById('dashboardPredictionSymbol');
    if (symbolSelect) {
        symbolSelect.addEventListener('change', function() {
            dashboardCurrentSymbol = this.value;
            fetchDashboardPredictions(dashboardCurrentSymbol);
        });
    }
    
    // Setup refresh button
    const refreshButton = document.getElementById('refreshDashboardPredictions');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            fetchDashboardPredictions(dashboardCurrentSymbol);
        });
    }
    
    // Hanya panggil fetchDashboardPredictions sekali untuk menghindari duplikasi
    fetchDashboardPredictions(dashboardCurrentSymbol);
    fetchTradingSignalsDashboard();
}

/**
 * Fetch prediksi multi-timeframe untuk dashboard
 * @param {string} symbol - Ticker saham
 */
async function fetchDashboardPredictions(symbol) {
    console.log(`Fetching predictions for ${symbol}...`);
    
    // Show loading state
    const loadingEl = document.getElementById('dashboardPredictionLoading');
    const contentEl = document.getElementById('dashboardPredictionContent');
    
    if (loadingEl && contentEl) {
        loadingEl.style.display = 'block';
        contentEl.style.display = 'none';
    }
    
    try {
        // Fetch data from multi-timeframe API with proper base URL handling for localhost
        const baseUrl = window.location.origin.includes('localhost') 
            ? 'http://localhost:8000' : '';
        
        // Try alternative endpoint patterns - some API endpoints might have different structures
        // First try standard endpoint format
        const apiUrl = `${baseUrl}/api/ml/timeframe/predictions/${symbol}`;
        console.log(`Fetching predictions from: ${apiUrl}`);
        
        const response = await fetch(apiUrl, {
            headers: {
                'Accept': 'application/json',
                ...localStorage.getItem('token') ? {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                } : {}
            },
            credentials: 'include' // Include cookies for cross-domain requests
        });
        
        if (!response.ok) {
            throw new Error(`Error fetching predictions: ${response.statusText}`);
        }
        
        dashboardPredictionData = await response.json();
        console.log('Prediction data received:', dashboardPredictionData);
        renderDashboardPredictions();
        
    } catch (error) {
        console.error('Error fetching predictions:', error);
        // Show error message
        if (contentEl) {
            contentEl.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> Error loading predictions: ${error.message}
                </div>
                <div class="alert alert-info small mt-2">
                    <i class="bi bi-info-circle"></i> Trying to generate sample data...
                </div>
            `;
            
            // Generate sample prediction data when API fails - execute immediately
            console.log('Generating sample prediction data for', symbol);
            dashboardPredictionData = generateSamplePredictionData(symbol);
            renderDashboardPredictions();
            contentEl.style.display = 'block';
            if (loadingEl) loadingEl.style.display = 'none';
        }
    }
}

/**
 * Fetch trading signals dashboard data
 */
async function fetchTradingSignalsDashboard() {
    console.log('Fetching trading signals dashboard data...');
    
    // Get top bullish stocks table body
    const bullishTableBody = document.getElementById('topBullishTableBody');
    const bearishTableBody = document.getElementById('topBearishTableBody');
    const signalsContainer = document.getElementById('dashboardPredictionSignals');
    
    // Skip if elements don't exist
    if (!bullishTableBody && !bearishTableBody) {
        console.log('Skipping signals dashboard fetch - elements not found');
        return;
    }
    
    // Set loading state
    if (bullishTableBody) {
        bullishTableBody.innerHTML = `
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
    
    if (bearishTableBody) {
        bearishTableBody.innerHTML = `
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
    
    try {
        // Gunakan API service jika tersedia, otherwise fallback ke metode lama
        let baseUrl, apiUrl, data;
        
        if (typeof API_CONFIG !== 'undefined') {
            baseUrl = API_CONFIG.baseUrl;
            apiUrl = `${baseUrl}${API_CONFIG.endpoints.signals}`;
            
            console.log(`Fetching signals dashboard from API service: ${apiUrl}`);
            
            const response = await fetch(apiUrl, {
                headers: {
                    'Accept': 'application/json',
                    ...localStorage.getItem('token') ? {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    } : {}
                },
                credentials: 'include' // Include cookies for cross-domain requests
            });
            
            if (!response.ok) {
                throw new Error(`Error fetching signals: ${response.statusText}`);
            }
            
            data = await response.json();
        } else {
            // Fallback ke metode lama
            data = await fetchSignalsDashboardLegacy();
        }
        
        // Render bullish signals
        if (bullishTableBody && data.buy_signals && data.buy_signals.length > 0) {
            const buySignals = data.buy_signals;
            
            if (buySignals.length === 0) {
                bullishTableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-3">
                            <i class="bi bi-info-circle me-2"></i>Tidak ada sinyal bullish saat ini
                        </td>
                    </tr>
                `;
            } else {
                bullishTableBody.innerHTML = '';
                
                // Sort by consensus score (highest first)
                buySignals.sort((a, b) => b.consensus_score - a.consensus_score);
                
                // Take top 5
                const topBuy = buySignals.slice(0, 5);
                
                topBuy.forEach(signal => {
                    // Create signal strength indicator
                    const strength = signal.consensus_score / 10 * 100; // Convert to percentage
                    
                    bullishTableBody.innerHTML += `
                        <tr>
                            <td>
                                <a href="stock-basic.html?symbol=${signal.ticker}" class="text-decoration-none">
                                    <span class="ticker-symbol">${signal.ticker}</span>
                                    <div class="stock-name">${signal.stock_name || ''}</div>
                                </a>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold ${signal.price_change > 0 ? 'price-up' : 'price-down'}">
                                    ${signal.current_price ? signal.current_price.toLocaleString() : '-'}
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="${signal.price_change > 0 ? 'price-up' : 'price-down'}">
                                    ${signal.price_change > 0 ? '+' : ''}${signal.price_change_percent ? signal.price_change_percent.toFixed(2) + '%' : '-'}
                                </span>
                            </td>
                            <td>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: ${strength}%" aria-valuenow="${strength}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
        }
        
        // Render bearish signals
        if (bearishTableBody && data.sell_signals && data.sell_signals.length > 0) {
            const sellSignals = data.sell_signals;
            
            if (sellSignals.length === 0) {
                bearishTableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-3">
                            <i class="bi bi-info-circle me-2"></i>Tidak ada sinyal bearish saat ini
                        </td>
                    </tr>
                `;
            } else {
                bearishTableBody.innerHTML = '';
                
                // Sort by consensus score (highest first)
                sellSignals.sort((a, b) => b.consensus_score - a.consensus_score);
                
                // Take top 5
                const topSell = sellSignals.slice(0, 5);
                
                topSell.forEach(signal => {
                    // Create signal strength indicator
                    const strength = signal.consensus_score / 10 * 100; // Convert to percentage
                    
                    bearishTableBody.innerHTML += `
                        <tr>
                            <td>
                                <a href="stock-basic.html?symbol=${signal.ticker}" class="text-decoration-none">
                                    <span class="ticker-symbol">${signal.ticker}</span>
                                    <div class="stock-name">${signal.stock_name || ''}</div>
                                </a>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold ${signal.price_change > 0 ? 'price-up' : 'price-down'}">
                                    ${signal.current_price ? signal.current_price.toLocaleString() : '-'}
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="${signal.price_change > 0 ? 'price-up' : 'price-down'}">
                                    ${signal.price_change > 0 ? '+' : ''}${signal.price_change_percent ? signal.price_change_percent.toFixed(2) + '%' : '-'}
                                </span>
                            </td>
                            <td>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: ${strength}%" aria-valuenow="${strength}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
        }
        
    } catch (error) {
        console.error('Error fetching trading signals dashboard:', error);
        
        // Show error message
        const errorHtml = `
            <tr>
                <td colspan="4" class="text-center py-3">
                    <div class="alert alert-danger mb-0 py-2">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Error loading signals: ${error.message}
                    </div>
                </td>
            </tr>
        `;
        
        if (bullishTableBody) bullishTableBody.innerHTML = errorHtml;
        if (bearishTableBody) bearishTableBody.innerHTML = errorHtml;
        
        // Generate sample data after showing error
        setTimeout(() => {
            console.log('Generating sample signals data');
            renderSampleSignals(bullishTableBody, bearishTableBody);
            
            if (signalsContainer) {
                renderSampleSignalCards(signalsContainer);
            }
        }, 1500);
    }
}

/**
 * Render dashboard predictions
 */
function renderDashboardPredictions() {
    // Get UI elements
    const loadingEl = document.getElementById('dashboardPredictionLoading');
    const contentEl = document.getElementById('dashboardPredictionContent');
    const signalsContainer = document.getElementById('dashboardPredictionSignals');
    
    console.log('Rendering dashboard predictions:', dashboardPredictionData);
    
    if (!dashboardPredictionData || !dashboardPredictionData.timeframes || Object.keys(dashboardPredictionData.timeframes).length === 0) {
        console.warn('No prediction data available, generating sample data now');
        dashboardPredictionData = generateSamplePredictionData('BBRI');
        console.log('Generated sample data:', dashboardPredictionData);
    }
    
    // Hide loading, show content
    if (loadingEl && contentEl) {
        loadingEl.style.display = 'none';
        contentEl.style.display = 'block';
    }
    
    // Get timeframes data
    const timeframes = dashboardPredictionData.timeframes;
    
    // Prepare data for chart
    const chartOptions = getChartOptions(dashboardPredictionData);
    
    // Initialize or update chart
    if (dashboardPredictionChart) {
        dashboardPredictionChart.updateOptions(chartOptions);
    } else {
        dashboardPredictionChart = new ApexCharts(
            document.getElementById('dashboardPredictionChart'), 
            chartOptions
        );
        dashboardPredictionChart.render();
    }
    
    // Render timeframe signals
    if (signalsContainer) {
        signalsContainer.innerHTML = '';
        
        // Get consensus data
        const consensus = dashboardPredictionData.consensus || {};
        const overallSignal = consensus.overall_signal || 'NEUTRAL';
        const consensusScore = consensus.consensus_score || 0;
        
        // Add overall consensus card
        const consensusColorMap = {
            'BUY': 'success',
            'SELL': 'danger',
            'HOLD': 'warning',
            'NEUTRAL': 'secondary'
        };
        
        const consensusColor = consensusColorMap[overallSignal] || 'secondary';
        
        signalsContainer.innerHTML += `
            <div class="col-12 mb-3">
                <div class="card border-${consensusColor} h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Konsensus Prediksi</h5>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-${consensusColor} me-2">${overallSignal}</span>
                                    <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                        <div class="progress-bar bg-${consensusColor}" role="progressbar" style="width: ${consensusScore * 10}%" aria-valuenow="${consensusScore}" aria-valuemin="0" aria-valuemax="10"></div>
                                    </div>
                                    <span class="ms-2 small">${consensusScore}/10</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <i class="bi bi-graph-up-arrow fs-3 text-${consensusColor}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add cards for each timeframe
        Object.entries(timeframes).forEach(([timeframeKey, timeframeData]) => {
            const tfConfig = dashboardTimeframeMap[timeframeKey] || {
                displayName: timeframeKey,
                chartColor: '#777777',
                icon: 'bi-clock'
            };
            
            const signal = timeframeData.signal || 'NEUTRAL';
            const probability = timeframeData.probability || 0;
            const signalColor = signal === 'BUY' ? 'success' : (signal === 'SELL' ? 'danger' : 'warning');
            
            signalsContainer.innerHTML += `
                <div class="col-md-6 col-lg-4 mb-2">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${tfConfig.displayName}</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-${signalColor} me-2">${signal}</span>
                                        <span class="small">${(probability * 100).toFixed(1)}% probabilitas</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <i class="bi ${tfConfig.icon} fs-4" style="color: ${tfConfig.chartColor}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    }
}

/**
 * Mendapatkan opsi chart untuk prediksi
 * @param {Object} data - Data prediksi
 * @returns {Object} - Chart options
 */
function getChartOptions(data) {
    // Get main prediction data
    const symbol = data.ticker || dashboardCurrentSymbol;
    const historyData = data.history || [];
    const predictionData = data.predictions || [];
    
    // Prepare series data
    const historySeries = historyData.map(point => ({
        x: new Date(point.date).getTime(),
        y: point.close
    }));
    
    const predictionSeries = predictionData.map(point => ({
        x: new Date(point.date).getTime(),
        y: point.predicted_close
    }));
    
    // Chart options
    return {
        series: [
            {
                name: 'Historis',
                data: historySeries,
                color: '#718096'
            },
            {
                name: 'Prediksi',
                data: predictionSeries,
                color: '#4C51BF'
            }
        ],
        chart: {
            type: 'line',
            height: 200,
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        stroke: {
            curve: 'smooth',
            width: [2, 2]
        },
        xaxis: {
            type: 'datetime',
            labels: {
                datetimeUTC: false,
                format: 'dd MMM',
                style: {
                    fontSize: '10px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toFixed(0);
                },
                style: {
                    fontSize: '10px'
                }
            },
            tickAmount: 5
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '12px'
        },
        tooltip: {
            x: {
                format: 'dd MMM yyyy'
            }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: true
                }
            }
        },
        markers: {
            size: 0
        }
    };
}

/**
 * Menampilkan pesan error pada widget prediksi
 * @param {string} message - Pesan error
 */
function showDashboardPredictionError(message) {
    const loadingEl = document.getElementById('dashboardPredictionLoading');
    const contentEl = document.getElementById('dashboardPredictionContent');
    
    if (loadingEl) loadingEl.style.display = 'none';
    
    if (contentEl) {
        contentEl.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i> ${message}
            </div>
        `;
        contentEl.style.display = 'block';
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    initDashboardPredictions();
});

/**
 * Generate sample prediction data when API is not available
 */
function generateSamplePredictionData(symbol) {
    const now = new Date();
    const sampleData = {
        symbol: symbol,
        lastUpdated: now.toISOString(),
        currentPrice: symbol === 'BBRI' ? 5025 : 8750,
        timeframes: {}
    };
    
    // Generate prediction data for each timeframe
    Object.keys(dashboardTimeframeMap).forEach(timeframe => {
        const tf = dashboardTimeframeMap[timeframe];
        const randomChange = (Math.random() * 6) - 2; // Between -2% and +4%
        const direction = randomChange > 0 ? 'bullish' : 'bearish';
        
        sampleData.timeframes[timeframe] = {
            apiValue: tf.apiValue,
            displayName: tf.displayName,
            predictedChange: randomChange,
            predictedDirection: direction,
            confidence: 65 + Math.floor(Math.random() * 20),
            signals: {
                rsi: Math.random() > 0.5 ? 'overbought' : 'neutral',
                macd: Math.random() > 0.5 ? 'bullish' : 'bearish',
                volume: Math.random() > 0.5 ? 'increasing' : 'decreasing'
            }
        };
    });
    
    return sampleData;
}

/**
 * Render sample signals when API fails
 */
function renderSampleSignals(bullishTableBody, bearishTableBody) {
    const bullishSamples = [
        { symbol: 'BBRI', name: 'Bank BRI', price: 'Rp4,250', signal: 'Strong Buy', change: '+2.45%' },
        { symbol: 'ASII', name: 'Astra International', price: 'Rp5,875', signal: 'Buy', change: '+1.67%' },
        { symbol: 'TLKM', name: 'Telkom Indonesia', price: 'Rp3,690', signal: 'Buy', change: '+1.32%' },
        { symbol: 'BBCA', name: 'Bank BCA', price: 'Rp9,725', signal: 'Buy', change: '+0.89%' }
    ];
    
    const bearishSamples = [
        { symbol: 'UNVR', name: 'Unilever Indonesia', price: 'Rp4,120', signal: 'Sell', change: '-1.24%' },
        { symbol: 'HMSP', name: 'HM Sampoerna', price: 'Rp1,505', signal: 'Sell', change: '-1.58%' },
        { symbol: 'INDF', name: 'Indofood', price: 'Rp6,750', signal: 'Strong Sell', change: '-2.17%' },
        { symbol: 'EXCL', name: 'XL Axiata', price: 'Rp2,310', signal: 'Sell', change: '-0.93%' }
    ];
    
    if (bullishTableBody) {
        bullishTableBody.innerHTML = bullishSamples.map(stock => `
            <tr>
                <td><a href="stock-basic.html?symbol=${stock.symbol}" class="fw-bold">${stock.symbol}</a></td>
                <td>${stock.name}</td>
                <td class="text-end">${stock.price}</td>
                <td class="text-end"><span class="badge bg-success">${stock.signal}</span></td>
            </tr>
        `).join('');
    }
    
    if (bearishTableBody) {
        bearishTableBody.innerHTML = bearishSamples.map(stock => `
            <tr>
                <td><a href="stock-basic.html?symbol=${stock.symbol}" class="fw-bold">${stock.symbol}</a></td>
                <td>${stock.name}</td>
                <td class="text-end">${stock.price}</td>
                <td class="text-end"><span class="badge bg-danger">${stock.signal}</span></td>
            </tr>
        `).join('');
    }
}

/**
 * Render sample signal cards when API fails
 */
function renderSampleSignalCards(container) {
    const signals = [
        { timeframe: '1 Jam', direction: 'bullish', confidence: 78 },
        { timeframe: '1 Hari', direction: 'bullish', confidence: 65 },
        { timeframe: '1 Minggu', direction: 'bearish', confidence: 58 }
    ];
    
    container.innerHTML = signals.map(signal => `
        <div class="col-md-4">
            <div class="card h-100 ${signal.direction === 'bullish' ? 'border-success' : 'border-danger'}">
                <div class="card-body p-3 text-center">
                    <h6 class="mb-2">${signal.timeframe}</h6>
                    <div class="d-flex justify-content-center align-items-center mb-2">
                        <i class="bi ${signal.direction === 'bullish' ? 'bi-arrow-up-circle-fill text-success' : 'bi-arrow-down-circle-fill text-danger'} fs-2"></i>
                    </div>
                    <h5 class="${signal.direction === 'bullish' ? 'text-success' : 'text-danger'} text-capitalize">${signal.direction}</h5>
                    <div class="small text-muted">Confidence: ${signal.confidence}%</div>
                </div>
            </div>
        </div>
    `).join('');
}

// Initialize dashboard predictions when DOM is loaded
document.addEventListener('DOMContentLoaded', initDashboardPredictions);
