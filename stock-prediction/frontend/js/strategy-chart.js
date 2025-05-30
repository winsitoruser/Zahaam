// strategy-chart.js - Script untuk chart dan indikator pada halaman strategi trading

document.addEventListener('DOMContentLoaded', function() {
    // Chart functionality
    const chartsTab = document.getElementById('charts-tab');
    const chartContainer = document.getElementById('chartContainer');
    const chartLoadingIndicator = document.getElementById('chartLoadingIndicator');
    let chart = null;

    // Listen for chart tab clicked
    if (chartsTab) {
        chartsTab.addEventListener('shown.bs.tab', function() {
            // Use the function from strategy-backtest.js instead
            const stockSymbol = document.getElementById('stockSymbol')?.value || 'BBRI';
            const chartType = document.querySelector('input[name="chartType"]:checked')?.id || 'candlestick';
            window.renderStrategyChart(stockSymbol, chartType);
        });
    }

    // Legacy strategy chart function (renamed to avoid conflicts)
    function _legacyRenderStrategyChart() {
        if (!chartContainer) return;
        
        // Show loading indicator
        if (chartLoadingIndicator) {
            chartLoadingIndicator.style.display = 'flex';
        }
        
        // Clear any existing chart
        chartContainer.innerHTML = '';
        
        // Get selected stock
        const stockSymbol = document.getElementById('stockSymbol')?.value || 'BBRI';
        
        // Fetch historical data (sample data in this example)
        setTimeout(() => {
            const data = generateSampleOHLC(stockSymbol, 90);
            createChart(stockSymbol, data);
            
            // Hide loading indicator
            if (chartLoadingIndicator) {
                chartLoadingIndicator.style.display = 'none';
            }
        }, 800); // Simulate fetch delay
    }
    
    // Create chart with ApexCharts
    function createChart(symbol, data) {
        const options = {
            series: [{
                name: symbol,
                type: 'candlestick',
                data: data.map(d => ({
                    x: new Date(d.date),
                    y: [d.open, d.high, d.low, d.close]
                }))
            }],
            chart: {
                height: 400,
                type: 'candlestick',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                },
                background: 'transparent'
            },
            title: {
                text: `${symbol} - Analisis Teknikal`,
                align: 'left',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold'
                }
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    format: 'dd MMM',
                    datetimeUTC: false
                }
            },
            yaxis: {
                tooltip: {
                    enabled: true
                },
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                }
            },
            plotOptions: {
                candlestick: {
                    colors: {
                        upward: '#26A69A',
                        downward: '#EF5350'
                    },
                    wick: {
                        useFillColor: true
                    }
                }
            },
            tooltip: {
                theme: document.body.classList.contains('dark-mode') ? 'dark' : 'light',
                x: {
                    format: 'dd MMM yyyy'
                },
                y: {
                    formatter: function(val) {
                        return val.toFixed(2);
                    }
                }
            }
        };

        // Create chart instance
        chart = new ApexCharts(chartContainer, options);
        chart.render();

        // Setup event handlers for indicator checkboxes
        setupIndicatorHandlers(chart, data);
        
        // Setup chart type radio buttons
        setupChartTypeHandlers(chart);
    }
    
    // Setup handlers for chart type radio buttons
    function setupChartTypeHandlers(chart) {
        document.querySelectorAll('input[name="chartType"]').forEach(radio => {
            radio.addEventListener('change', function() {
                let newType = 'candlestick';
                
                switch(this.id) {
                    case 'ohlc':
                        newType = 'bar';
                        break;
                    case 'line':
                        newType = 'line';
                        break;
                    case 'area':
                        newType = 'area';
                        break;
                }
                
                chart.updateOptions({
                    chart: {
                        type: newType
                    }
                });
            });
        });
    }
    
    // Setup handlers for indicator checkboxes
    function setupIndicatorHandlers(chart, data) {
        const indicators = {
            showVolume: function(checked) {
                if (checked) {
                    // Add volume series
                    const volumeData = data.map(d => ({
                        x: new Date(d.date),
                        y: d.volume
                    }));
                    
                    chart.appendSeries({
                        name: 'Volume',
                        type: 'bar',
                        data: volumeData
                    });
                } else {
                    chart.hideSeries('Volume');
                }
            },
            showSMA: function(checked) {
                if (checked) {
                    // Calculate SMA 20
                    const closePrices = data.map(d => d.close);
                    const sma20 = calculateSMA(closePrices, 20);
                    
                    chart.appendSeries({
                        name: 'SMA 20',
                        type: 'line',
                        data: sma20.map((val, i) => ({
                            x: new Date(data[i + 19].date),
                            y: val
                        }))
                    });
                } else {
                    chart.hideSeries('SMA 20');
                }
            },
            showEMA: function(checked) {
                if (checked) {
                    // Calculate EMA 20
                    const closePrices = data.map(d => d.close);
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
            showBollinger: function(checked) {
                if (checked) {
                    // Calculate Bollinger Bands (SMA 20, 2 standard deviations)
                    const closePrices = data.map(d => d.close);
                    const period = 20;
                    const stdDev = 2;
                    
                    const sma = calculateSMA(closePrices, period);
                    const upperBand = [];
                    const lowerBand = [];
                    
                    for (let i = period - 1; i < closePrices.length; i++) {
                        const slice = closePrices.slice(i - period + 1, i + 1);
                        const sum = slice.reduce((a, b) => a + b, 0);
                        const avg = sum / period;
                        
                        // Calculate standard deviation
                        const squaredDiffs = slice.map(val => Math.pow(val - avg, 2));
                        const avgSquaredDiff = squaredDiffs.reduce((a, b) => a + b, 0) / period;
                        const std = Math.sqrt(avgSquaredDiff);
                        
                        upperBand.push(avg + stdDev * std);
                        lowerBand.push(avg - stdDev * std);
                    }
                    
                    // Add upper band
                    chart.appendSeries({
                        name: 'Upper Band',
                        type: 'line',
                        data: upperBand.map((val, i) => ({
                            x: new Date(data[i + period - 1].date),
                            y: val
                        }))
                    });
                    
                    // Add middle band (SMA)
                    chart.appendSeries({
                        name: 'Middle Band',
                        type: 'line',
                        data: sma.map((val, i) => ({
                            x: new Date(data[i + period - 1].date),
                            y: val
                        }))
                    });
                    
                    // Add lower band
                    chart.appendSeries({
                        name: 'Lower Band',
                        type: 'line',
                        data: lowerBand.map((val, i) => ({
                            x: new Date(data[i + period - 1].date),
                            y: val
                        }))
                    });
                } else {
                    chart.hideSeries('Upper Band');
                    chart.hideSeries('Middle Band');
                    chart.hideSeries('Lower Band');
                }
            },
            showMACD: function(checked) {
                if (checked) {
                    const closePrices = data.map(d => d.close);
                    const fastPeriod = 12;
                    const slowPeriod = 26;
                    const signalPeriod = 9;
                    
                    // Calculate MACD line
                    const fastEMA = calculateEMA(closePrices, fastPeriod);
                    const slowEMA = calculateEMA(closePrices, slowPeriod);
                    
                    // We need to align the arrays since EMAs have different lengths
                    const macdLine = [];
                    
                    for (let i = 0; i < slowEMA.length; i++) {
                        const fastIndex = i + (slowPeriod - fastPeriod);
                        if (fastIndex >= 0 && fastIndex < fastEMA.length) {
                            macdLine.push(fastEMA[fastIndex] - slowEMA[i]);
                        }
                    }
                    
                    // Calculate signal line (9-day EMA of MACD line)
                    const signalLine = calculateEMA(macdLine, signalPeriod);
                    
                    // Align dates for the MACD and signal lines
                    const macdDates = data.slice(slowPeriod).map(d => d.date);
                    const signalDates = macdDates.slice(signalPeriod - 1);
                    
                    // Add MACD line
                    chart.appendSeries({
                        name: 'MACD',
                        type: 'line',
                        data: macdLine.map((val, i) => ({
                            x: new Date(macdDates[i]),
                            y: val
                        }))
                    });
                    
                    // Add signal line
                    chart.appendSeries({
                        name: 'Signal',
                        type: 'line',
                        data: signalLine.map((val, i) => ({
                            x: new Date(signalDates[i]),
                            y: val
                        }))
                    });
                } else {
                    chart.hideSeries('MACD');
                    chart.hideSeries('Signal');
                }
            },
            showRSI: function(checked) {
                if (checked) {
                    const closePrices = data.map(d => d.close);
                    const period = 14;
                    const rsi = calculateRSI(closePrices, period);
                    
                    chart.appendSeries({
                        name: 'RSI',
                        type: 'line',
                        data: rsi.map((val, i) => ({
                            x: new Date(data[i + period].date),
                            y: val
                        }))
                    });
                } else {
                    chart.hideSeries('RSI');
                }
            }
            // Additional indicators can be added here
        };
        
        // Add event listeners to indicator checkboxes
        Object.keys(indicators).forEach(id => {
            const checkbox = document.getElementById(id);
            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    indicators[id](this.checked);
                });
                
                // Initialize if checked by default
                if (checkbox.checked) {
                    indicators[id](true);
                }
            }
        });
    }
    
    // Helper function to calculate SMA
    function calculateSMA(data, period) {
        const result = [];
        for (let i = period - 1; i < data.length; i++) {
            const sum = data.slice(i - period + 1, i + 1).reduce((a, b) => a + b, 0);
            result.push(sum / period);
        }
        return result;
    }
    
    // Helper function to calculate EMA
    function calculateEMA(data, period) {
        const result = [];
        const k = 2 / (period + 1);
        
        // First EMA is SMA
        let ema = data.slice(0, period).reduce((a, b) => a + b, 0) / period;
        result.push(ema);
        
        // Calculate EMA for remaining data
        for (let i = period; i < data.length; i++) {
            ema = data[i] * k + ema * (1 - k);
            result.push(ema);
        }
        
        return result;
    }
    
    // Helper function to calculate RSI
    function calculateRSI(data, period) {
        const result = [];
        let gains = 0;
        let losses = 0;
        
        // Calculate initial average gains and losses
        for (let i = 1; i <= period; i++) {
            const change = data[i] - data[i - 1];
            if (change >= 0) {
                gains += change;
            } else {
                losses -= change;
            }
        }
        
        let avgGain = gains / period;
        let avgLoss = losses / period;
        
        // Calculate first RSI
        let rs = avgLoss ? avgGain / avgLoss : 0;
        let rsi = 100 - (100 / (1 + rs));
        result.push(rsi);
        
        // Calculate remaining RSI values
        for (let i = period + 1; i < data.length; i++) {
            const change = data[i] - data[i - 1];
            let currentGain = change >= 0 ? change : 0;
            let currentLoss = change < 0 ? -change : 0;
            
            // Use Wilder's smoothing method
            avgGain = (avgGain * (period - 1) + currentGain) / period;
            avgLoss = (avgLoss * (period - 1) + currentLoss) / period;
            
            rs = avgLoss ? avgGain / avgLoss : 0;
            rsi = 100 - (100 / (1 + rs));
            result.push(rsi);
        }
        
        return result;
    }
    
    // Generate sample OHLC data
    function generateSampleOHLC(symbol, days) {
        const data = [];
        let price = symbol === 'BBCA' ? 8800 : 
                   symbol === 'BBRI' ? 4500 : 
                   symbol === 'TLKM' ? 3200 : 
                   symbol === 'BMRI' ? 5600 : 
                   symbol === 'ASII' ? 6700 : 3500;
                   
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - days);
        
        for (let i = 0; i < days; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            
            // Skip weekends
            const day = date.getDay();
            if (day === 0 || day === 6) continue;
            
            // Simulate price movements with slight upward bias
            const change = (Math.random() - 0.48) * (price * 0.02); 
            price += change;
            
            // Generate OHLC data
            const open = price;
            const high = price + Math.random() * (price * 0.01);
            const low = price - Math.random() * (price * 0.01);
            const close = price + (Math.random() - 0.5) * (price * 0.008);
            const volume = Math.floor(Math.random() * 50000000) + 10000000;
            
            data.push({
                date: date.toISOString().split('T')[0],
                open,
                high,
                low,
                close: Math.max(1, close), // Ensure price doesn't go below 1
                volume
            });
            
            // Update price for next iteration
            price = close;
        }
        
        // Simulate price movements with slight upward bias
        const change = (Math.random() - 0.48) * (price * 0.02); 
        price += change;
        
        // Generate OHLC data
        const open = price;
        const high = price + Math.random() * (price * 0.01);
        const low = price - Math.random() * (price * 0.01);
        const close = price + (Math.random() - 0.5) * (price * 0.008);
        const volume = Math.floor(Math.random() * 50000000) + 10000000;
        
        data.push({
            date: date.toISOString().split('T')[0],
            open,
            high,
            low,
            close: Math.max(1, close), // Ensure price doesn't go below 1
            volume
        });
        
        // Update price for next iteration
        price = close;
    }
    
    return data;
}

// Listen for changes to the stock dropdown
// This is now handled in strategy-backtest.js
/*
const stockSymbolEl = document.getElementById('stockSymbol');
if (stockSymbolEl) {
    stockSymbolEl.addEventListener('change', function() {
        // Using the renderStrategyChart from strategy-backtest.js instead
    });
}
*/

}); // Tutup dari document.addEventListener di awal file
