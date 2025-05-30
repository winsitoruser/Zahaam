// multi-timeframe-prediction.js - Script untuk menampilkan prediksi harga saham berbagai timeframe

// Global variables
let predictionCharts = {
    'hour1': null,
    'hour3': null,
    'hour24': null,
    'day7': null,
    'day30': null
};

let currentPredictions = null;
let currentSymbol = null;

// Timeframe mapping untuk API dan display
const timeframeMap = {
    'hour1': {
        apiValue: '1h',
        displayName: '1 Jam',
        chartColor: '#00C7B1'
    },
    'hour3': {
        apiValue: '3h',
        displayName: '3 Jam',
        chartColor: '#36A2EB'
    },
    'hour24': {
        apiValue: '24h',
        displayName: '24 Jam',
        chartColor: '#4C51BF'
    },
    'day7': {
        apiValue: '7d',
        displayName: '7 Hari',
        chartColor: '#9061F9'
    },
    'day30': {
        apiValue: '30d',
        displayName: '30 Hari',
        chartColor: '#6875F5'
    }
};

// Inisialisasi komponen prediksi multi-timeframe
function initMultiTimeframePrediction() {
    console.log('Initializing Multi-Timeframe Prediction module...');
    
    // Dapatkan simbol saham dari form strategi
    const symbolSelect = document.getElementById('stockSymbol');
    if (symbolSelect) {
        currentSymbol = symbolSelect.value;
        
        // Subscribe ke perubahan simbol
        symbolSelect.addEventListener('change', function() {
            currentSymbol = this.value;
            fetchAllPredictions(currentSymbol);
        });
        
        // Fetch prediksi pertama kali
        fetchAllPredictions(currentSymbol);
    }
    
    // Tombol refresh untuk mengambil data terbaru
    const refreshButton = document.getElementById('refreshPredictions');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            fetchAllPredictions(currentSymbol);
        });
    }
    
    // Setup event listener untuk tab prediksi
    const predictionTab = document.getElementById('prediction-tab');
    if (predictionTab) {
        predictionTab.addEventListener('shown.bs.tab', function() {
            // Refresh charts jika diperlukan
            refreshPredictionCharts();
        });
    }
}

// Fungsi untuk fetch semua prediksi untuk simbol tertentu
async function fetchAllPredictions(symbol) {
    // Tampilkan loading indicator
    const loadingIndicator = document.getElementById('predictionLoadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'block';
    }
    
    // Sembunyikan chart containers
    document.querySelectorAll('.tab-pane .chart-container').forEach(container => {
        container.style.visibility = 'hidden';
    });
    
    try {
        // Request ke API backend
        const response = await fetch(`/api/ml/timeframe/predictions/${symbol}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error: ${response.status}`);
        }
        
        const data = await response.json();
        currentPredictions = data;
        
        // Render prediksi untuk setiap timeframe
        renderAllPredictions(data);
        
        // Update tabel perbandingan
        updatePredictionComparisonTable(data);
        
    } catch (error) {
        console.error('Error fetching predictions:', error);
        showErrorMessage('Gagal mengambil data prediksi');
    } finally {
        // Sembunyikan loading indicator
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
        }
        
        // Tampilkan chart containers
        document.querySelectorAll('.tab-pane .chart-container').forEach(container => {
            container.style.visibility = 'visible';
        });
    }
}

// Fungsi untuk render semua prediksi
function renderAllPredictions(data) {
    if (!data || !data.predictions) {
        showErrorMessage('Data prediksi tidak valid');
        return;
    }
    
    // Render chart untuk setiap timeframe
    for (const timeframeKey in timeframeMap) {
        const apiTimeframe = timeframeMap[timeframeKey].apiValue;
        const prediction = data.predictions.find(p => p.timeframe === apiTimeframe);
        
        if (prediction) {
            renderTimeframeChart(timeframeKey, prediction);
            displayPredictionMetrics(timeframeKey, prediction);
        }
    }
}

// Fungsi untuk render chart prediksi timeframe tertentu
function renderTimeframeChart(timeframeKey, prediction) {
    const chartId = `${timeframeKey}-chart`;
    const chartContainer = document.getElementById(chartId);
    
    if (!chartContainer) {
        console.error(`Chart container ${chartId} not found`);
        return;
    }
    
    // Destroy existing chart if any
    if (predictionCharts[timeframeKey]) {
        predictionCharts[timeframeKey].destroy();
    }
    
    // Prepare data for chart
    const historicalDates = prediction.historical_data.map(d => new Date(d.date));
    const historicalPrices = prediction.historical_data.map(d => d.close);
    
    const futureDates = prediction.prediction_dates.map(d => new Date(d));
    const predictedPrices = prediction.predicted_values;
    const upperBound = prediction.upper_bound || predictedPrices.map(p => p * 1.02); // 2% di atas jika tidak ada
    const lowerBound = prediction.lower_bound || predictedPrices.map(p => p * 0.98); // 2% di bawah jika tidak ada
    
    // Find the split point (last historical date)
    const splitPoint = historicalDates[historicalDates.length - 1];
    
    // Chart color from timeframe map
    const chartColor = timeframeMap[timeframeKey].chartColor;
    
    // Create the ApexChart
    const options = {
        series: [
            {
                name: 'Historical',
                type: 'line',
                data: historicalPrices.map((price, index) => ({
                    x: historicalDates[index],
                    y: price
                }))
            },
            {
                name: 'Prediction',
                type: 'line',
                data: predictedPrices.map((price, index) => ({
                    x: futureDates[index],
                    y: price
                })),
                dashArray: 5
            },
            {
                name: 'Upper Bound',
                type: 'area',
                data: upperBound.map((price, index) => ({
                    x: futureDates[index],
                    y: price
                }))
            },
            {
                name: 'Lower Bound',
                type: 'area',
                data: lowerBound.map((price, index) => ({
                    x: futureDates[index],
                    y: price
                }))
            }
        ],
        chart: {
            height: 300,
            type: 'line',
            animations: {
                enabled: false
            },
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: false,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                }
            }
        },
        colors: [
            '#718096', // Historical
            chartColor, // Prediction
            'transparent', // Upper bound (area fill will be set by fill gradient)
            'transparent'  // Lower bound (area fill will be set by fill gradient)
        ],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: [2, 2, 0, 0],
            dashArray: [0, 5, 0, 0]
        },
        fill: {
            type: ['solid', 'solid', 'gradient', 'gradient'],
            gradient: {
                shade: 'light',
                type: 'vertical',
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        xaxis: {
            type: 'datetime',
            labels: {
                datetimeUTC: false,
                format: 'dd MMM HH:mm'
            }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return Math.round(value).toLocaleString();
                }
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            }
        },
        legend: {
            show: true,
            position: 'top'
        },
        annotations: {
            xaxis: [
                {
                    x: splitPoint.getTime(),
                    borderColor: '#FF4560',
                    strokeDashArray: 0,
                    label: {
                        borderColor: '#FF4560',
                        style: {
                            color: '#fff',
                            background: '#FF4560',
                        },
                        text: 'Saat ini',
                        orientation: 'horizontal'
                    }
                }
            ]
        }
    };
    
    // Create area fill between upper and lower bounds
    options.fill.opacity = [1, 1, 0.3, 0.3];
    
    // Create chart instance
    predictionCharts[timeframeKey] = new ApexCharts(chartContainer, options);
    predictionCharts[timeframeKey].render();
}

// Fungsi untuk menampilkan metrik prediksi
function displayPredictionMetrics(timeframeKey, prediction) {
    const metricsContainer = document.getElementById(`${timeframeKey}-metrics`);
    if (!metricsContainer) return;
    
    const currentPrice = prediction.historical_data[prediction.historical_data.length - 1].close;
    const predictedPrice = prediction.predicted_values[prediction.predicted_values.length - 1];
    const priceChange = predictedPrice - currentPrice;
    const percentChange = (priceChange / currentPrice) * 100;
    
    // Get trading signal
    const signal = prediction.signal || 'HOLD';
    const signalStrength = prediction.signal_strength || 0.5;
    const confidence = prediction.confidence || 85;
    
    // Signal color
    let signalColor, signalBgColor, signalIcon;
    switch (signal) {
        case 'BUY':
            signalColor = 'success';
            signalBgColor = 'rgba(0, 200, 81, 0.1)';
            signalIcon = 'bi-arrow-up-circle-fill';
            break;
        case 'SELL':
            signalColor = 'danger';
            signalBgColor = 'rgba(234, 84, 85, 0.1)';
            signalIcon = 'bi-arrow-down-circle-fill';
            break;
        default:
            signalColor = 'warning';
            signalBgColor = 'rgba(255, 159, 67, 0.1)';
            signalIcon = 'bi-dash-circle-fill';
    }
    
    // Create metrics HTML
    metricsContainer.innerHTML = `
        <div class="col-md-3 mb-2">
            <div class="p-3 rounded" style="background-color: rgba(99, 102, 241, 0.1);">
                <h6 class="mb-1 small">Prediksi Harga</h6>
                <h5 class="mb-0">${predictedPrice.toLocaleString()}</h5>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="p-3 rounded" style="background-color: ${percentChange >= 0 ? 'rgba(0, 200, 81, 0.1)' : 'rgba(234, 84, 85, 0.1)'};">
                <h6 class="mb-1 small">Perubahan</h6>
                <h5 class="mb-0 ${percentChange >= 0 ? 'text-success' : 'text-danger'}">
                    ${percentChange >= 0 ? '+' : ''}${percentChange.toFixed(2)}%
                </h5>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="p-3 rounded" style="background-color: ${signalBgColor};">
                <h6 class="mb-1 small">Signal</h6>
                <h5 class="mb-0 text-${signalColor}">
                    <i class="bi ${signalIcon} me-1"></i> ${signal}
                </h5>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="p-3 rounded" style="background-color: rgba(99, 102, 241, 0.1);">
                <h6 class="mb-1 small">Confidence</h6>
                <div class="d-flex align-items-center">
                    <div class="progress flex-grow-1" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: ${confidence}%;" 
                            aria-valuenow="${confidence}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="ms-2 fw-medium">${confidence}%</span>
                </div>
            </div>
        </div>
    `;
}

// Fungsi untuk update tabel perbandingan prediksi
function updatePredictionComparisonTable(data) {
    const tableBody = document.querySelector('#prediction-comparison-table tbody');
    if (!tableBody) return;
    
    // Clear existing rows
    tableBody.innerHTML = '';
    
    if (!data || !data.predictions) return;
    
    // Get current price from first prediction's historical data
    const currentPrice = data.predictions[0]?.historical_data[data.predictions[0].historical_data.length - 1].close || 0;
    
    // Create rows for each timeframe
    for (const timeframeKey in timeframeMap) {
        const apiTimeframe = timeframeMap[timeframeKey].apiValue;
        const displayName = timeframeMap[timeframeKey].displayName;
        
        const prediction = data.predictions.find(p => p.timeframe === apiTimeframe);
        
        if (prediction) {
            const predictedPrice = prediction.predicted_values[prediction.predicted_values.length - 1];
            const priceChange = predictedPrice - currentPrice;
            const percentChange = (priceChange / currentPrice) * 100;
            const signal = prediction.signal || 'HOLD';
            const confidence = prediction.confidence || 85;
            
            // Signal styling
            let signalClass;
            switch (signal) {
                case 'BUY': signalClass = 'success'; break;
                case 'SELL': signalClass = 'danger'; break;
                default: signalClass = 'warning';
            }
            
            // Create table row
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${displayName}</td>
                <td>${predictedPrice.toLocaleString()}</td>
                <td class="text-${percentChange >= 0 ? 'success' : 'danger'}">
                    ${percentChange >= 0 ? '+' : ''}${percentChange.toFixed(2)}%
                </td>
                <td>
                    <span class="badge bg-${signalClass} rounded-pill">${signal}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: ${confidence}%;" 
                                aria-valuenow="${confidence}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="ms-2 small">${confidence}%</span>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        }
    }
}

// Fungsi untuk refresh semua chart (ketika tab aktif)
function refreshPredictionCharts() {
    if (!currentPredictions) return;
    
    for (const timeframeKey in predictionCharts) {
        const chart = predictionCharts[timeframeKey];
        if (chart) {
            chart.render();
        }
    }
}

// Fungsi untuk menampilkan pesan error
function showErrorMessage(message) {
    console.error(message);
    
    // Sembunyikan loading
    const loadingIndicator = document.getElementById('predictionLoadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
    }
    
    // Tampilkan error di setiap tab prediksi
    for (const timeframeKey in timeframeMap) {
        const contentEl = document.getElementById(`${timeframeKey}-content`);
        if (contentEl) {
            const chartContainer = contentEl.querySelector('.chart-container');
            if (chartContainer) {
                chartContainer.innerHTML = `
                    <div class="alert alert-danger text-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        ${message}
                    </div>
                `;
            }
        }
    }
}

// Inisialisasi ketika dokumen siap
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded for multi-timeframe predictions');
    initMultiTimeframePrediction();
});
