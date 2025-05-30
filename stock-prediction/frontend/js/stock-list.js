// stock-list.js - JavaScript untuk halaman daftar saham dengan pagination

// Konfigurasi pagination
const ITEMS_PER_PAGE = 15;
let currentPage = 1;
let filteredStocks = [];
let allStocks = [];
let favorites = JSON.parse(localStorage.getItem('favoriteStocks') || '[]');

// Inisialisasi halaman
document.addEventListener('DOMContentLoaded', () => {
    // Load data saham
    fetchStockData();
    
    // Event listeners untuk search, filter, dan sort
    setupEventListeners();
});

// Fungsi untuk mengambil data saham
async function fetchStockData() {
    try {
        // Menampilkan loading state
        document.getElementById('stocksTableBody').innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-5">
                    <div class="spinner-zahaam"></div>
                    <p class="mt-3 text-muted">Loading stocks data...</p>
                </td>
            </tr>
        `;
        
        // Fetch stock details dari API
        const response = await fetch('/api/stocks/details');
        
        if (!response.ok) {
            throw new Error(`Error fetching stock data: ${response.statusText}`);
        }
        
        const data = await response.json();
        const stocksFromAPI = data.stocks || [];
        
        // Tambahkan data harga dan informasi lainnya
        allStocks = await enrichStocksWithPriceData(stocksFromAPI);
        
        // Tampilkan data
        filteredStocks = [...allStocks];
        renderStockTable(filteredStocks);
        renderPagination(filteredStocks.length);
        
    } catch (error) {
        console.error('Error fetching stock data:', error);
        document.getElementById('stocksTableBody').innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="bi bi-exclamation-triangle text-warning fs-1"></i>
                    <p class="mt-3 text-muted">Failed to load stocks data: ${error.message}</p>
                    <button class="btn btn-outline-primary mt-2" id="retryFetchBtn">
                        <i class="bi bi-arrow-clockwise me-2"></i>Retry
                    </button>
                </td>
            </tr>
        `;
        
        document.getElementById('retryFetchBtn').addEventListener('click', fetchStockData);
    }
}

// Fungsi untuk memperkaya data saham dengan data harga
async function enrichStocksWithPriceData(stocks) {
    try {
        // Dalam implementasi produksi, mungkin ada API endpoint khusus
        // yang mengembalikan data lengkap dengan harga
        
        // Untuk sekarang, kita akan fetch data harga terakhir dari API
        const pricesResponse = await fetch('/api/stocks/latest-prices');
        
        if (!pricesResponse.ok) {
            // Jika API belum tersedia, gunakan data harga acak
            console.warn('Latest prices API not available, using random price data');
            return stocks.map(stock => ({
                symbol: stock.ticker.replace('.JK', ''),
                company: stock.name,
                sector: stock.sector,
                lastPrice: generateRandomPrice(),
                change: generateRandomChange(),
                volume: Math.floor(Math.random() * 10000000) + 100000,
                marketCap: generateRandomMarketCap(),
                peRatio: (Math.random() * 30 + 5).toFixed(2) * 1
            }));
        }
        
        const pricesData = await pricesResponse.json();
        
        // Gabungkan data saham dengan data harga
        return stocks.map(stock => {
            const ticker = stock.ticker.replace('.JK', '');
            const priceInfo = pricesData[ticker] || {};
            
            return {
                symbol: ticker,
                company: stock.name,
                sector: stock.sector,
                lastPrice: priceInfo.lastPrice || generateRandomPrice(),
                change: priceInfo.change || generateRandomChange(),
                volume: priceInfo.volume || Math.floor(Math.random() * 10000000) + 100000,
                marketCap: priceInfo.marketCap || generateRandomMarketCap(),
                peRatio: priceInfo.peRatio || (Math.random() * 30 + 5).toFixed(2) * 1
            };
        });
    } catch (error) {
        console.error('Error enriching stock data:', error);
        // Fallback ke data harga acak jika terjadi error
        return stocks.map(stock => ({
            symbol: stock.ticker.replace('.JK', ''),
            company: stock.name,
            sector: stock.sector,
            lastPrice: generateRandomPrice(),
            change: generateRandomChange(),
            volume: Math.floor(Math.random() * 10000000) + 100000,
            marketCap: generateRandomMarketCap(),
            peRatio: (Math.random() * 30 + 5).toFixed(2) * 1
        }));
    }
}

// Helper function untuk generate random price
function generateRandomPrice() {
    // Generate random price between 100 and 20000
    return Math.floor(Math.random() * 19900) + 100;
}

// Helper function untuk generate random change
function generateRandomChange() {
    // Generate random change between -5% and +5%
    return (Math.random() * 10 - 5).toFixed(2) * 1;
}

// Helper function untuk generate random market cap
function generateRandomMarketCap() {
    // Generate random market cap between 100B and 500T
    return Math.floor(Math.random() * 500000000000000) + 100000000000;
}

// Setup event listeners
function setupEventListeners() {
    // Search input
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    
    searchInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            filterStocks();
        }
    });
    
    searchButton.addEventListener('click', filterStocks);
    
    // Filter buttons
    document.getElementById('applyFilters').addEventListener('click', filterStocks);
    document.getElementById('resetFilters').addEventListener('click', resetFilters);
    
    // Sort dropdown
    document.getElementById('sortBy').addEventListener('change', () => {
        sortStocks();
        renderCurrentPage();
    });
}

// Render tabel saham
function renderStockTable(stocks) {
    const tableBody = document.getElementById('stocksTableBody');
    const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
    const endIndex = Math.min(startIndex + ITEMS_PER_PAGE, stocks.length);
    const pageStocks = stocks.slice(startIndex, endIndex);
    
    // Jika tidak ada data
    if (stocks.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="bi bi-search text-muted fs-1"></i>
                    <p class="mt-3 text-muted">No stocks match your search criteria.</p>
                    <button class="btn btn-outline-primary mt-2" id="resetFiltersBtn">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters
                    </button>
                </td>
            </tr>
        `;
        
        document.getElementById('resetFiltersBtn').addEventListener('click', resetFilters);
        return;
    }
    
    // Render data saham
    let html = '';
    
    pageStocks.forEach(stock => {
        const isUp = stock.change >= 0;
        const changeClass = isUp ? 'text-success' : 'text-danger';
        const changeIcon = isUp ? 'bi-arrow-up' : 'bi-arrow-down';
        
        const marketCapClass = 
            stock.marketCap >= 10000000000000 ? 'market-cap-large' : 
            stock.marketCap >= 1000000000000 ? 'market-cap-medium' : 
            'market-cap-small';
        
        const marketCapLabel = 
            stock.marketCap >= 10000000000000 ? 'Large Cap' : 
            stock.marketCap >= 1000000000000 ? 'Mid Cap' : 
            'Small Cap';
        
        const isFavorite = favorites.includes(stock.symbol);
        const favoriteClass = isFavorite ? 'active' : '';
        
        html += `
            <tr class="stock-row" data-symbol="${stock.symbol}">
                <td>
                    <i class="bi bi-star-fill favorite-star ${favoriteClass}" data-symbol="${stock.symbol}"></i>
                </td>
                <td>
                    <span class="stock-symbol">${stock.symbol}</span>
                </td>
                <td>${stock.company}</td>
                <td>
                    <span class="sector-pill">${stock.sector}</span>
                </td>
                <td class="text-end stock-price">Rp ${formatNumber(stock.lastPrice)}</td>
                <td class="text-end ${changeClass}">
                    <i class="bi ${changeIcon}"></i> ${Math.abs(stock.change).toFixed(2)}%
                </td>
                <td class="text-end">${formatNumber(stock.volume)}</td>
                <td class="text-end">
                    <span class="market-cap-pill ${marketCapClass}">${marketCapLabel}</span>
                    <span class="d-block mt-1 small text-muted">Rp ${formatMarketCap(stock.marketCap)}</span>
                </td>
                <td class="text-end">${stock.peRatio.toFixed(2)}</td>
                <td class="text-end">
                    <a href="stock-basic.html?symbol=${stock.symbol}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-graph-up"></i>
                    </a>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
    
    // Tambahkan event listener untuk favorite stars
    document.querySelectorAll('.favorite-star').forEach(star => {
        star.addEventListener('click', (e) => {
            e.stopPropagation();
            const symbol = star.getAttribute('data-symbol');
            toggleFavorite(symbol);
            
            // Toggle class tanpa me-render ulang seluruh tabel
            if (star.classList.contains('active')) {
                star.classList.remove('active');
            } else {
                star.classList.add('active');
            }
        });
    });
    
    // Tambahkan event listener untuk stock rows
    document.querySelectorAll('.stock-row').forEach(row => {
        row.addEventListener('click', () => {
            const symbol = row.getAttribute('data-symbol');
            window.location.href = `stock-basic.html?symbol=${symbol}`;
        });
    });
}

// Render pagination
function renderPagination(totalItems) {
    const paginationContainer = document.getElementById('paginationContainer');
    const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
    
    // Jika hanya ada 1 halaman, sembunyikan pagination
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    let html = `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" aria-label="Previous" data-page="${currentPage - 1}">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Menentukan rentang halaman yang akan ditampilkan
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, startPage + 4);
    
    // Pastikan selalu menampilkan 5 tombol halaman jika totalPages >= 5
    if (endPage - startPage < 4 && totalPages > 4) {
        startPage = Math.max(1, endPage - 4);
    }
    
    // Tambahkan tombol untuk halaman pertama jika start > 1
    if (startPage > 1) {
        html += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="1">1</a>
            </li>
        `;
        
        // Tambahkan ellipsis jika start > 2
        if (startPage > 2) {
            html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
        }
    }
    
    // Tambahkan tombol halaman
    for (let i = startPage; i <= endPage; i++) {
        html += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }
    
    // Tambahkan ellipsis dan tombol untuk halaman terakhir
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
        }
        
        html += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
            </li>
        `;
    }
    
    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" aria-label="Next" data-page="${currentPage + 1}">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `;
    
    paginationContainer.innerHTML = html;
    
    // Tambahkan event listeners untuk tombol pagination
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const pageNum = parseInt(link.getAttribute('data-page'));
            
            if (!isNaN(pageNum) && pageNum !== currentPage && pageNum > 0 && pageNum <= totalPages) {
                currentPage = pageNum;
                renderCurrentPage();
                
                // Scroll to top of table
                document.querySelector('.stock-table').scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
}

// Filter data saham berdasarkan input pengguna
function filterStocks() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const sector = document.getElementById('sectorFilter').value;
    const marketCap = document.getElementById('marketCapFilter').value;
    
    // Reset current page
    currentPage = 1;
    
    // Filter data
    filteredStocks = allStocks.filter(stock => {
        const matchesSearch = 
            searchTerm === '' || 
            stock.symbol.toLowerCase().includes(searchTerm) || 
            stock.company.toLowerCase().includes(searchTerm);
            
        const matchesSector = 
            sector === 'all' || 
            stock.sector === sector;
            
        const matchesMarketCap = 
            marketCap === 'all' || 
            (marketCap === 'large' && stock.marketCap >= 10000000000000) || 
            (marketCap === 'medium' && stock.marketCap >= 1000000000000 && stock.marketCap < 10000000000000) || 
            (marketCap === 'small' && stock.marketCap < 1000000000000);
            
        return matchesSearch && matchesSector && matchesMarketCap;
    });
    
    // Sort dan render
    sortStocks();
    renderStockTable(filteredStocks);
    renderPagination(filteredStocks.length);
}

// Sorting data saham
function sortStocks() {
    const sortBy = document.getElementById('sortBy').value;
    
    filteredStocks.sort((a, b) => {
        switch (sortBy) {
            case 'symbol':
                return a.symbol.localeCompare(b.symbol);
            case 'price':
                return b.lastPrice - a.lastPrice;
            case 'change':
                return b.change - a.change;
            case 'volume':
                return b.volume - a.volume;
            case 'marketCap':
                return b.marketCap - a.marketCap;
            default:
                return a.symbol.localeCompare(b.symbol);
        }
    });
}

// Reset filter
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('sectorFilter').value = 'all';
    document.getElementById('marketCapFilter').value = 'all';
    document.getElementById('sortBy').value = 'symbol';
    
    currentPage = 1;
    filteredStocks = [...allStocks];
    
    sortStocks();
    renderStockTable(filteredStocks);
    renderPagination(filteredStocks.length);
}

// Render halaman saat ini
function renderCurrentPage() {
    renderStockTable(filteredStocks);
}

// Toggle favorite
function toggleFavorite(symbol) {
    const index = favorites.indexOf(symbol);
    
    if (index === -1) {
        // Add to favorites
        favorites.push(symbol);
    } else {
        // Remove from favorites
        favorites.splice(index, 1);
    }
    
    // Save to localStorage
    localStorage.setItem('favoriteStocks', JSON.stringify(favorites));
}

// Format angka
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Format market cap
function formatMarketCap(num) {
    if (num >= 1000000000000) {
        return (num / 1000000000000).toFixed(2) + 'T';
    } else if (num >= 1000000000) {
        return (num / 1000000000).toFixed(2) + 'B';
    } else if (num >= 1000000) {
        return (num / 1000000).toFixed(2) + 'M';
    } else {
        return formatNumber(num);
    }
}

// Generate mock data untuk daftar saham Indonesia
function generateMockStocks() {
    const sectors = [
        'Banking', 'Consumer Goods', 'Infrastructure', 'Mining', 
        'Property', 'Technology', 'Telecommunications'
    ];
    
    const stockSymbols = [
        'BBRI', 'BBCA', 'BMRI', 'TLKM', 'ASII', 'UNVR', 'HMSP', 'ICBP', 
        'INDF', 'ANTM', 'PTBA', 'KLBF', 'UNTR', 'ADRO', 'PGAS', 'SMGR',
        'BRPT', 'INCO', 'JSMR', 'EXCL', 'MNCN', 'INKP', 'TPIA', 'CPIN',
        'JPFA', 'AKRA', 'MDKA', 'TOWR', 'MEDC', 'BSDE', 'CTRA', 'MAPI',
        'ERAA', 'BTPS', 'MYOR', 'PWON', 'ESSA', 'BBNI', 'BRIS', 'BBTN',
        'ITMG', 'INDY', 'TBIG', 'SRTG', 'SMRA', 'ACES', 'DMAS', 'DOID',
        'MIKA', 'EMTK', 'RALS', 'ELSA', 'TKIM', 'GOTO', 'BUKA', 'AMRT',
        'LPPF', 'MPPA', 'MAPI', 'MTDL', 'BNLI', 'BTPN', 'BBKP', 'ISAT',
        'SILO', 'PNBN', 'BTPS', 'MAYA', 'MEGA', 'BNGA', 'IHSG', 'NISP',
        'DNAR', 'DOID', 'APEX', 'AALI', 'LSIP', 'SSMS', 'DSNG', 'SIMP',
        'SGRO', 'BWPT', 'PALM', 'ANJT', 'BYAN', 'GEMS', 'HRUM', 'MBAP',
        'ADRO', 'ITMG', 'INDY', 'PTRO', 'BOSS', 'TOBA', 'ARII', 'BSSR',
        'ZINC', 'FIRE', 'MPMX', 'LINK', 'BNBR', 'BMTR'
    ];
    
    const companyNames = [
        'Bank Rakyat Indonesia', 'Bank Central Asia', 'Bank Mandiri', 'Telkom Indonesia', 
        'Astra International', 'Unilever Indonesia', 'HM Sampoerna', 'Indofood CBP Sukses Makmur', 
        'Indofood Sukses Makmur', 'Aneka Tambang', 'Bukit Asam', 'Kalbe Farma', 
        'United Tractors', 'Adaro Energy', 'Perusahaan Gas Negara', 'Semen Indonesia', 
        'Barito Pacific', 'Vale Indonesia', 'Jasa Marga', 'XL Axiata', 
        'Media Nusantara Citra', 'Indah Kiat Pulp & Paper', 'Chandra Asri Petrochemical', 'Charoen Pokphand Indonesia',
        'Japfa Comfeed Indonesia', 'AKR Corporindo', 'Merdeka Copper Gold', 'Sarana Menara Nusantara', 
        'Medco Energi', 'Bumi Serpong Damai', 'Ciputra Development', 'Mitra Adiperkasa',
        'Erajaya Swasembada', 'Bank BTPN Syariah', 'Mayora Indah', 'Pakuwon Jati', 
        'Surya Esa Perkasa', 'Bank Negara Indonesia', 'Bank BRI Syariah', 'Bank Tabungan Negara',
        'Indo Tambangraya Megah', 'Indika Energy', 'Tower Bersama Infrastructure', 'Saratoga Investama Sedaya', 
        'Summarecon Agung', 'Ace Hardware Indonesia', 'Puradelta Lestari', 'Delta Dunia Makmur',
        'Mitra Keluarga Karyasehat', 'Elang Mahkota Teknologi', 'Ramayana Lestari Sentosa', 'Elnusa', 
        'Pabrik Kertas Tjiwi Kimia', 'GoTo Gojek Tokopedia', 'Bukalapak', 'Sumber Alfaria Trijaya',
        'Matahari Department Store', 'Matahari Putra Prima', 'Mitra Adiperkasa', 'Metrodata Electronics', 
        'Bank Permata', 'Bank BTPN', 'Bank KB Bukopin', 'Indosat Ooredoo',
        'Siloam International Hospitals', 'Bank Pan Indonesia', 'Bank BTPN Syariah', 'Bank Mayapada', 
        'Bank Mega', 'Bank CIMB Niaga', 'IDX Composite', 'Bank OCBC NISP',
        'Bank Dinar Indonesia', 'Delta Dunia Makmur', 'Apexindo Pratama Duta', 'Astra Agro Lestari', 
        'PP London Sumatra Indonesia', 'Sawit Sumbermas Sarana', 'Dharma Satya Nusantara', 'Salim Ivomas Pratama',
        'Sampoerna Agro', 'Eagle High Plantations', 'Provident Agro', 'Austindo Nusantara Jaya', 
        'Bayan Resources', 'Golden Energy Mines', 'Harum Energy', 'Mitrabara Adiperdana',
        'Adaro Energy', 'Indo Tambangraya Megah', 'Indika Energy', 'Petrosea', 
        'Borneo Olah Sarana Sukses', 'Toba Bara Sejahtera', 'Atlas Resources', 'Baramulti Suksessarana',
        'Kapuas Prima Coal', 'Alfa Energi Investama', 'Mitra Pinasthika Mustika', 'Link Net',
        'Bakrie & Brothers', 'Global Mediacom'
    ];
    
    // Create 100 mock stocks
    const stocks = [];
    
    for (let i = 0; i < 100; i++) {
        const symbol = stockSymbols[i % stockSymbols.length];
        const company = companyNames[i % companyNames.length];
        const sector = sectors[Math.floor(Math.random() * sectors.length)];
        const lastPrice = Math.floor(Math.random() * 10000) + 500; // 500 - 10,500
        const change = (Math.random() * 6) - 3; // -3% to +3%
        const volume = Math.floor(Math.random() * 100000000) + 1000000; // 1M - 100M
        const marketCap = lastPrice * (Math.floor(Math.random() * 10000000) + 100000); // Market cap based on price
        const peRatio = Math.random() * 30 + 5; // 5 - 35
        
        stocks.push({
            symbol,
            company,
            sector,
            lastPrice,
            change,
            volume,
            marketCap,
            peRatio
        });
    }
    
    return stocks;
}
