// strategy-backtest.js - Script untuk membuat dan menguji strategi trading serta tools trading lainnya

// Global variables
let stockChart = null;
let currentChartType = 'candlestick';
let chartData = [];

// Function to render strategy chart with selected type (ultra-lightweight version)
const renderStrategyChart = (symbol = 'BBRI', chartType = 'candlestick') => {
    console.log(`Rendering chart: Symbol=${symbol}, Type=${chartType}`);
    const chartContainer = document.getElementById('chartContainer');
    if (!chartContainer) {
        console.error('Chart container not found');
        return;
    }
    
    // Clear loading indicator if exists
    const loadingIndicator = document.getElementById('chartLoadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
    }
    
    // Show loading
    chartContainer.innerHTML = '<div class="spinner-zahaam"></div>';
    
    // Set current chart type
    currentChartType = chartType;
    
    // Generate sample data if needed - using VERY few data points for extremely fast rendering
    if (chartData.length === 0) {
        chartData = generateSampleOHLC(symbol, 30); // Drastically reduced to just 30 data points
    }
    
    // Format data for ApexCharts (using most efficient approach possible)
    let seriesData;
    
    // For candlestick/OHLC, need full OHLC data
    if (chartType === 'candlestick' || chartType === 'ohlc') {
        seriesData = [];
        // Only use every other data point to reduce rendering burden
        for (let i = 0; i < chartData.length; i += 2) {
            const item = chartData[i];
            seriesData.push({
                x: new Date(item.date),
                y: [item.open, item.high, item.low, item.close]
            });
        }
    } else {
        // For line/area charts, only need closing prices which is much faster to render
        seriesData = [];
        for (let i = 0; i < chartData.length; i += 1) {
            const item = chartData[i];
            seriesData.push({
                x: new Date(item.date),
                y: item.close // Just the close price for line/area charts
            });
        }
    }
    
    // Configure chart options - ultra minimalist version
    const chartOptions = {
        series: [{
            name: 'Price',
            type: chartType,
            data: seriesData
        }],
        chart: {
            height: 400, // Reduced height
            type: chartType,
            toolbar: {
                show: true,
                tools: {
                    download: false, // Disable download
                    selection: true,
                    zoom: true,
                    zoomin: false, // Disable zoomin
                    zoomout: false, // Disable zoomout
                    pan: true,
                    reset: true
                }
            },
            animations: {
                enabled: false
            },
            redrawOnWindowResize: false,
            redrawOnParentResize: false,
            fontFamily: 'inherit', // Use inherited font to avoid loading new fonts
            defaultLocale: 'en', // Use simplified locale
            sparkline: { enabled: false },
            brush: { enabled: false },
            stacked: false,
            events: {} // Disable all events
        },
        title: {
            text: `${symbol} (${chartType.toUpperCase()})`, // Shorter title
            align: 'left'
        },
        xaxis: {
            type: 'datetime',
            labels: {
                datetimeUTC: false,
                format: 'dd MMM',
                style: { fontFamily: 'inherit' } // Use inherited font
            },
            axisBorder: { show: false }, // Hide border
            axisTicks: { show: false }  // Hide ticks
        },
        yaxis: {
            show: true,
            tickAmount: 4, // Fewer ticks
            labels: {
                formatter: function(val) {
                    return Math.round(val); // Simpler formatting
                },
                style: { fontFamily: 'inherit' }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        tooltip: {
            enabled: true,
            followCursor: false, // Disable cursor following for better performance
            intersect: true,
            x: {
                format: 'dd MMM'
            },
            theme: 'light',
            style: { fontFamily: 'inherit' }
        },
        grid: {
            show: false // Hide grid
        },
        stroke: {
            show: true,
            width: chartType === 'line' || chartType === 'area' ? 2 : 1,
            curve: 'smooth'
        },
        plotOptions: {
            candlestick: {
                colors: {
                    upward: '#26a69a',
                    downward: '#ef5350'
                },
                wick: {
                    useFillColor: true
                }
            },
            bar: {
                columnWidth: '80%'
            }
        },
        states: {
            hover: { filter: { type: 'none' } }, // Disable hover effects
            active: { filter: { type: 'none' } }  // Disable active effects
        },
        fill: {
            opacity: chartType === 'area' ? 0.2 : 1 // Lower opacity for area
        }
    };
    
    // If chart already exists, destroy it
    if (stockChart) {
        console.log('Destroying existing chart');
        stockChart.destroy();
    }
    
    // Create new chart
    stockChart = new ApexCharts(chartContainer, chartOptions);
    stockChart.render();
    
    // Add volume series if checkbox is checked
    if (document.getElementById('showVolume') && document.getElementById('showVolume').checked) {
        addVolumeToChart();
    }
    
    return stockChart;
};

// Function to add volume to chart
const addVolumeToChart = () => {
    if (!stockChart) return;
    
    const volumeData = chartData.map(item => ({
        x: new Date(item.date),
        y: item.volume
    }));
    
    stockChart.appendSeries({
        name: 'Volume',
        type: 'bar',
        data: volumeData
    });
};

// Helper function to calculate SMA
const calculateSMA = (data, period) => {
    const result = [];
    for (let i = period - 1; i < data.length; i++) {
        const sum = data.slice(i - period + 1, i + 1).reduce((a, b) => a + b, 0);
        result.push(sum / period);
    }
    return result;
};

// Function to add SMA to chart
const addSMAToChart = (period = 20) => {
    if (!stockChart || chartData.length === 0) return;
    
    const closePrices = chartData.map(item => item.close);
    const sma = calculateSMA(closePrices, period);
    
    // Skip the first (period-1) days that don't have SMA values
    const smaData = sma.map((value, index) => ({
        x: new Date(chartData[index + period - 1].date),
        y: value
    }));
    
    stockChart.appendSeries({
        name: `SMA (${period})`,
        type: 'line',
        data: smaData
    });
};

function initStrategyBacktest(strategyFormId, backtestResultsId) {
    // Definisi strategi default
    const defaultStrategy = {
        name: "Moving Average Crossover",
        description: "Buy when fast MA crosses above slow MA, sell when it crosses below",
        parameters: {
            fastPeriod: 20,
            slowPeriod: 50,
            stopLoss: 2.5,
            takeProfit: 5
        },
        type: "MA Crossover"
    };
    
    // Placeholder untuk data historis saham
    const fetchHistoricalData = async (symbol, timeframe = '1Y') => {
        // Dalam implementasi asli, ini akan memanggil API backend
        // Untuk contoh, kita gunakan data statis (200 hari)
        const data = [];
        let price = 4500;
        const startDate = new Date('2024-11-01');
        
        for (let i = 0; i < 200; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            
            // Simulasi pergerakan harga dengan random walk
            const change = (Math.random() - 0.48) * 50; // Slightly biased upward
            price += change;
            
            // Pastikan harga tidak negatif
            price = Math.max(price, 1000);
            
            // Generate OHLC data
            const open = price;
            const high = price + Math.random() * 30;
            const low = price - Math.random() * 30;
            const close = price + (Math.random() - 0.5) * 20;
            
            // Volume
            const volume = Math.floor(Math.random() * 50000000) + 10000000;
            
            data.push({
                date: date.toISOString().split('T')[0],
                open,
                high,
                low,
                close,
                volume
            });
        }
        
        return data;
    };
    
    // Function untuk menjalankan backtest
    const runBacktest = async (symbol, strategy) => {
        // Dapatkan data historis
        const historicalData = await fetchHistoricalData(symbol);
        
        // Hitung indikator sesuai strategi
        const processedData = calculateIndicators(historicalData, strategy);
        
        // Jalankan backtest berdasarkan indikator dan parameter
        const backtestResult = executeBacktest(processedData, strategy);
        
        // Tampilkan hasil backtest
        displayBacktestResults(backtestResult, backtestResultsId);
        
        return backtestResult;
    };
    
    // Hitung indikator teknikal
    const calculateIndicators = (data, strategy) => {
        // Salin data
        const processedData = JSON.parse(JSON.stringify(data));
        
        // Hitung indikator sesuai jenis strategi
        if (strategy.type === "MA Crossover") {
            // Hitung moving average cepat
            for (let i = 0; i < processedData.length; i++) {
                if (i < strategy.parameters.fastPeriod - 1) {
                    processedData[i].fastMA = null;
                } else {
                    let sum = 0;
                    for (let j = 0; j < strategy.parameters.fastPeriod; j++) {
                        sum += processedData[i - j].close;
                    }
                    processedData[i].fastMA = sum / strategy.parameters.fastPeriod;
                }
            }
            
            // Hitung moving average lambat
            for (let i = 0; i < processedData.length; i++) {
                if (i < strategy.parameters.slowPeriod - 1) {
                    processedData[i].slowMA = null;
                } else {
                    let sum = 0;
                    for (let j = 0; j < strategy.parameters.slowPeriod; j++) {
                        sum += processedData[i - j].close;
                    }
                    processedData[i].slowMA = sum / strategy.parameters.slowPeriod;
                }
            }
            
            // Tentukan sinyal berdasarkan crossover
            for (let i = 1; i < processedData.length; i++) {
                if (
                    processedData[i].fastMA && 
                    processedData[i].slowMA && 
                    processedData[i-1].fastMA && 
                    processedData[i-1].slowMA
                ) {
                    // Buy signal - fast MA crosses above slow MA
                    if (
                        processedData[i-1].fastMA <= processedData[i-1].slowMA && 
                        processedData[i].fastMA > processedData[i].slowMA
                    ) {
                        processedData[i].signal = 'BUY';
                    }
                    // Sell signal - fast MA crosses below slow MA
                    else if (
                        processedData[i-1].fastMA >= processedData[i-1].slowMA && 
                        processedData[i].fastMA < processedData[i].slowMA
                    ) {
                        processedData[i].signal = 'SELL';
                    } else {
                        processedData[i].signal = null;
                    }
                } else {
                    processedData[i].signal = null;
                }
            }
        } else if (strategy.type === "RSI") {
            // Implementasi RSI (simplified)
            // Hitung perubahan harga
            for (let i = 1; i < processedData.length; i++) {
                processedData[i].priceChange = processedData[i].close - processedData[i-1].close;
            }
            
            // Hitung RSI
            const period = strategy.parameters.rsiPeriod || 14;
            for (let i = period; i < processedData.length; i++) {
                let gains = 0;
                let losses = 0;
                
                for (let j = i - period + 1; j <= i; j++) {
                    if (processedData[j].priceChange > 0) {
                        gains += processedData[j].priceChange;
                    } else {
                        losses -= processedData[j].priceChange;
                    }
                }
                
                const avgGain = gains / period;
                const avgLoss = losses / period;
                
                if (avgLoss === 0) {
                    processedData[i].rsi = 100;
                } else {
                    const rs = avgGain / avgLoss;
                    processedData[i].rsi = 100 - (100 / (1 + rs));
                }
                
                // Tentukan sinyal berdasarkan RSI
                if (processedData[i].rsi < strategy.parameters.oversold) {
                    processedData[i].signal = 'BUY';
                } else if (processedData[i].rsi > strategy.parameters.overbought) {
                    processedData[i].signal = 'SELL';
                } else {
                    processedData[i].signal = null;
                }
            }
        }
        
        return processedData;
    };
    
    // Jalankan backtest berdasarkan sinyal
    const executeBacktest = (data, strategy) => {
        // Inisialisasi variabel backtest
        let initialCapital = 10000000; // Rp 10 juta
        let capital = initialCapital;
        let position = null;
        let entryPrice = 0;
        let trades = [];
        let equity = [initialCapital];
        
        // Eksekusi backtest
        for (let i = 1; i < data.length; i++) {
            // Update equity history
            equity.push(position ? capital + position * data[i].close : capital);
            
            // Check untuk stop loss dan take profit jika dalam posisi
            if (position) {
                const currentPrice = data[i].close;
                const percentChange = (currentPrice - entryPrice) / entryPrice * 100;
                
                // Stop loss
                if (percentChange < -strategy.parameters.stopLoss) {
                    // Sell position
                    capital = capital + position * currentPrice;
                    trades.push({
                        type: 'SELL',
                        reason: 'Stop Loss',
                        date: data[i].date,
                        price: currentPrice,
                        shares: position,
                        pnl: position * (currentPrice - entryPrice),
                        pnlPercent: percentChange
                    });
                    position = null;
                    continue;
                }
                
                // Take profit
                if (percentChange > strategy.parameters.takeProfit) {
                    // Sell position
                    capital = capital + position * currentPrice;
                    trades.push({
                        type: 'SELL',
                        reason: 'Take Profit',
                        date: data[i].date,
                        price: currentPrice,
                        shares: position,
                        pnl: position * (currentPrice - entryPrice),
                        pnlPercent: percentChange
                    });
                    position = null;
                    continue;
                }
            }
            
            // Process trading signals
            if (data[i].signal === 'BUY' && !position) {
                // Calculate position size
                const positionSize = capital * 0.95; // Use 95% of capital
                position = positionSize / data[i].close;
                entryPrice = data[i].close;
                capital = capital - positionSize;
                
                trades.push({
                    type: 'BUY',
                    reason: 'Signal',
                    date: data[i].date,
                    price: data[i].close,
                    shares: position
                });
            } 
            else if (data[i].signal === 'SELL' && position) {
                // Sell position
                capital = capital + position * data[i].close;
                const percentChange = (data[i].close - entryPrice) / entryPrice * 100;
                
                trades.push({
                    type: 'SELL',
                    reason: 'Signal',
                    date: data[i].date,
                    price: data[i].close,
                    shares: position,
                    pnl: position * (data[i].close - entryPrice),
                    pnlPercent: percentChange
                });
                
                position = null;
            }
        }
        
        // Likuidasi posisi di akhir periode
        if (position) {
            const lastPrice = data[data.length - 1].close;
            capital = capital + position * lastPrice;
            const percentChange = (lastPrice - entryPrice) / entryPrice * 100;
            
            trades.push({
                type: 'SELL',
                reason: 'End of Period',
                date: data[data.length - 1].date,
                price: lastPrice,
                shares: position,
                pnl: position * (lastPrice - entryPrice),
                pnlPercent: percentChange
            });
        }
        
        // Hitung metrik performa
        const finalCapital = capital;
        const totalReturn = (finalCapital - initialCapital) / initialCapital * 100;
        
        // Hitung win rate
        let winCount = 0;
        const sellTrades = trades.filter(trade => trade.type === 'SELL');
        for (const trade of sellTrades) {
            if (trade.pnl > 0) winCount++;
        }
        const winRate = sellTrades.length > 0 ? (winCount / sellTrades.length) * 100 : 0;
        
        // Hitung max drawdown
        let maxDrawdown = 0;
        let peak = equity[0];
        
        for (let i = 1; i < equity.length; i++) {
            if (equity[i] > peak) {
                peak = equity[i];
            } else {
                const drawdown = (peak - equity[i]) / peak * 100;
                maxDrawdown = Math.max(maxDrawdown, drawdown);
            }
        }
        
        return {
            initialCapital,
            finalCapital,
            totalReturn,
            winRate,
            maxDrawdown,
            trades,
            equity,
            dates: data.map(d => d.date)
        };
    };
    
    // Tampilkan hasil backtest
    const displayBacktestResults = (results, containerId) => {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        // Format angka uang
        const formatCurrency = (value) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value);
        };
        
        // HTML untuk ringkasan backtest
        let html = `
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Backtest Results</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Initial Capital</label>
                                <h5>${formatCurrency(results.initialCapital)}</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Final Capital</label>
                                <h5>${formatCurrency(results.finalCapital)}</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Return</label>
                                <h5 class="${results.totalReturn >= 0 ? 'text-success' : 'text-danger'}">
                                    ${results.totalReturn.toFixed(2)}%
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Win Rate</label>
                                <h5>${results.winRate.toFixed(2)}%</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Max Drawdown</label>
                                <h5 class="text-danger">${results.maxDrawdown.toFixed(2)}%</h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Trades</label>
                                <h5>${results.trades.length}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Equity Curve</h5>
                </div>
                <div class="card-body">
                    <div id="equityCurveChart" style="height: 300px;"></div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Trade History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Reason</th>
                                    <th>Price</th>
                                    <th>Shares</th>
                                    <th>P&L</th>
                                    <th>P&L %</th>
                                </tr>
                            </thead>
                            <tbody>
        `;
        
        // Tambahkan baris tabel untuk setiap trade
        for (const trade of results.trades) {
            const pnlClass = trade.type === 'BUY' ? '' : (trade.pnl >= 0 ? 'text-success' : 'text-danger');
            
            html += `
                <tr>
                    <td>${trade.date}</td>
                    <td>${trade.type}</td>
                    <td>${trade.reason}</td>
                    <td>${trade.price.toFixed(2)}</td>
                    <td>${trade.shares.toFixed(2)}</td>
                    <td class="${pnlClass}">${trade.pnl ? formatCurrency(trade.pnl) : '-'}</td>
                    <td class="${pnlClass}">${trade.pnlPercent ? trade.pnlPercent.toFixed(2) + '%' : '-'}</td>
                </tr>
            `;
        }
        
        html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = html;
        
        // Render equity curve chart
        const equityCurveOptions = {
            series: [{
                name: 'Equity',
                data: results.equity.map((value, index) => ({
                    x: new Date(results.dates[Math.min(index, results.dates.length - 1)]).getTime(),
                    y: value
                }))
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            colors: ['#28a745'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                type: 'datetime'
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return formatCurrency(val).replace('Rp', '');
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return formatCurrency(val);
                    }
                }
            }
        };
        
        const equityCurveChart = new ApexCharts(document.querySelector('#equityCurveChart'), equityCurveOptions);
        equityCurveChart.render();
    };
    
    // Setup event listeners for form
    const setupStrategyForm = () => {
        const form = document.getElementById(strategyFormId);
        if (!form) return;
        
        // Populate form with default strategy
        form.querySelector('#strategyName').value = defaultStrategy.name;
        form.querySelector('#strategyDescription').value = defaultStrategy.description;
        form.querySelector('#strategyType').value = defaultStrategy.type;
        form.querySelector('#fastPeriod').value = defaultStrategy.parameters.fastPeriod;
        form.querySelector('#slowPeriod').value = defaultStrategy.parameters.slowPeriod;
        form.querySelector('#stopLoss').value = defaultStrategy.parameters.stopLoss;
        form.querySelector('#takeProfit').value = defaultStrategy.parameters.takeProfit;
        
        // Listen for form submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Get form values
            const strategyName = form.querySelector('#strategyName').value;
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed(0);
                }
                    const ema20 = calculateEMA(closePrices, 20);
                    
                    chart.appendSeries({
                        name: 'EMA 20',
                        type: 'line',
                        data: ema20.map((val, i) => ({
                            x: new Date(data[i + 19].date),
                            y: val
                        }))
                    });
                } else {
                    chart.hideSeries('EMA 20');
                }
            },
            // Other indicators would be implemented similarly
        };
        
        // Add event listeners to indicator checkboxes
        Object.keys(indicators).forEach(id => {
            const checkbox = document.getElementById(id);
            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    indicators[id](chart, this.checked);
                });
                
                // Initialize if checked by default
                if (checkbox.checked) {
                    indicators[id](chart, true);
                }
            }
        });
    });
};

// ==================
// Trading Tools Functions
// ==================
const initTradingTools = () => {
    // 1. Fibonacci Retracement Calculator
    const calcFiboBtn = document.getElementById('calcFibo');
    if (calcFiboBtn) {
        calcFiboBtn.addEventListener('click', function() {
            const high = parseFloat(document.getElementById('fiboHigh').value);
            const low = parseFloat(document.getElementById('fiboLow').value);
            
            if (isNaN(high) || isNaN(low) || high <= low) {
                document.getElementById('fiboResults').innerHTML = 
                    '<div class="alert alert-danger">Please enter valid high and low values (high must be greater than low)</div>';
                return;
            }
            
            const diff = high - low;
            const levels = [
                {level: 0, value: high},
                {level: 0.236, value: high - (diff * 0.236)},
                {level: 0.382, value: high - (diff * 0.382)},
                {level: 0.5, value: high - (diff * 0.5)},
                {level: 0.618, value: high - (diff * 0.618)},
                {level: 0.786, value: high - (diff * 0.786)},
                {level: 1, value: low}
            ];
            
            let html = `
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Fibonacci Level</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            levels.forEach(level => {
                html += `
                    <tr>
                        <td>${level.level}</td>
                        <td>${level.value.toFixed(2)}</td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
            
            document.getElementById('fiboResults').innerHTML = html;
        });
    }
    
    // 2. Position Size Calculator
    const calcPositionBtn = document.getElementById('calcPosition');
    if (calcPositionBtn) {
        calcPositionBtn.addEventListener('click', function() {
            const accountSize = parseFloat(document.getElementById('accountSize').value);
            const riskPercent = parseFloat(document.getElementById('riskPercent').value);
            const stopLossPoints = parseFloat(document.getElementById('stopLossPoints').value);
            
            if (isNaN(accountSize) || isNaN(riskPercent) || isNaN(stopLossPoints) || accountSize <= 0 || riskPercent <= 0 || stopLossPoints <= 0) {
                document.getElementById('positionResults').innerHTML = 
                    '<div class="alert alert-danger">Please enter valid values</div>';
                return;
            }
            
            const riskAmount = accountSize * (riskPercent / 100);
            const positionSize = Math.floor(riskAmount / stopLossPoints);
            
            const html = `
                <div class="alert alert-info">
                    <p><strong>Risk Amount:</strong> Rp ${riskAmount.toLocaleString()}</p>
                    <p><strong>Position Size:</strong> ${positionSize} shares</p>
                </div>
            `;
            
            document.getElementById('positionResults').innerHTML = html;
        });
    }
    
    // 3. Risk/Reward Calculator
    const calcRiskRewardBtn = document.getElementById('calcRiskReward');
    if (calcRiskRewardBtn) {
        calcRiskRewardBtn.addEventListener('click', function() {
            const entryPrice = parseFloat(document.getElementById('entryPrice').value);
            const stopPrice = parseFloat(document.getElementById('stopPrice').value);
            const targetPrice = parseFloat(document.getElementById('targetPrice').value);
            
            if (isNaN(entryPrice) || isNaN(stopPrice) || isNaN(targetPrice) || entryPrice <= 0 || stopPrice <= 0 || targetPrice <= 0) {
                document.getElementById('riskRewardResults').innerHTML = 
                    '<div class="alert alert-danger">Please enter valid prices</div>';
                return;
            }
            
            // Calculate values for long position
            const isLong = targetPrice > entryPrice;
            const risk = isLong ? entryPrice - stopPrice : stopPrice - entryPrice;
            const reward = isLong ? targetPrice - entryPrice : entryPrice - targetPrice;
            const ratio = (reward / risk).toFixed(2);
            
            let positionType = isLong ? 'Long' : 'Short';
            let riskPercent = ((risk / entryPrice) * 100).toFixed(2);
            let rewardPercent = ((reward / entryPrice) * 100).toFixed(2);
            
            let colorClass = ratio >= 2 ? 'success' : (ratio >= 1 ? 'warning' : 'danger');
            
            const html = `
                <div class="alert alert-${colorClass}">
                    <p><strong>Position Type:</strong> ${positionType}</p>
                    <p><strong>Risk:</strong> ${risk.toFixed(2)} (${riskPercent}%)</p>
                    <p><strong>Reward:</strong> ${reward.toFixed(2)} (${rewardPercent}%)</p>
                    <p><strong>Risk/Reward Ratio:</strong> 1:${ratio}</p>
                </div>
            `;
            
            document.getElementById('riskRewardResults').innerHTML = html;
        });
    }
};

// Helper function to calculate EMA
const calculateEMA = (data, period) => {
    if (data.length < period) return [];
    
    const k = 2 / (period + 1);
    let ema = [data[0]];
    
    for (let i = period; i < data.length; i++) {
    }
    
    return ema;
}

// Helper function to generate sample OHLC data for testing (ultra lightweight version)
const generateSampleOHLC = (symbol, days = 30) => { // Drastically reduced default days
    const data = [];
    
    // Start date (days ago from today)
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - days);
    
    // Fixed initial prices - simplified
    const prices = { 'BBRI': 5200, 'BBCA': 9400, 'TLKM': 3700, 'ASII': 5800, 'UNVR': 4500 };
    let price = prices[symbol] || 5000;
    
    // Generate data for each day (ultra simplified)
    for (let i = 0; i < days; i++) {
        const date = new Date(startDate);
        date.setDate(date.getDate() + i);
        
        // Skip weekends
        const day = date.getDay();
        if (day === 0 || day === 6) continue;
        
        // Simplified price movement
        const change = (Math.random() - 0.5) * (price * 0.01);
        price += change;
        
        // Generate simplified OHLC data
        const open = price;
        const high = price * 1.005;
        const low = price * 0.995;
        const close = price + (Math.random() - 0.5) * (price * 0.005);
        const volume = Math.floor(Math.random() * 10000000) + 1000000;
        
        data.push({
            date: date.toISOString().split('T')[0],
            open,
            high,
            low,
            close,
            volume
        });
    }
    
    return data;
};

// Initialize charts and tools when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing chart...');
    
    // Pre-generate data for common symbols to improve first-load speed - ultra lightweight version
    const commonSymbols = ['BBRI']; // Only preload the default symbol
    const dataCache = {};
    dataCache['BBRI'] = generateSampleOHLC('BBRI', 30); // Generate very few data points
    
    // Initialize chart with default settings immediately without timeout
    // We don't need a setTimeout anymore since we pre-loaded the data
    renderStrategyChart('BBRI', 'candlestick');
    
    // Add event listeners for chart type selection
    const chartTypeRadios = document.querySelectorAll('input[name="chartType"]');
    chartTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            console.log(`Chart type changed to: ${this.id}`);
            if (this.checked) {
                // Force re-render with new chart type
                const stockSymbol = document.getElementById('stockSymbol').value || 'BBRI';
                renderStrategyChart(stockSymbol, this.id);
            }
        });
    });
    
    // Add event listener for stock symbol changes
    const stockSymbolSelect = document.getElementById('stockSymbol');
    if (stockSymbolSelect) {
        stockSymbolSelect.addEventListener('change', function() {
            console.log(`Stock symbol changed to: ${this.value}`);
            // Use cached data if available
            if (dataCache[this.value]) {
                chartData = dataCache[this.value];
            } else {
                chartData = []; // Reset data to get new data for the selected stock
            }
            renderStrategyChart(this.value, currentChartType);
        });
    }
    
    // Add event listeners for indicator checkboxes
    const volumeCheckbox = document.getElementById('showVolume');
    if (volumeCheckbox) {
        volumeCheckbox.addEventListener('change', function() {
            console.log(`Volume indicator ${this.checked ? 'enabled' : 'disabled'}`);
            if (this.checked) {
                addVolumeToChart();
            } else if (stockChart) {
                stockChart.hideSeries('Volume');
            }
        });
    }
    
    const smaCheckbox = document.getElementById('showSMA');
    if (smaCheckbox) {
        smaCheckbox.addEventListener('change', function() {
            console.log(`SMA indicator ${this.checked ? 'enabled' : 'disabled'}`);
            if (this.checked) {
                addSMAToChart(20);
            } else if (stockChart) {
                stockChart.hideSeries('SMA (20)');
            }
        });
    }

    // Manually check if we need to immediately show volume
    if (volumeCheckbox && volumeCheckbox.checked) {
        addVolumeToChart();
    }
});

// Initialize trading tools
// Fungsi ini sudah dideklarasikan sebelumnya di baris 722

// Setup listeners for all indicator checkboxes
const setupIndicatorListeners = () => {
    const indicatorMap = {
        'showSMA': {
            periods: [20, 50, 200],
            colors: ['#2196F3', '#FF9800', '#E91E63']
        },
        'showEMA': {
            periods: [9, 21, 55],
            colors: ['#7C4DFF', '#FF5722', '#8BC34A']
        },
        'showBollinger': {
            period: 20,
            stdDev: 2
        },
        'showMACD': {
            fastPeriod: 12,
            slowPeriod: 26,
            signalPeriod: 9
        }
    };
    
    // Add listeners for each indicator
    Object.keys(indicatorMap).forEach(id => {
        const checkbox = document.getElementById(id);
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    // Add indicator to chart
                    // This would need implementation for each specific indicator
                    console.log(`Adding ${id} to chart`);
                } else {
                    // Remove indicator from chart
                    console.log(`Removing ${id} from chart`);
                }
            });
        }
    });
};

// ... (rest of the code remains the same)
