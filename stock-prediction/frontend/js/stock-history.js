// stock-history.js - Script untuk mengambil dan menampilkan histori harga saham dari Yahoo Finance

// Fungsi untuk mengambil data histori harga saham dari Yahoo Finance
async function fetchStockHistory(symbol, range = '1mo') {
    try {
        // Dalam implementasi sebenarnya, kita akan memanggil API atau backend
        // yang dapat mengakses Yahoo Finance API. Namun, untuk tujuan demo,
        // kita akan mensimulasikan respons API.
        
        // Simulasi loading
        showLoadingState();
        
        // Dalam kasus nyata, kita akan membuat permintaan ke server backend kita
        // yang akan mengambil data dari Yahoo Finance API, misalnya:
        // const response = await fetch(`/api/stock/history?symbol=${symbol}&range=${range}`);
        // const data = await response.json();
        
        // Simulasi delay jaringan
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // Generate data historis tiruan untuk demo
        const mockData = generateMockStockHistory(symbol, range);
        
        // Render tabel histori
        renderHistoryTable(mockData, symbol);
        
        // Render statistik ringkasan
        renderSummaryStats(mockData, symbol);
        
        return mockData;
    } catch (error) {
        console.error('Error fetching stock history:', error);
        document.getElementById('historyTableContainer').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Failed to load stock history. Please try again later.
            </div>
        `;
    }
}

// Menampilkan status loading saat mengambil data
function showLoadingState() {
    const container = document.getElementById('historyTableContainer');
    if (container) {
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-zahaam"></div>
                <p class="text-muted mt-3">Fetching stock history data...</p>
            </div>
        `;
    }
}

// Render tabel histori harga saham
function renderHistoryTable(data, symbol) {
    const container = document.getElementById('historyTableContainer');
    if (!container) return;
    
    const rows = data.map(item => {
        const priceChange = item.close - item.open;
        const priceChangePercent = (priceChange / item.open) * 100;
        const changeClass = priceChange >= 0 ? 'text-success' : 'text-danger';
        const changeIcon = priceChange >= 0 ? 'bi-arrow-up' : 'bi-arrow-down';
        
        return `
            <tr>
                <td>${formatDate(item.date)}</td>
                <td class="text-end">Rp ${formatNumber(item.open)}</td>
                <td class="text-end">Rp ${formatNumber(item.high)}</td>
                <td class="text-end">Rp ${formatNumber(item.low)}</td>
                <td class="text-end">Rp ${formatNumber(item.close)}</td>
                <td class="text-end ${changeClass}">
                    <i class="bi ${changeIcon}"></i> ${Math.abs(priceChangePercent).toFixed(2)}%
                </td>
                <td class="text-end">${formatNumber(item.volume)}</td>
            </tr>
        `;
    }).join('');
    
    container.innerHTML = `
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Price History for ${symbol}</h5>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary range-btn" data-range="1mo">1M</button>
                    <button class="btn btn-sm btn-outline-primary range-btn" data-range="3mo">3M</button>
                    <button class="btn btn-sm btn-outline-primary range-btn" data-range="6mo">6M</button>
                    <button class="btn btn-sm btn-outline-primary range-btn" data-range="1y">1Y</button>
                    <button class="btn btn-sm btn-outline-primary range-btn" data-range="5y">5Y</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-end">Open</th>
                                <th class="text-end">High</th>
                                <th class="text-end">Low</th>
                                <th class="text-end">Close</th>
                                <th class="text-end">Change</th>
                                <th class="text-end">Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <small class="text-muted">Data source: Yahoo Finance</small>
                <button class="btn btn-sm btn-outline-primary ms-2" id="downloadCsvBtn">
                    <i class="bi bi-download me-1"></i> Download CSV
                </button>
            </div>
        </div>
    `;
    
    // Tambahkan event listener untuk tombol range
    document.querySelectorAll('.range-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Hapus kelas aktif dari semua tombol
            document.querySelectorAll('.range-btn').forEach(b => b.classList.remove('active'));
            // Tambahkan kelas aktif ke tombol yang diklik
            btn.classList.add('active');
            // Ambil data dengan range baru
            fetchStockHistory(symbol, btn.getAttribute('data-range'));
        });
    });
    
    // Aktifkan tombol range default (1mo)
    document.querySelector(`.range-btn[data-range="${data[0].range}"]`).classList.add('active');
    
    // Tambahkan event listener untuk tombol download CSV
    document.getElementById('downloadCsvBtn').addEventListener('click', () => {
        downloadCSV(data, symbol);
    });
}

// Render statistik ringkasan
function renderSummaryStats(data, symbol) {
    const container = document.getElementById('historySummary');
    if (!container) return;
    
    // Hitung statistik dari data
    const closes = data.map(item => item.close);
    const volumes = data.map(item => item.volume);
    const startPrice = data[data.length - 1].close;
    const endPrice = data[0].close;
    const totalChange = endPrice - startPrice;
    const totalChangePercent = (totalChange / startPrice) * 100;
    const avgVolume = volumes.reduce((a, b) => a + b, 0) / volumes.length;
    
    // Hitung volatilitas (standar deviasi dari perubahan harga harian)
    const dailyChanges = data.map((item, index) => {
        if (index === data.length - 1) return 0;
        return (item.close - data[index + 1].close) / data[index + 1].close * 100;
    });
    const avgChange = dailyChanges.reduce((a, b) => a + b, 0) / dailyChanges.length;
    const volatility = Math.sqrt(
        dailyChanges.map(change => Math.pow(change - avgChange, 2))
                    .reduce((a, b) => a + b, 0) / dailyChanges.length
    );
    
    // Hitung max drawdown
    let maxDrawdown = 0;
    let peak = closes[0];
    for (let i = 1; i < closes.length; i++) {
        if (closes[i] > peak) {
            peak = closes[i];
        } else {
            const drawdown = (peak - closes[i]) / peak * 100;
            maxDrawdown = Math.max(maxDrawdown, drawdown);
        }
    }
    
    const changeClass = totalChange >= 0 ? 'text-success' : 'text-danger';
    const changeIcon = totalChange >= 0 ? 'bi-arrow-up' : 'bi-arrow-down';
    
    container.innerHTML = `
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Period Return</h6>
                        <h3 class="${changeClass}">
                            <i class="bi ${changeIcon}"></i> ${Math.abs(totalChangePercent).toFixed(2)}%
                        </h3>
                        <p class="text-muted small mb-0">${formatDate(data[data.length - 1].date)} - ${formatDate(data[0].date)}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Average Volume</h6>
                        <h3>${formatNumber(Math.round(avgVolume))}</h3>
                        <p class="text-muted small mb-0">Daily trading volume</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Volatility</h6>
                        <h3>${volatility.toFixed(2)}%</h3>
                        <p class="text-muted small mb-0">Daily price fluctuation</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Max Drawdown</h6>
                        <h3 class="text-danger">${maxDrawdown.toFixed(2)}%</h3>
                        <p class="text-muted small mb-0">Maximum decline from peak</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Fungsi untuk download data sebagai CSV
function downloadCSV(data, symbol) {
    // Buat header CSV
    let csvContent = "data:text/csv;charset=utf-8,Date,Open,High,Low,Close,Change,Volume\n";
    
    // Tambahkan baris data
    data.forEach(item => {
        const priceChange = item.close - item.open;
        const priceChangePercent = (priceChange / item.open) * 100;
        
        const row = [
            formatDate(item.date),
            item.open,
            item.high,
            item.low,
            item.close,
            priceChangePercent.toFixed(2) + '%',
            item.volume
        ].join(',');
        
        csvContent += row + "\n";
    });
    
    // Buat link untuk download
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `${symbol}_history_${formatDate(new Date())}.csv`);
    document.body.appendChild(link);
    
    // Klik link untuk download
    link.click();
    
    // Hapus link
    document.body.removeChild(link);
}

// Fungsi helper untuk format tanggal
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

// Fungsi helper untuk format angka
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Fungsi untuk generate data mock histori saham
function generateMockStockHistory(symbol, range) {
    // Tentukan jumlah hari berdasarkan range
    let days;
    switch (range) {
        case '1mo': days = 22; break;  // 1 bulan kerja ≈ 22 hari
        case '3mo': days = 66; break;  // 3 bulan kerja ≈ 66 hari
        case '6mo': days = 132; break; // 6 bulan kerja ≈ 132 hari
        case '1y': days = 252; break;  // 1 tahun kerja ≈ 252 hari
        case '5y': days = 1260; break; // 5 tahun kerja ≈ 1260 hari
        default: days = 22;
    }
    
    // Harga awal berdasarkan simbol (untuk demo saja)
    let basePrice;
    switch (symbol) {
        case 'BBRI': basePrice = 4580; break;
        case 'BBCA': basePrice = 9450; break;
        case 'TLKM': basePrice = 3750; break;
        case 'ASII': basePrice = 5900; break;
        case 'BMRI': basePrice = 5200; break;
        case 'UNVR': basePrice = 4120; break;
        case 'HMSP': basePrice = 1505; break;
        case 'INDF': basePrice = 6750; break;
        case 'EXCL': basePrice = 2310; break;
        case 'BBNI': basePrice = 4870; break;
        case 'ICBP': basePrice = 8230; break;
        default: basePrice = 5000;
    }
    
    // Generate data dengan random walk
    const result = [];
    let currentDate = new Date();
    let price = basePrice;
    
    for (let i = 0; i < days; i++) {
        // Kurangi 1 hari jika hari kerja (senin-jumat)
        do {
            currentDate.setDate(currentDate.getDate() - 1);
        } while (currentDate.getDay() === 0 || currentDate.getDay() === 6); // Skip sabtu minggu
        
        // Random daily volatility (0.5% - 2%)
        const volatility = 0.005 + Math.random() * 0.015;
        
        // Adjust volatility based on range (longer ranges have less daily volatility)
        const adjustedVolatility = range === '5y' ? volatility * 0.5 : 
                                  range === '1y' ? volatility * 0.7 : 
                                  range === '6mo' ? volatility * 0.8 : 
                                  range === '3mo' ? volatility * 0.9 : 
                                  volatility;
        
        // Generate price movement (-2% to +2% with slight upward bias)
        const change = (Math.random() - 0.48) * adjustedVolatility * price;
        price += change;
        
        // Ensure price doesn't go too low
        price = Math.max(price, basePrice * 0.5);
        
        // Generate OHLC data
        const open = price;
        const high = price * (1 + Math.random() * 0.015); // Up to 1.5% higher
        const low = price * (1 - Math.random() * 0.015);  // Up to 1.5% lower
        const close = price + (Math.random() - 0.5) * (high - low) * 0.5;
        
        // Volume (more volatile on big price moves)
        const volumeBase = basePrice === 4580 ? 50000000 : 
                          basePrice === 9450 ? 20000000 : 
                          basePrice === 3750 ? 80000000 : 
                          basePrice === 5900 ? 30000000 : 
                          basePrice === 5200 ? 40000000 : 
                          35000000;
        
        const volumeVolatility = Math.abs(change / price) * 10; // More volume on bigger changes
        const volume = Math.floor(volumeBase * (0.7 + Math.random() * 0.6 + volumeVolatility));
        
        result.push({
            date: new Date(currentDate).toISOString().split('T')[0],
            open: parseFloat(open.toFixed(2)),
            high: parseFloat(high.toFixed(2)),
            low: parseFloat(low.toFixed(2)),
            close: parseFloat(close.toFixed(2)),
            volume,
            range // Simpan range untuk referensi
        });
    }
    
    return result;
}
