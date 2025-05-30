// stocks-table.js - Menangani tabel daftar saham dengan fitur pagination dan sorting

/**
 * Mengonfigurasi dan menampilkan tabel saham dengan pagination dan sorting
 * @param {string} tableId - ID element tabel
 * @param {Array} stocksData - Array data saham
 */
function initStockTable(tableId, stocksData) {
    // Konfigurasi pagination
    let currentPage = 1;
    const rowsPerPage = 10;
    let filteredData = [...stocksData]; // Menyimpan data yang sudah difilter
    let sortColumn = 'code';
    let sortDirection = 'asc';
    
    // DOM elements
    const tableElement = document.getElementById(tableId);
    const tableBody = tableElement.querySelector('tbody');
    const paginationContainer = document.getElementById('stocksPagination');
    const searchInput = document.getElementById('stockSearch');
    const sectorFilter = document.getElementById('sectorFilter');
    
    // Fungsi untuk menampilkan data pada halaman tertentu
    function displayTableData() {
        // Hitung index awal dan akhir untuk data pada halaman saat ini
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        const paginatedData = filteredData.slice(startIndex, endIndex);
        
        // Reset table body
        tableBody.innerHTML = '';
        
        // Tambahkan data ke tabel
        paginatedData.forEach(stock => {
            const row = document.createElement('tr');
            
            // Tambahkan class untuk styling perubahan harga
            const changeClass = parseFloat(stock.change) >= 0 ? 'price-up' : 'price-down';
            const changeSymbol = parseFloat(stock.change) >= 0 ? '+' : '';
            const arrowIcon = parseFloat(stock.change) >= 0 
                ? '<i class="bi bi-arrow-up-short animate-arrow"></i>' 
                : '<i class="bi bi-arrow-down-short animate-arrow"></i>';
            
            // Format harga dan volume
            const formattedPrice = new Intl.NumberFormat('id-ID').format(stock.price);
            const formattedVolume = (stock.volume/1000000).toFixed(1) + 'M';
            
            // Buat HTML untuk tiap row
            row.innerHTML = `
                <td><a href="stock-basic.html?symbol=${stock.code}">${stock.code}</a></td>
                <td>${stock.name}</td>
                <td>${stock.sector}</td>
                <td class="text-end">Rp ${formattedPrice}</td>
                <td class="text-end ${changeClass}">${arrowIcon} ${changeSymbol}${stock.change}%</td>
                <td class="text-end">${formattedVolume}</td>
                <td class="text-center">
                    <a href="stock-basic.html?symbol=${stock.code}" class="stock-action detail" title="Detail Saham">
                        <i class="bi bi-graph-up"></i>
                    </a>
                    <a href="prediction.html?symbol=${stock.code}" class="stock-action predict" title="Prediksi AI">
                        <i class="bi bi-magic"></i>
                    </a>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Update pagination info
        updatePaginationInfo();
    }
    
    // Fungsi untuk memperbarui info pagination
    function updatePaginationInfo() {
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);
        document.getElementById('totalPages').textContent = totalPages;
        document.getElementById('currentPage').textContent = currentPage;
        
        // Update jumlah total saham
        document.getElementById('totalStocks').textContent = filteredData.length;
        
        // Buat pagination
        renderPagination(totalPages);
    }
    
    // Fungsi untuk membuat pagination yang lebih sederhana
    function renderPagination(totalPages) {
        paginationContainer.innerHTML = '';
        
        // Tombol Previous
        const prevItem = document.createElement('li');
        prevItem.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevItem.innerHTML = '<a class="page-link" href="#">&laquo;</a>';
        prevItem.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                displayTableData();
            }
        });
        paginationContainer.appendChild(prevItem);
        
        // Halaman yang ditampilkan (maksimal 5 halaman)
        const maxPagesToShow = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
        
        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }
        
        // Tambahkan tombol halaman
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('li');
            pageButton.className = `page-item ${i === currentPage ? 'active' : ''}`;
            pageButton.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageButton.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                displayTableData();
            });
            paginationContainer.appendChild(pageButton);
        }
        
        // Tambahkan tombol Next
        const nextButton = document.createElement('li');
        nextButton.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextButton.innerHTML = '<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
        nextButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                displayTableData();
            }
        });
        paginationContainer.appendChild(nextButton);
        
        // Update info total data
        document.getElementById('totalStocks').textContent = filteredData.length;
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
        
        // Urutkan data
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
    
    // Fungsi untuk memfilter data berdasarkan pencarian
    function filterData() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedSector = sectorFilter.value;
        
        filteredData = stocksData.filter(stock => {
            // Filter berdasarkan term pencarian
            const matchesSearch = 
                stock.code.toLowerCase().includes(searchTerm) || 
                stock.name.toLowerCase().includes(searchTerm);
            
            // Filter berdasarkan sektor
            const matchesSector = selectedSector === 'all' || stock.sector === selectedSector;
            
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
    
    // Setup event listener untuk pencarian
    searchInput.addEventListener('input', filterData);
    
    // Setup event listener untuk filter sektor
    sectorFilter.addEventListener('change', filterData);
    
    // Populasi dropdown filter sektor
    function populateSectorFilter() {
        // Dapatkan daftar sektor unik
        const sectors = [...new Set(stocksData.map(stock => stock.sector))].sort();
        
        // Tambahkan opsi default
        sectorFilter.innerHTML = '<option value="all">Semua Sektor</option>';
        
        // Tambahkan setiap sektor sebagai opsi
        sectors.forEach(sector => {
            const option = document.createElement('option');
            option.value = sector;
            option.textContent = sector;
            sectorFilter.appendChild(option);
        });
    }
    
    // Inisialisasi
    populateSectorFilter();
    sortData(sortColumn); // Initial sort
    displayTableData(); // Initial display
}

// Inisialisasi tabel saat dokumen siap
document.addEventListener('DOMContentLoaded', function() {
    if (typeof indonesianStocks !== 'undefined' && document.getElementById('stocksTable')) {
        initStockTable('stocksTable', indonesianStocks);
    }
});
