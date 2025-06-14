/**
 * currency-utils.js
 * Utilitas untuk format mata uang dan nilai numerik di aplikasi ZAHAAM
 */

/**
 * Format nilai ke dalam format mata uang Rupiah
 * @param {number} amount - Nilai yang akan diformat
 * @param {boolean} includeCurrency - Menentukan apakah simbol mata uang (Rp) disertakan
 * @returns {string} Nilai yang sudah diformat
 */
function formatCurrency(amount, includeCurrency = true) {
    if (amount === null || amount === undefined || isNaN(amount)) {
        return includeCurrency ? 'Rp -' : '-';
    }
    
    const formattedAmount = new Intl.NumberFormat('id-ID').format(amount);
    return includeCurrency ? `Rp${formattedAmount}` : formattedAmount;
}

/**
 * Format perubahan harga dengan tanda +/- dan persentase
 * @param {number} change - Nilai perubahan dalam persentase
 * @returns {string} Perubahan yang sudah diformat dengan tanda +/-
 */
function formatPriceChange(change) {
    if (change === null || change === undefined || isNaN(change)) {
        return '-';
    }
    
    const prefix = change >= 0 ? '+' : '';
    return `${prefix}${change.toFixed(2)}%`;
}

/**
 * Mendapatkan kelas CSS berdasarkan perubahan harga
 * @param {number} change - Nilai perubahan 
 * @returns {string} Nama kelas CSS (price-up, price-down, atau kosong)
 */
function getPriceChangeColor(change) {
    if (change === null || change === undefined || isNaN(change)) {
        return '';
    }
    
    return change >= 0 ? 'price-up' : 'price-down';
}
