const API_BASE_URL = 'http://localhost:8000/api';

/**
 * Fetch list of available stocks
 * @returns {Promise<Array>} Array of stock symbols
 */
export const fetchStocks = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/stocks`);
    if (!response.ok) {
      throw new Error('Failed to fetch stocks');
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching stocks:', error);
    throw error;
  }
};

/**
 * Fetch stock data for a specific ticker
 * @param {string} ticker - Stock ticker symbol (e.g., 'BBCA.JK')
 * @param {string} period - Data period (default: '1y')
 * @param {string} interval - Data interval (default: '1d')
 * @returns {Promise<Object>} Stock data
 */
export const fetchStockData = async (ticker, period = '1y', interval = '1d') => {
  try {
    const response = await fetch(
      `${API_BASE_URL}/stock/${ticker}?period=${period}&interval=${interval}`
    );
    if (!response.ok) {
      throw new Error(`Failed to fetch data for ${ticker}`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error fetching data for ${ticker}:`, error);
    throw error;
  }
};

/**
 * Fetch technical signals for a specific stock
 * @param {string} ticker - Stock ticker symbol (e.g., 'BBCA.JK')
 * @param {string} period - Data period (default: '1y')
 * @returns {Promise<Object>} Technical analysis signals
 */
export const fetchTechnicalSignals = async (ticker, period = '1y') => {
  try {
    const response = await fetch(
      `${API_BASE_URL}/technical/signal/${ticker}?period=${period}`
    );
    if (!response.ok) {
      throw new Error(`Failed to fetch technical signals for ${ticker}`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error fetching technical signals for ${ticker}:`, error);
    throw error;
  }
};

/**
 * Format number with commas as thousand separators
 * @param {number} num - Number to format
 * @returns {string} Formatted number string
 */
export const formatNumber = (num) => {
  if (num === null || num === undefined) return 'N/A';
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
};

/**
 * Format currency
 * @param {number} amount - Amount to format
 * @param {string} currency - Currency code (default: 'IDR')
 * @returns {string} Formatted currency string
 */
export const formatCurrency = (amount, currency = 'IDR') => {
  if (amount === null || amount === undefined) return 'N/A';
  
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: currency,
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  }).format(amount);
};

/**
 * Format percentage
 * @param {number} value - Percentage value (0-100)
 * @param {number} decimals - Number of decimal places (default: 2)
 * @returns {string} Formatted percentage string
 */
export const formatPercentage = (value, decimals = 2) => {
  if (value === null || value === undefined) return 'N/A';
  return `${value.toFixed(decimals)}%`;
};

/**
 * Get color based on value (positive/negative)
 * @param {number} value - Numeric value
 * @returns {string} Bootstrap color class
 */
export const getValueColor = (value) => {
  if (value === null || value === undefined) return '';
  return value >= 0 ? 'text-success' : 'text-danger';
};
