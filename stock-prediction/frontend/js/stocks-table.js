// stocks-table.js - Menangani tabel daftar saham dengan fitur pagination, sorting, dan integrasi dengan API

/**
 * Mengonfigurasi dan menampilkan tabel saham dengan pagination dan sorting
 * @param {string} tableId - ID element tabel
 * @param {Object|Array} stocksData - Data saham dari API atau array data statis
 */
function initStockTable(tableId, stocksData) {
    // Penanganan data dari API vs data statis
    let stocks = Array.isArray(stocksData) ? stocksData : (stocksData?.stocks || []);
    let meta = Array.isArray(stocksData) ? null : (stocksData?.meta || null);
    let isUsingOfflineData = Array.isArray(stocksData) ? true : (stocksData?.isOffline || false);
    
    // Konfigurasi pagination
    let currentPage = meta?.page || 1;
    const rowsPerPage = meta?.limit || 10;
    let totalItems = meta?.total || stocks.length;
    let filteredData = [...stocks]; // Menyimpan data yang sudah difilter
    let sortColumn = 'code';
    let sortDirection = 'asc';
    let isServerSideProcessing = !isUsingOfflineData && meta !== null;
    
    // State untuk pencarian dan filter
    let currentSearchTerm = '';
    let currentSectorFilter = 'all';
    
    // Status loading
    let isLoading = false;
    
    // DOM elements
    const tableElement = document.getElementById(tableId);
    const tableBody = tableElement.querySelector('tbody');
    const paginationContainer = document.getElementById('stocksPagination');
    const searchInput = document.getElementById('stockSearch');
    const sectorFilter = document.getElementById('sectorFilter');
    const statusContainer = document.createElement('div');
    
    // Tambahkan status container
    statusContainer.className = 'table-status-container';
    tableElement.parentNode.insertBefore(statusContainer, tableElement.nextSibling);
    
    // Fungsi untuk menampilkan loading indicator
    function showLoading() {
        isLoading = true;
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">Memuat...</span>
                        </div>
                        <span>Memuat data saham...</span>
                    </div>
                </td>
            </tr>
        `;
    }
    
    // Fungsi untuk menampilkan pesan error
    function showError(message) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <div class="text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${message || 'Terjadi kesalahan saat memuat data'}
                    </div>
                    <button class="btn btn-sm btn-outline-primary mt-3" onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Muat Ulang
                    </button>
                </td>
            </tr>
        `;
    }
    
    // Fungsi untuk menampilkan pesan tidak ada data
    function showNoData() {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <div class="text-muted">
                        <i class="bi bi-inbox me-2"></i>
                        Tidak ada data saham yang ditemukan
                    </div>
                </td>
            </tr>
        `;
    }

    // Fungsi untuk menampilkan data pada halaman tertentu
    function displayTableData() {
        // Jika tidak ada data sama sekali
        if (!filteredData || filteredData.length === 0) {
            showNoData();
            updatePaginationInfo();
            return;
        }
        
        // Reset table body
        tableBody.innerHTML = '';
        
        // Tentukan data yang akan ditampilkan
        let paginatedData;
        if (isServerSideProcessing) {
            // Untuk server-side, kita gunakan semua data yang diterima dari API
            paginatedData = filteredData;
        } else {
            // Untuk client-side pagination, kita potong data sesuai halaman
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, filteredData.length);
            paginatedData = filteredData.slice(startIndex, endIndex);
        }
        
        // Tambahkan data ke tabel
        paginatedData.forEach(stock => {
            const row = document.createElement('tr');
            
            // Pastikan nilai-nilai valid (defensive programming)
            const price = parseFloat(stock.price || 0);
            const change = parseFloat(stock.change || 0);
            const volume = parseInt(stock.volume || 0, 10);
            
            // Tambahkan class untuk styling perubahan harga
            const changeClass = change >= 0 ? 'price-up' : 'price-down';
            const changeSymbol = change >= 0 ? '+' : '';
            const arrowIcon = change >= 0 
                ? '<i class="bi bi-arrow-up-short animate-arrow"></i>' 
                : '<i class="bi bi-arrow-down-short animate-arrow"></i>';
            
            // Format harga dan volume
            const formattedPrice = new Intl.NumberFormat('id-ID').format(price);
            
            // Format volume berdasarkan ukurannya
            let formattedVolume;
            if (volume >= 1000000000) {
                formattedVolume = (volume / 1000000000).toFixed(1) + 'B';
            } else if (volume >= 1000000) {
                formattedVolume = (volume / 1000000).toFixed(1) + 'M';
            } else if (volume >= 1000) {
                formattedVolume = (volume / 1000).toFixed(1) + 'K';
            } else {
                formattedVolume = volume.toString();
            }
            
            // Pastikan kode saham valid untuk link detail
            // Dari API bisa berupa code atau symbol, pastikan keduanya dihandle
            const stockSymbol = stock.code || stock.symbol || '';
            
            // Validasi tambahan untuk memastikan stockSymbol tidak kosong
            if (!stockSymbol) {
                console.warn('Ditemukan data saham tanpa kode/simbol:', stock);
            }
            
            // Persiapkan URL detail dan prediksi secara dinamis dari konfigurasi
            let detailUrl, predictionUrl;
            let dynamicUrlsActive = false;
            
            // Debugging check untuk config.js
            console.log('Config Status:', {
                configDetected: typeof PAGE_URLS !== 'undefined',
                generateUrlFunctionExists: typeof generatePageUrl === 'function'
            });
            
            // Gunakan generatePageUrl dari config.js jika tersedia
            if (typeof generatePageUrl === 'function') {
                detailUrl = generatePageUrl('stockDetail', { symbol: stockSymbol });
                predictionUrl = generatePageUrl('stockPrediction', { symbol: stockSymbol });
                dynamicUrlsActive = true;
                console.log(`Dynamic URLs generated for ${stockSymbol}:`, { detailUrl, predictionUrl });
            } else {
                // Fallback ke URL hardcode jika konfigurasi tidak tersedia
                detailUrl = `stock-basic.html?symbol=${encodeURIComponent(stockSymbol)}`;
                predictionUrl = `prediction.html?symbol=${encodeURIComponent(stockSymbol)}`;
                console.warn('Using hardcoded URLs - config.js not detected');
            }
            
            // Buat HTML untuk tiap row
            // Tambahkan kelas dinamis jika URL menggunakan config.js
            const dynamicClass = dynamicUrlsActive ? 'dynamic-url' : '';
            
            row.innerHTML = `
                <td><a href="${detailUrl}" class="fw-bold ${dynamicClass}">${stockSymbol}</a></td>
                <td>${stock.name || ''}</td>
                <td>${stock.sector || ''}</td>
                <td class="text-end">Rp ${formattedPrice}</td>
                <td class="text-end ${changeClass}">${arrowIcon} ${changeSymbol}${Math.abs(change).toFixed(2)}%</td>
                <td class="text-end">${formattedVolume}</td>
                <td class="text-center">
                    <a href="${detailUrl}" class="stock-action detail ${dynamicClass}" title="Detail Saham ${stockSymbol}" data-stock="${stockSymbol}" data-dynamic="${dynamicUrlsActive}">
                        <i class="bi bi-graph-up"></i>
                    </a>
                    <a href="${predictionUrl}" class="stock-action predict ${dynamicClass}" title="Prediksi AI ${stockSymbol}" data-stock="${stockSymbol}" data-dynamic="${dynamicUrlsActive}">
                        <i class="bi bi-magic"></i>
                    </a>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Update pagination info
        updatePaginationInfo();
        
        // Tampilkan status koneksi jika menggunakan data offline
        updateConnectionStatus();
    }
    
    // Fungsi untuk memperbarui status koneksi
    function updateConnectionStatus() {
        // Bersihkan status container
        statusContainer.innerHTML = '';
        
        // Tampilkan indikator jika menggunakan data offline
        if (isUsingOfflineData) {
            const statusAlert = document.createElement('div');
            statusAlert.className = 'alert alert-warning py-1 px-3 mt-2 mb-0 d-flex align-items-center small';
            statusAlert.innerHTML = `
                <i class="bi bi-wifi-off me-2"></i>
                <div>Menggunakan data offline. Beberapa informasi mungkin tidak terbaru.</div>
                <button class="btn btn-sm ms-auto" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            `;
            statusContainer.appendChild(statusAlert);
        }
    }

    // Fungsi untuk memperbarui info pagination
    function updatePaginationInfo() {
        // Hitung total halaman berdasarkan mode (server-side atau client-side)
        const totalPages = isServerSideProcessing 
            ? Math.ceil(totalItems / rowsPerPage) 
            : Math.ceil(filteredData.length / rowsPerPage);
            
        // Update info pagination di UI
        if (document.getElementById('totalPages')) {
            document.getElementById('totalPages').textContent = totalPages || 0;
        }
        
        if (document.getElementById('currentPage')) {
            document.getElementById('currentPage').textContent = currentPage || 1; 
        }
        
        // Update jumlah total saham
        if (document.getElementById('totalStocks')) {
            document.getElementById('totalStocks').textContent = isServerSideProcessing 
                ? totalItems 
                : filteredData.length;
        }
        
        // Buat pagination
        renderPagination(totalPages);
    }
    
    // Fungsi untuk membuat pagination yang lebih sederhana
    function renderPagination(totalPages) {
        if (!paginationContainer) return;
        
        // Reset pagination
        paginationContainer.innerHTML = '';
        
        if (totalPages <= 1) {
            return; // Tidak perlu pagination jika hanya 1 halaman
        }
        
        const ul = document.createElement('ul');
        ul.className = 'pagination';
        
        // Button Previous
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        
        const prevLink = document.createElement('a');
        prevLink.className = 'page-link';
        prevLink.href = '#';
        prevLink.innerHTML = '<i class="bi bi-chevron-left"></i>';
        prevLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                goToPage(currentPage - 1);
            }
        });
        
        prevLi.appendChild(prevLink);
        ul.appendChild(prevLi);
        
        // Tentukan halaman yang ditampilkan
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        // Pastikan selalu menampilkan 5 halaman jika totalPages > 5
        if (totalPages > 5 && endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }
        
        // Button First jika tidak dimulai dari halaman 1
        if (startPage > 1) {
            const firstLi = document.createElement('li');
            firstLi.className = 'page-item';
            
            const firstLink = document.createElement('a');
            firstLink.className = 'page-link';
            firstLink.href = '#';
            firstLink.textContent = '1';
            firstLink.addEventListener('click', (e) => {
                e.preventDefault();
                goToPage(1);
            });
            
            firstLi.appendChild(firstLink);
            ul.appendChild(firstLi);
            
            // Ellipsis setelah first
            if (startPage > 2) {
                const ellipsisLi = document.createElement('li');
                ellipsisLi.className = 'page-item disabled';
                ellipsisLi.innerHTML = '<a class="page-link">...</a>';
                ul.appendChild(ellipsisLi);
            }
        }
        
        // Loop untuk button halaman
        for (let i = startPage; i <= endPage; i++) {
            const pageLi = document.createElement('li');
            pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
            
            const pageLink = document.createElement('a');
            pageLink.className = 'page-link';
            pageLink.href = '#';
            pageLink.textContent = i;
            pageLink.addEventListener('click', (e) => {
                e.preventDefault();
                goToPage(i);
            });
            
            pageLi.appendChild(pageLink);
            ul.appendChild(pageLi);
        }
        
        // Ellipsis sebelum last page jika diperlukan
        if (endPage < totalPages - 1) {
            const ellipsisLi = document.createElement('li');
            ellipsisLi.className = 'page-item disabled';
            ellipsisLi.innerHTML = '<a class="page-link">...</a>';
            ul.appendChild(ellipsisLi);
        }
        
        // Button Last page jika tidak diakhiri dengan halaman terakhir
        if (endPage < totalPages) {
            const lastLi = document.createElement('li');
            lastLi.className = 'page-item';
            
            const lastLink = document.createElement('a');
            lastLink.className = 'page-link';
            lastLink.href = '#';
            lastLink.textContent = totalPages;
            lastLink.addEventListener('click', (e) => {
                e.preventDefault();
                goToPage(totalPages);
            });
            
            lastLi.appendChild(lastLink);
            ul.appendChild(lastLi);
        }
        
        // Button Next
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        
        const nextLink = document.createElement('a');
        nextLink.className = 'page-link';
        nextLink.href = '#';
        nextLink.innerHTML = '<i class="bi bi-chevron-right"></i>';
        nextLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        });
        
        nextLi.appendChild(nextLink);
        ul.appendChild(nextLi);
        
        paginationContainer.appendChild(ul);
    }
    
    // Fungsi untuk berpindah ke halaman tertentu
    // Fungsi ini dapat melakukan server-side request untuk pagination jika diperlukan
    async function goToPage(pageNumber) {
        if (isLoading) return; // Jangan pindah halaman saat loading
        
        // Jika menggunakan server-side pagination, kita perlu mengambil data lagi
        if (isServerSideProcessing) {
            try {
                currentPage = pageNumber;
                await loadStocksData({
                    page: currentPage,
                    search: currentSearchTerm,
                    sector: currentSectorFilter
                });
            } catch (error) {
                console.error('Error navigating to page:', error);
                showError('Gagal memuat data halaman ' + pageNumber);
            }
        } else {
            // Client-side pagination
            currentPage = pageNumber;
            displayTableData();
        }
    }
    
    // Fungsi untuk memperbarui indikator sorting pada header tabel
    function updateSortIndicators() {
        // Reset semua indikator
        const headers = tableElement.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.classList.remove('sort-asc', 'sort-desc');
            header.querySelector('.sort-icon')?.remove();
        });
        
        // Tambahkan indikator ke header yang sedang aktif
        const activeHeader = tableElement.querySelector(`th[data-sort="${sortColumn}"]`);
        if (activeHeader) {
            activeHeader.classList.add(`sort-${sortDirection}`);
            
            // Tambahkan ikon
            const iconSpan = document.createElement('span');
            iconSpan.className = 'sort-icon ms-1';
            iconSpan.innerHTML = sortDirection === 'asc' 
                ? '<i class="bi bi-arrow-up-short"></i>' 
                : '<i class="bi bi-arrow-down-short"></i>';
            
            activeHeader.appendChild(iconSpan);
        }
    }
    
    // Fungsi untuk men-sort data berdasarkan kolom
    function sortData(column) {
        // Jika kolom sama dengan kolom sebelumnya, toggle direction
        if (column === sortColumn) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortColumn = column;
            sortDirection = 'asc';
        }
        
        // Jika menggunakan server-side processing, minta data yang sudah disortir dari server
        if (isServerSideProcessing) {
            loadStocksData({
                page: currentPage,
                search: currentSearchTerm,
                sector: currentSectorFilter,
                sortBy: column,
                sortDirection: sortDirection
            });
            return;
        }
        
        // Untuk client-side sorting
        filteredData.sort((a, b) => {
            let valueA = a[column];
            let valueB = b[column];
            
            // Konversi ke number jika perlu
            if (column === 'price' || column === 'change' || column === 'volume') {
                valueA = parseFloat(valueA);
                valueB = parseFloat(valueB);
            } else {
                valueA = String(valueA).toLowerCase();
                valueB = String(valueB).toLowerCase();
            }
            
            // Urutkan
            if (sortDirection === 'asc') {
                return valueA > valueB ? 1 : -1;
            } else {
                return valueA < valueB ? 1 : -1;
            }
        });
        
        // Reset ke halaman pertama dan tampilkan data
        currentPage = 1;
        displayTableData();
        
        // Update header tabel untuk menunjukkan kolom yang di-sort
        updateSortIndicators();
    }
    
    // Fungsi untuk memuat data saham dari API
    async function loadStocksData(options = {}) {
        // Set status loading
        isLoading = true;
        showLoading();
        
        try {
            // Update state untuk pencarian dan filter
            currentSearchTerm = options.search || '';
            currentSectorFilter = options.sector || 'all';
            
            // Jika fetchStocks tersedia, gunakan untuk mendapatkan data dari API
            if (typeof fetchStocks === 'function') {
                const response = await fetchStocks({
                    page: options.page || currentPage,
                    limit: options.limit || rowsPerPage,
                    search: currentSearchTerm,
                    sector: currentSectorFilter !== 'all' ? currentSectorFilter : undefined,
                    sortBy: options.sortBy || sortColumn,
                    sortDirection: options.sortDirection || sortDirection
                });
                
                // Update data dalam state
                stocks = response.stocks || [];
                filteredData = [...stocks];
                meta = response.meta || null;
                totalItems = meta?.total || stocks.length;
                currentPage = meta?.page || currentPage;
                isUsingOfflineData = response.isOffline || false;
                isServerSideProcessing = !isUsingOfflineData && meta !== null;
                
                // Tampilkan data
                displayTableData();
            } else {
                throw new Error('fetchStocks function not available');
            }
        } catch (error) {
            console.error('Error loading stocks data:', error);
            showError('Gagal memuat data saham: ' + (error.message || 'Terjadi kesalahan'));
            
            // Fallback ke data statis jika tersedia
            if (typeof indonesianStocks !== 'undefined' && indonesianStocks.length > 0) {
                stocks = [...indonesianStocks];
                filteredData = [...stocks];
                isUsingOfflineData = true;
                isServerSideProcessing = false;
                displayTableData();
            }
        } finally {
            isLoading = false;
        }
    }

    // Fungsi untuk memfilter data berdasarkan pencarian
    async function filterData() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedSector = sectorFilter.value;
        
        // Update state
        currentSearchTerm = searchTerm;
        currentSectorFilter = selectedSector;
        
        // Jika menggunakan server-side processing, minta data baru dari API
        if (isServerSideProcessing) {
            await loadStocksData({
                page: 1, // Reset ke halaman pertama saat filter berubah
                search: searchTerm,
                sector: selectedSector !== 'all' ? selectedSector : undefined
            });
            return;
        }
        
        // Untuk client-side filtering
        filteredData = stocks.filter(stock => {
            // Filter berdasarkan term pencarian (defensive coding untuk null/undefined)
            const code = (stock.code || stock.symbol || '').toLowerCase();
            const name = (stock.name || '').toLowerCase();
            const matchesSearch = searchTerm === '' || 
                code.includes(searchTerm) || 
                name.includes(searchTerm);
            
            // Filter berdasarkan sektor
            const stockSector = stock.sector || '';
            const matchesSector = selectedSector === 'all' || stockSector === selectedSector;
            
            return matchesSearch && matchesSector;
        });
        
        // Reset ke halaman pertama dan tampilkan data
        currentPage = 1;
        displayTableData();
    }
    
    // Setup event listener untuk kolom yang bisa di-sort
    const sortableHeaders = tableElement.querySelectorAll('th[data-sort]');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const column = header.dataset.sort;
            sortData(column);
        });
    });
    
    // Fungsi debounce untuk pencarian
    function debounce(func, delay) {
        let timer;
        return function(...args) {
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(this, args);
            }, delay);
        };
    }
    
    // Setup event listener untuk pencarian dengan debounce
    const debouncedFilter = debounce(filterData, 400); // 400ms delay
    searchInput.addEventListener('input', debouncedFilter);
    
    // Setup event listener untuk filter sektor
    sectorFilter.addEventListener('change', filterData);
    
    // Tambahkan event listener untuk tombol enter pada search input
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterData(); // Bypass debounce saat tekan Enter
        }
    });
    
    // Populasi dropdown filter sektor
    function populateSectorFilter() {
        // Dapatkan daftar sektor unik
        const sectors = [...new Set(stocks.filter(stock => stock.sector).map(stock => stock.sector))].sort();
        
        // Tambahkan opsi default
        sectorFilter.innerHTML = '<option value="all">Semua Sektor</option>';
        
        // Tambahkan setiap sektor sebagai opsi
        sectors.forEach(sector => {
            if (sector) { // Pastikan sector tidak null/undefined/empty string
                const option = document.createElement('option');
                option.value = sector;
                option.textContent = sector;
                sectorFilter.appendChild(option);
            }
        });
        
        // Jika ada currentSectorFilter yang sudah dipilih sebelumnya, set kembali
        if (currentSectorFilter && currentSectorFilter !== 'all') {
            sectorFilter.value = currentSectorFilter;
        }
    }
    
    // Inisialisasi
    populateSectorFilter();
    sortData(sortColumn); // Initial sort
    displayTableData(); // Initial display
}

// Inisialisasi tabel saat dokumen siap
document.addEventListener('DOMContentLoaded', async function() {
    if (document.getElementById('stocksTable')) {
        try {
            // CSS untuk status koneksi dan loading indikator
            addTableStyles();
            
            // Inisialisasi tabel dasar
            let initialData = [];
            
            // Coba ambil data dari API terlebih dahulu
            if (typeof fetchStocks === 'function') {
                try {
                    initialData = await fetchStocks();
                    console.log('Data berhasil diambil dari API:', initialData);
                } catch (apiError) {
                    console.error('Gagal mengambil data dari API:', apiError);
                    // Fallback ke data statis
                    if (typeof indonesianStocks !== 'undefined') {
                        initialData = {
                            stocks: indonesianStocks,
                            meta: null,
                            isOffline: true
                        };
                        console.log('Menggunakan data offline fallback');
                    }
                }
            } else {
                console.warn('fetchStocks tidak tersedia, menggunakan data statis');
                // Fallback ke data statis
                if (typeof indonesianStocks !== 'undefined') {
                    initialData = {
                        stocks: indonesianStocks,
                        meta: null,
                        isOffline: true
                    };
                }
            }
            
            // Inisialisasi tabel
            initStockTable('stocksTable', initialData);
        } catch (error) {
            console.error('Error initializing stocks table:', error);
            // Fallback ke data statis jika terjadi error
            if (typeof indonesianStocks !== 'undefined') {
                initStockTable('stocksTable', {
                    stocks: indonesianStocks,
                    meta: null,
                    isOffline: true
                });
            }
        }
    }
});

// Fungsi untuk menambahkan styles untuk tabel
function addTableStyles() {
    // Tambahkan style untuk indikator status koneksi
    const style = document.createElement('style');
    style.textContent = `
        .table-status-container {
            margin-top: 0.5rem;
        }
        .price-up {
            color: #28a745;
            font-weight: 500;
        }
        .price-down {
            color: #dc3545;
            font-weight: 500;
        }
        .animate-arrow {
            display: inline-block;
        }
        .stock-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin: 0 2px;
            transition: all 0.2s;
        }
        .stock-action.detail {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }
        .stock-action.predict {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        .stock-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th.sort-asc, th.sort-desc {
            background-color: rgba(0, 123, 255, 0.05);
            position: relative;
        }
    `;
    document.head.appendChild(style);
}
