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
    
    // Fetch top signals for bullish stocks section
    fetchTradingSignalsDashboard();
    
    // Initial load of predictions for default symbol
    fetchDashboardPredictions(dashboardCurrentSymbol);
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
        // Fetch data from multi-timeframe API
        const response = await fetch(`/api/ml/timeframe/predictions/${symbol}`);
        
        if (!response.ok) {
            throw new Error(`Error fetching predictions: ${response.statusText}`);
        }
        
        dashboardPredictionData = await response.json();
        renderDashboardPredictions();
        
    } catch (error) {
        console.error('Error fetching predictions:', error);
        // Show error message
        if (contentEl) {
            contentEl.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> Error loading predictions: ${error.message}
                </div>
            `;
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
    
    if (!bullishTableBody && !bearishTableBody) {
        console.warn('Trading signals table elements not found in DOM');
        return;
    }
    
    // Show loading
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
        const response = await fetch('/api/ml/signals/dashboard');
        
        if (!response.ok) {
            throw new Error(`Error fetching signals dashboard: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        // Update bullish stocks table
        if (bullishTableBody) {
            const buySignals = data.buy_signals || [];
            
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
        
        // Update bearish stocks table
        if (bearishTableBody) {
            const sellSignals = data.sell_signals || [];
            
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
    
    if (!dashboardPredictionData || !dashboardPredictionData.timeframes || Object.keys(dashboardPredictionData.timeframes).length === 0) {
        showDashboardPredictionError('Data prediksi tidak tersedia');
        return;
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
