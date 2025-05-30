// prediction-chart.js - Script untuk visualisasi prediksi AI

function initPredictionChart(containerId, stockSymbol) {
    // Placeholder untuk data prediksi
    const fetchPredictionData = async (symbol, modelType = 'lstm', timeframe = '7d') => {
        // Dalam implementasi asli, ini akan memanggil API backend
        // Untuk contoh, kita gunakan data statis
        
        // Tanggal untuk data historis dan prediksi
        const historicalDates = [
            '2025-05-22', '2025-05-23', '2025-05-24', '2025-05-25', 
            '2025-05-26', '2025-05-27', '2025-05-28', '2025-05-29'
        ];
        
        const futureDates = [
            '2025-05-30', '2025-05-31', '2025-06-01', '2025-06-02', 
            '2025-06-03', '2025-06-04', '2025-06-05'
        ];
        
        // Data historis
        const historicalPrices = [4535, 4545, 4560, 4575, 4585, 4590, 4595, 4580];
        
        // Data prediksi
        const predictedPrices = [4590, 4605, 4620, 4640, 4655, 4675, 4690];
        const upperBound = predictedPrices.map(price => price * 1.03); // 3% di atas prediksi
        const lowerBound = predictedPrices.map(price => price * 0.97); // 3% di bawah prediksi
        
        // Metrik model
        const metrics = {
            mae: 35.42,        // Mean Absolute Error
            mse: 1568.75,      // Mean Squared Error
            rmse: 39.61,       // Root Mean Squared Error
            mape: 0.0082,      // Mean Absolute Percentage Error (0.82%)
            r2: 0.87,          // R-squared
            accuracy: 85.2,    // Akurasi model (%)
            confidence: 92.4   // Confidence level (%)
        };
        
        return {
            historicalDates,
            historicalPrices,
            futureDates,
            predictedPrices,
            upperBound,
            lowerBound,
            metrics,
            modelType,
            generatedAt: new Date().toISOString(),
            stockInfo: {
                ticker: symbol,
                name: symbol === 'BBRI' ? 'Bank Rakyat Indonesia' : 'Unknown Stock',
                lastPrice: historicalPrices[historicalPrices.length - 1],
                predictedPrice: predictedPrices[predictedPrices.length - 1],
                predictedChange: predictedPrices[predictedPrices.length - 1] - historicalPrices[historicalPrices.length - 1],
                predictedChangePercent: ((predictedPrices[predictedPrices.length - 1] / historicalPrices[historicalPrices.length - 1]) - 1) * 100
            }
        };
    };

    // Inisialisasi chart
    const initChart = async () => {
        const predictionData = await fetchPredictionData(stockSymbol);
        
        // Gabungkan data historis dan prediksi untuk visualisasi
        const allDates = [...predictionData.historicalDates, ...predictionData.futureDates];
        
        // Siapkan data untuk ApexCharts
        const historicalData = predictionData.historicalDates.map((date, i) => ({
            x: new Date(date).getTime(),
            y: predictionData.historicalPrices[i]
        }));
        
        const predictionData2 = predictionData.futureDates.map((date, i) => ({
            x: new Date(date).getTime(),
            y: predictionData.predictedPrices[i]
        }));
        
        const upperBoundData = predictionData.futureDates.map((date, i) => ({
            x: new Date(date).getTime(),
            y: predictionData.upperBound[i]
        }));
        
        const lowerBoundData = predictionData.futureDates.map((date, i) => ({
            x: new Date(date).getTime(),
            y: predictionData.lowerBound[i]
        }));
        
        // Tampilkan informasi saham dan prediksi
        displayStockInfo(predictionData.stockInfo);
        
        // Tampilkan metrik model
        displayModelMetrics(predictionData.metrics, predictionData.modelType);
        
        // Hapus loading spinner setelah data siap dirender
        if (typeof removeLoadingSpinner === 'function') {
            removeLoadingSpinner('predictionChart');
        }
        
        // Opsi chart
        const options = {
            series: [
                {
                    name: 'Historical',
                    type: 'line',
                    data: historicalData
                },
                {
                    name: 'Prediction',
                    type: 'line',
                    data: predictionData2
                },
                {
                    name: 'Upper Bound',
                    type: 'line',
                    data: upperBoundData
                },
                {
                    name: 'Lower Bound',
                    type: 'line',
                    data: lowerBoundData
                }
            ],
            chart: {
                height: 400,
                type: 'line',
                toolbar: {
                    show: true
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            stroke: {
                width: [3, 3, 1, 1],
                curve: 'smooth',
                dashArray: [0, 0, 0, 0]
            },
            colors: ['#007bff', '#28a745', '#17a2b8', '#17a2b8'],
            fill: {
                type: ['solid', 'solid', 'gradient', 'gradient'],
                opacity: [1, 1, 0.3, 0.3],
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    opacityFrom: 0.7,
                    opacityTo: 0.2
                }
            },
            markers: {
                size: [0, 5, 0, 0],
                strokeWidth: 0,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    datetimeUTC: false,
                    format: 'dd MMM'
                }
            },
            yaxis: {
                labels: {
                    formatter: (val) => {
                        return val.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                title: {
                    text: 'Price (IDR)'
                }
            },
            tooltip: {
                shared: true,
                x: {
                    format: 'dd MMM yyyy'
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            },
            annotations: {
                xaxis: [{
                    x: new Date(predictionData.historicalDates[predictionData.historicalDates.length - 1]).getTime(),
                    borderColor: '#999',
                    label: {
                        text: 'Prediction Start',
                        style: {
                            color: '#fff',
                            background: '#999'
                        }
                    }
                }]
            }
        };
        
        // Render chart
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    };
    
    // Function untuk menampilkan informasi saham dan prediksi
    const displayStockInfo = (stockInfo) => {
        const stockInfoContainer = document.getElementById('stockInfo');
        if (!stockInfoContainer) return;
        
        const isPriceUp = stockInfo.predictedChange > 0;
        const direction = isPriceUp ? 'up' : 'down';
        const colorClass = isPriceUp ? 'text-success' : 'text-danger';
        const icon = isPriceUp ? 'bi-arrow-up-circle' : 'bi-arrow-down-circle';
        
        stockInfoContainer.innerHTML = `
            <div class="text-center mb-4">
                <div class="prediction-direction ${direction}">
                    <i class="bi ${icon}" style="font-size: 3rem;"></i>
                </div>
                <h3 class="mb-0 ${colorClass}">
                    ${isPriceUp ? 'BULLISH' : 'BEARISH'}
                </h3>
                <p class="text-muted">7-Day Prediction</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Current Price</td>
                            <td class="text-end fw-bold">Rp ${stockInfo.lastPrice.toLocaleString()}</td>
                        </tr>
                        <tr>
                            <td>Predicted Price</td>
                            <td class="text-end fw-bold ${colorClass}">
                                Rp ${stockInfo.predictedPrice.toLocaleString()}
                            </td>
                        </tr>
                        <tr>
                            <td>Potential Return</td>
                            <td class="text-end fw-bold ${colorClass}">
                                ${stockInfo.predictedChangePercent.toFixed(2)}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    };
    
    // Function untuk menampilkan metrik model
    const displayModelMetrics = (metrics, modelType) => {
        const metricsContainer = document.getElementById('modelMetrics');
        if (!metricsContainer) return;
        
        const modelInfo = getModelInfo(modelType);
        
        metricsContainer.innerHTML = `
            <div class="d-flex align-items-center mb-3">
                <div class="model-icon p-2 rounded" style="background-color: ${modelInfo.color}; color: white;">
                    <i class="bi bi-cpu"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">${modelInfo.name}</h6>
                    <div class="d-flex align-items-center">
                        <small class="text-muted me-2">Accuracy:</small>
                        <div class="progress flex-grow-1" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: ${metrics.accuracy}%; background-color: ${modelInfo.color}"></div>
                        </div>
                        <small class="ms-2 fw-bold">${metrics.accuracy}%</small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <h6 class="card-title">MAE (Mean Absolute Error)</h6>
                            <h5 class="card-text">${metrics.mae.toFixed(2)}</h5>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: ${100 - Math.min(metrics.mae / 100 * 100, 100)}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <h6 class="card-title">MAPE</h6>
                            <h5 class="card-text">${(metrics.mape * 100).toFixed(2)}%</h5>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: ${100 - Math.min(metrics.mape * 100 * 20, 100)}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <h6 class="card-title">R² Score</h6>
                            <h5 class="card-text">${metrics.r2.toFixed(2)}</h5>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: ${metrics.r2 * 100}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <h6 class="card-title">Confidence</h6>
                            <h5 class="card-text">${metrics.confidence.toFixed(1)}%</h5>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: ${metrics.confidence}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    };
    
    // Helper function untuk mendapatkan informasi model
    const getModelInfo = (modelType) => {
        switch (modelType) {
            case 'lstm':
                return {
                    name: 'LSTM (Long Short-Term Memory)',
                    description: 'Deep learning model specialized for time series prediction with ability to capture long-term dependencies.',
                    color: '#6f42c1'
                };
            case 'arima':
                return {
                    name: 'ARIMA (AutoRegressive Integrated Moving Average)',
                    description: 'Statistical model that uses time-dependent structures in the data to make forecasts.',
                    color: '#fd7e14'
                };
            case 'prophet':
                return {
                    name: 'Prophet (Facebook)',
                    description: 'Forecasting procedure developed by Facebook that works well with seasonal effects.',
                    color: '#20c997'
                };
            default:
                return {
                    name: 'Unknown Model',
                    description: 'Model information not available',
                    color: '#6c757d'
                };
        }
    };
    
    // Initialize
    initChart();
}
