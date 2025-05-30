// market-depth.js - Script untuk menampilkan market depth dan signal trading

/**
 * Menginisialisasi chart market depth
 * @param {string} containerId - ID elemen container untuk chart
 * @param {string} stockSymbol - Simbol saham (ticker)
 */
function initMarketDepthChart(containerId, stockSymbol) {
    // Mendapatkan data market depth (simulasi)
    const fetchMarketDepthData = (symbol) => {
        // Data statis untuk simulasi - dalam implementasi nyata, ini akan memanggil API
        return {
            bids: [
                { price: 4570, volume: 15200000 },
                { price: 4560, volume: 7800000 },
                { price: 4550, volume: 3500000 },
                { price: 4540, volume: 2100000 },
                { price: 4530, volume: 1800000 },
                { price: 4520, volume: 1200000 },
                { price: 4510, volume: 850000 },
                { price: 4500, volume: 720000 }
            ],
            asks: [
                { price: 4580, volume: 12500000 },
                { price: 4590, volume: 6400000 },
                { price: 4600, volume: 2800000 },
                { price: 4610, volume: 1900000 },
                { price: 4620, volume: 1350000 },
                { price: 4630, volume: 920000 },
                { price: 4640, volume: 750000 },
                { price: 4650, volume: 650000 }
            ],
            lastPrice: 4580,
            bidTotalVolume: 25830000,
            askTotalVolume: 18240000
        };
    };

    // Mendapatkan data
    const depthData = fetchMarketDepthData(stockSymbol);
    
    // Memformat data untuk chart
    const bidsSeries = depthData.bids.map(bid => ({
        x: bid.price,
        y: bid.volume / 1000000 // Konversi ke juta untuk tampilan lebih baik
    })).sort((a, b) => a.x - b.x); // Urutkan berdasarkan harga
    
    const asksSeries = depthData.asks.map(ask => ({
        x: ask.price,
        y: ask.volume / 1000000 // Konversi ke juta untuk tampilan lebih baik
    })).sort((a, b) => a.x - b.x); // Urutkan berdasarkan harga

    // Konfigurasi chart market depth
    const options = {
        series: [{
            name: 'Bids',
            data: bidsSeries
        }, {
            name: 'Asks',
            data: asksSeries
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            },
            animations: {
                enabled: false
            },
            background: 'transparent'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'stepline',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.5,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        colors: ['#28a745', '#dc3545'],
        xaxis: {
            type: 'numeric',
            title: {
                text: 'Price (Rp)',
                style: {
                    fontSize: '12px'
                }
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed(0);
                }
            },
            tickAmount: 6,
            tooltip: {
                enabled: false
            }
        },
        yaxis: {
            title: {
                text: 'Volume (Juta)',
                style: {
                    fontSize: '12px'
                }
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed(1);
                }
            }
        },
        tooltip: {
            shared: false,
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const data = seriesIndex === 0 ? depthData.bids : depthData.asks;
                const point = data[dataPointIndex];
                const price = w.globals.seriesX[seriesIndex][dataPointIndex];
                const volume = series[seriesIndex][dataPointIndex];
                
                return `
                <div class="custom-tooltip">
                    <span>Price: Rp ${price.toFixed(0)}</span><br>
                    <span>Volume: ${(volume * 1000000).toLocaleString()} shares</span>
                </div>`;
            }
        },
        annotations: {
            xaxis: [{
                x: depthData.lastPrice,
                borderColor: '#4e73df',
                label: {
                    borderColor: '#4e73df',
                    style: {
                        color: '#fff',
                        background: '#4e73df'
                    },
                    text: 'Last Price'
                }
            }]
        },
        grid: {
            borderColor: '#e0e0e0'
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        }
    };

    // Inisialisasi chart
    const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
    chart.render();
    
    // Event listener untuk toggle real-time
    const realTimeToggle = document.getElementById('realTimeDepth');
    if (realTimeToggle) {
        realTimeToggle.addEventListener('change', function() {
            if (this.checked) {
                // Simulasi update real-time
                const depthUpdateInterval = setInterval(() => {
                    // Buat perubahan kecil pada data volume
                    const newBids = bidsSeries.map(bid => ({
                        x: bid.x,
                        y: bid.y * (0.95 + Math.random() * 0.1) // Fluktuasi ±5%
                    }));
                    
                    const newAsks = asksSeries.map(ask => ({
                        x: ask.x,
                        y: ask.y * (0.95 + Math.random() * 0.1) // Fluktuasi ±5%
                    }));
                    
                    // Update series data
                    chart.updateSeries([{
                        name: 'Bids',
                        data: newBids
                    }, {
                        name: 'Asks',
                        data: newAsks
                    }]);
                }, 2000);
                
                // Simpan interval ID di elemen untuk dibersihkan nanti
                this.dataset.intervalId = depthUpdateInterval;
            } else {
                // Hentikan update real-time
                if (this.dataset.intervalId) {
                    clearInterval(parseInt(this.dataset.intervalId));
                }
            }
        });
    }
}

/**
 * Menampilkan trading signals dan penjelasannya
 * @param {string} stockSymbol - Simbol saham (ticker)
 */
function displayTradingSignals(stockSymbol) {
    // Fungsi ini dapat diperluas untuk mengambil data sinyal trading dari API
    // Untuk saat ini, kita sudah memiliki data statis di HTML
    
    // Jika di masa depan data diambil dari API, implementasi bisa ditambahkan di sini
    // Contoh panggilan API:
    // const fetchSignalData = async (symbol) => {
    //     const response = await fetch(`/api/signals/${symbol}`);
    //     return await response.json();
    // };
    
    // Menambahkan event handlers untuk elemen interaktif di signals section
    const signalElements = document.querySelectorAll('.signal-card');
    signalElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
}

// Inisialisasi chart dan signals ketika dokumen siap
document.addEventListener('DOMContentLoaded', function() {
    // Mendapatkan parameter saham dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const stockSymbol = urlParams.get('symbol') || 'BBRI';
    
    // Inisialisasi market depth chart
    initMarketDepthChart('marketDepthChart', stockSymbol);
    
    // Tampilkan trading signals
    displayTradingSignals(stockSymbol);
});
