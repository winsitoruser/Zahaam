// stock-chart.js - Script untuk menampilkan grafik candlestick dengan indikator

function initStockChart(containerId, stockSymbol) {
    // Placeholder untuk data saham
    const fetchStockData = async (symbol, timeframe = '1D') => {
        // Dalam implementasi asli, ini akan memanggil API backend
        // Untuk contoh, kita gunakan data statis
        return {
            dates: [
                '2025-05-22', '2025-05-23', '2025-05-24', '2025-05-25', 
                '2025-05-26', '2025-05-27', '2025-05-28', '2025-05-29'
            ],
            ohlc: [
                [4520, 4550, 4510, 4535], [4535, 4560, 4530, 4545],
                [4545, 4570, 4535, 4560], [4560, 4580, 4555, 4575],
                [4575, 4590, 4565, 4585], [4585, 4600, 4570, 4590],
                [4590, 4610, 4580, 4595], [4595, 4620, 4570, 4580]
            ],
            volume: [
                22450600, 21345700, 23567800, 20345600, 
                24567800, 26789500, 23456700, 25450200
            ],
            sma20: [4510, 4520, 4530, 4540, 4550, 4560, 4570, 4580],
            sma50: [4420, 4425, 4430, 4435, 4440, 4445, 4450, 4455],
            rsi14: [45, 48, 52, 55, 58, 56, 54, 52],
            macd: [10, 11, 12, 12.5, 12.8, 13, 12.5, 12.35],
            macdSignal: [9.5, 10, 10.5, 11, 11.5, 12, 12.2, 12.25],
            macdHist: [0.5, 1, 1.5, 1.5, 1.3, 1, 0.3, 0.1]
        };
    };

    // Inisialisasi chart
    const initChart = async () => {
        const stockData = await fetchStockData(stockSymbol);
        
        // Siapkan data untuk ApexCharts
        const candlestickData = stockData.dates.map((date, i) => ({
            x: new Date(date).getTime(),
            y: stockData.ohlc[i]
        }));
        
        const sma20Data = stockData.dates.map((date, i) => ({
            x: new Date(date).getTime(),
            y: stockData.sma20[i]
        }));
        
        const sma50Data = stockData.dates.map((date, i) => ({
            x: new Date(date).getTime(),
            y: stockData.sma50[i]
        }));

        // Opsi chart
        const options = {
            series: [
                {
                    name: stockSymbol,
                    type: 'candlestick',
                    data: candlestickData
                },
                {
                    name: 'SMA 20',
                    type: 'line',
                    data: sma20Data
                },
                {
                    name: 'SMA 50',
                    type: 'line',
                    data: sma50Data
                }
            ],
            chart: {
                height: 400,
                type: 'candlestick',
                toolbar: {
                    show: true
                }
            },
            xaxis: {
                type: 'datetime'
            },
            yaxis: {
                tooltip: {
                    enabled: true
                }
            },
            colors: ['#000', '#2196F3', '#FFA500'],
            stroke: {
                width: [1, 2, 2]
            },
            plotOptions: {
                candlestick: {
                    colors: {
                        upward: '#26a69a',
                        downward: '#ef5350'
                    }
                }
            }
        };
        
        // Render chart
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
        
        // Expose chart controls
        window.toggleSMA20 = function(checked) {
            if (checked) {
                chart.showSeries('SMA 20');
            } else {
                chart.hideSeries('SMA 20');
            }
        };
        
        window.toggleSMA50 = function(checked) {
            if (checked) {
                chart.showSeries('SMA 50');
            } else {
                chart.hideSeries('SMA 50');
            }
        };
    };
    
    // Initialize
    initChart();
}
