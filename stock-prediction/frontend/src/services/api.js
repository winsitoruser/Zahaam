const API_BASE_URL = 'http://localhost:8000/api';

// Fallback data for when API is unavailable
const FALLBACK_DATA = {
  // Fallback strategies data
  strategies: [
    { id: 'moving_average', name: 'Moving Average Crossover', description: 'Simple strategy based on crossover between fast and slow moving averages' },
    { id: 'rsi', name: 'RSI Strategy', description: 'Trading strategy based on Relative Strength Index (RSI) indicator' },
    { id: 'macd', name: 'MACD Strategy', description: 'Moving Average Convergence Divergence trading strategy' },
    { id: 'bollinger', name: 'Bollinger Bands', description: 'Price action strategy using Bollinger Bands indicator' },
    { id: 'breakout', name: 'Breakout Strategy', description: 'Trade on price breakouts through support or resistance levels' }
  ],
  stocks: [
    // Top Gainers
    { symbol: 'BBRI', name: 'Bank BRI', price: 5150, change: 3.21, volume: 123456789, value: 635812662150, frequency: 15467, sector: 'Finance' },
    { symbol: 'TLKM', name: 'Telkom Indonesia', price: 3970, change: 2.85, volume: 98765432, value: 392099265840, frequency: 12345, sector: 'Telecommunication' },
    { symbol: 'ASII', name: 'Astra International', price: 6275, change: 2.45, volume: 87654321, value: 550040865275, frequency: 10234, sector: 'Manufacturing' },
    { symbol: 'UNVR', name: 'Unilever Indonesia', price: 4680, change: 2.18, volume: 76543210, value: 358222222800, frequency: 9876, sector: 'Consumer Goods' },
    { symbol: 'BMRI', name: 'Bank Mandiri', price: 5775, change: 1.94, volume: 65432109, value: 377880129525, frequency: 8765, sector: 'Finance' },
    
    // Top Losers
    { symbol: 'INDF', name: 'Indofood Sukses Makmur', price: 6450, change: -1.53, volume: 54321098, value: 350321082100, frequency: 7654, sector: 'Consumer Goods' },
    { symbol: 'ICBP', name: 'Indofood CBP Sukses', price: 8725, change: -1.69, volume: 43210987, value: 377005853675, frequency: 6543, sector: 'Consumer Goods' },
    { symbol: 'ANTM', name: 'Aneka Tambang', price: 1865, change: -2.09, volume: 32109876, value: 59884769640, frequency: 5432, sector: 'Mining' },
    { symbol: 'BBCA', name: 'Bank Central Asia', price: 9025, change: -0.83, volume: 21098765, value: 190366053625, frequency: 4321, sector: 'Finance' },
    { symbol: 'INTP', name: 'Indocement Tunggal Prakarsa', price: 9450, change: -2.33, volume: 10987654, value: 103833230300, frequency: 3210, sector: 'Manufacturing' },
    
    // Most Active
    { symbol: 'HMSP', name: 'H.M. Sampoerna', price: 1875, change: 0.81, volume: 145678932, value: 273148497500, frequency: 16789, sector: 'Consumer Goods' },
    { symbol: 'PGAS', name: 'Perusahaan Gas Negara', price: 1540, change: 0.65, volume: 132456789, value: 203983854760, frequency: 14567, sector: 'Energy' },
    { symbol: 'BBNI', name: 'Bank Negara Indonesia', price: 4350, change: 1.64, volume: 107654321, value: 468296295350, frequency: 12321, sector: 'Finance' },
    { symbol: 'PTBA', name: 'Tambang Batubara Bukit Asam', price: 3150, change: -1.25, volume: 96543210, value: 304111111500, frequency: 11234, sector: 'Mining' },
    { symbol: 'EXCL', name: 'XL Axiata', price: 2470, change: -0.40, volume: 89765432, value: 221719567040, frequency: 9876, sector: 'Telecommunication' },
    
    // More stocks for the full list
    { symbol: 'ADRO', name: 'Adaro Energy', price: 2350, change: 0.64, volume: 19876543, value: 46709876550, frequency: 2987, sector: 'Energy' },
    { symbol: 'KLBF', name: 'Kalbe Farma', price: 1675, change: 0.91, volume: 18765432, value: 31432098600, frequency: 2876, sector: 'Health Care' },
    { symbol: 'PTBA', name: 'Tambang Batubara Bukit Asam', price: 3200, change: 1.27, volume: 17654321, value: 56493827200, frequency: 2765, sector: 'Mining' },
    { symbol: 'CPIN', name: 'Charoen Pokphand Indonesia', price: 5550, change: -0.36, volume: 16543210, value: 91814815500, frequency: 2654, sector: 'Consumer Goods' },
    { symbol: 'UNTR', name: 'United Tractors', price: 27525, change: 0.73, volume: 15432109, value: 424749000225, frequency: 2543, sector: 'Manufacturing' }
  ],
  
  // Market indices
  marketIndices: [
    { symbol: 'COMPOSITE', name: 'IDX Composite', price: 7025.36, change: 0.38 },
    { symbol: 'LQ45', name: 'IDX LQ45', price: 948.82, change: 0.54 },
    { symbol: 'IDX30', name: 'IDX 30', price: 517.63, change: 0.41 },
    { symbol: 'IDXBUMN20', name: 'IDX BUMN20', price: 423.79, change: -0.26 },
    { symbol: 'IDXSMC-COM', name: 'IDX Small-Mid Cap', price: 392.14, change: 0.73 }
  ],
  
  // IHSG chart data - simulated
  ihsgData: [
    { time: '2023-12-01', open: 6934.56, high: 6983.21, low: 6905.78, close: 6972.34 },
    { time: '2023-12-04', open: 6972.34, high: 7012.45, low: 6952.87, close: 6989.76 },
    { time: '2023-12-05', open: 6989.76, high: 7032.18, low: 6975.32, close: 7014.23 },
    { time: '2023-12-06', open: 7014.23, high: 7045.67, low: 6998.54, close: 7032.18 },
    { time: '2023-12-07', open: 7032.18, high: 7075.43, low: 7018.65, close: 7065.89 },
    { time: '2023-12-08', open: 7065.89, high: 7098.76, low: 7042.31, close: 7082.45 },
    { time: '2023-12-11', open: 7082.45, high: 7110.32, low: 7056.78, close: 7092.16 },
    { time: '2023-12-12', open: 7092.16, high: 7124.57, low: 7076.23, close: 7103.87 },
    { time: '2023-12-13', open: 7103.87, high: 7132.64, low: 7065.18, close: 7086.42 },
    { time: '2023-12-14', open: 7086.42, high: 7098.35, low: 7036.71, close: 7042.19 },
    { time: '2023-12-15', open: 7042.19, high: 7075.86, low: 7021.53, close: 7025.36 }
  ],
  
  // Sector data
  sectors: [
    { name: 'Finance', change: 0.87, color: '#1976d2', value: 3257000000000, count: 4 },
    { name: 'Consumer Goods', change: 0.54, color: '#388e3c', value: 1895000000000, count: 3 },
    { name: 'Energy', change: -0.32, color: '#d32f2f', value: 780000000000, count: 1 },
    { name: 'Health Care', change: 1.21, color: '#7b1fa2', value: 590000000000, count: 1 },
    { name: 'Telecommunication', change: 0.65, color: '#f57c00', value: 1650000000000, count: 2 },
    { name: 'Property', change: -0.43, color: '#c2185b', value: 450000000000, count: 1 },
    { name: 'Mining', change: -0.76, color: '#5d4037', value: 890000000000, count: 2 },
    { name: 'Manufacturing', change: 0.38, color: '#0097a7', value: 1420000000000, count: 2 },
    { name: 'Infrastructure', change: -0.7, color: '#00796b', value: 675000000000, count: 1 }
  ],
  marketSummary: {
    ihsg: 7025.36,
    change: 0.38,
    volume: 9827491200,
    value: 9327491000000,
    frequency: 983425,
    marketCap: 8452000000000000,
    advancing: 189,
    declining: 254,
    unchanged: 98
  },
  ihsgData: [
    { date: '2023-05-01', value: 6900.12 },
    { date: '2023-05-02', value: 6925.47 },
    { date: '2023-05-03', value: 6932.81 },
    { date: '2023-05-04', value: 6918.19 },
    { date: '2023-05-05', value: 6940.22 },
    { date: '2023-05-08', value: 6962.55 },
    { date: '2023-05-09', value: 6981.30 },
    { date: '2023-05-10', value: 6975.14 },
    { date: '2023-05-11', value: 6990.78 },
    { date: '2023-05-12', value: 7005.43 },
    { date: '2023-05-15', value: 7025.36 }
  ],
  marketIndices: [
    { name: 'IHSG', value: 7025.36, change: 0.38 },
    { name: 'LQ45', value: 982.14, change: 0.42 },
    { name: 'IDX30', value: 542.68, change: 0.35 },
    { name: 'IDX80', value: 168.47, change: 0.29 }
  ]
};

/**
 * Fetch list of available stocks and dashboard data
 * @returns {Promise<Object>} Object containing stocks and dashboard data
 */
export const fetchStocks = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/dashboard/data`);
    if (!response.ok) {
      throw new Error('Failed to fetch dashboard data');
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching dashboard data:', error);
    // Return fallback data when API is unavailable
    console.log('Using fallback data for dashboard');
    return FALLBACK_DATA;
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

/**
 * Fetch available trading strategies
 * @returns {Promise<Object>} Object containing available strategies
 */
export const fetchStrategies = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/strategies`);
    if (!response.ok) {
      throw new Error('Failed to fetch strategies');
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching strategies:', error);
    // Return fallback strategies when API is unavailable
    console.log('Using fallback data for strategies');
    return { strategies: FALLBACK_DATA.strategies };
  }
};

/**
 * Fetch user portfolio data
 * @param {string} userId - User ID (optional)
 * @returns {Promise<Object>} Portfolio data
 */
export const fetchPortfolio = async (userId = 'current') => {
  try {
    const response = await fetch(`${API_BASE_URL}/portfolio/${userId}`);
    if (!response.ok) {
      throw new Error('Failed to fetch portfolio data');
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching portfolio data:', error);
    // Return fallback portfolio data
    console.log('Using fallback data for portfolio');
    return { 
      portfolio: [
        { symbol: 'BBCA', name: 'Bank Central Asia', shares: 100, avgPrice: 8950, currentPrice: 9025, value: 902500, gain: 7500, gainPercent: 0.84 },
        { symbol: 'TLKM', name: 'Telkom Indonesia', shares: 200, avgPrice: 3850, currentPrice: 3970, value: 794000, gain: 24000, gainPercent: 3.12 },
        { symbol: 'ASII', name: 'Astra International', shares: 150, avgPrice: 6100, currentPrice: 6275, value: 941250, gain: 26250, gainPercent: 2.87 },
        { symbol: 'ADRO', name: 'Adaro Energy', shares: 300, avgPrice: 2250, currentPrice: 2350, value: 705000, gain: 30000, gainPercent: 4.44 }
      ],
      summary: {
        totalValue: 3342750,
        totalGain: 87750,
        totalGainPercent: 2.70,
        cashBalance: 5000000
      }
    };
  }
};

/**
 * Fetch user watchlist
 * @param {string} userId - User ID (optional)
 * @returns {Promise<Object>} Watchlist data
 */
export const fetchWatchlist = async (userId = 'current') => {
  try {
    const response = await fetch(`${API_BASE_URL}/watchlist/${userId}`);
    if (!response.ok) {
      throw new Error('Failed to fetch watchlist');
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching watchlist:', error);
    // Return fallback watchlist data
    console.log('Using fallback data for watchlist');
    return { 
      watchlist: [
        { symbol: 'UNVR', name: 'Unilever Indonesia', price: 4680, change: 2.18, alerts: [{type: 'price', value: 4800, condition: 'above'}] },
        { symbol: 'BBRI', name: 'Bank BRI', price: 5150, change: 3.21, alerts: [{type: 'price', value: 5000, condition: 'below'}] },
        { symbol: 'BMRI', name: 'Bank Mandiri', price: 5775, change: 1.94, alerts: [] },
        { symbol: 'PGAS', name: 'Perusahaan Gas Negara', price: 1540, change: 0.65, alerts: [{type: 'change', value: 5, condition: 'above'}] }
      ]
    };
  }
};

/**
 * Fetch stock prediction results
 * @param {string} ticker - Stock ticker symbol
 * @param {string} strategyId - Strategy ID to use
 * @param {Object} params - Additional parameters
 * @returns {Promise<Object>} Prediction results
 */
export const fetchPrediction = async (ticker, strategyId, params = {}) => {
  try {
    const queryParams = new URLSearchParams({
      strategy: strategyId,
      ...params
    }).toString();
    
    const response = await fetch(`${API_BASE_URL}/prediction/${ticker}?${queryParams}`);
    if (!response.ok) {
      throw new Error(`Failed to fetch prediction for ${ticker}`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error fetching prediction for ${ticker}:`, error);
    throw error;
  }
};

/**
 * Add stock to watchlist
 * @param {string} ticker - Stock ticker symbol
 * @returns {Promise<Object>} Updated watchlist
 */
export const addToWatchlist = async (ticker) => {
  try {
    const response = await fetch(`${API_BASE_URL}/watchlist/add`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ symbol: ticker })
    });
    
    if (!response.ok) {
      throw new Error(`Failed to add ${ticker} to watchlist`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error adding ${ticker} to watchlist:`, error);
    throw error;
  }
};

/**
 * Run backtest for a strategy
 * @param {string} strategyId - Strategy ID
 * @param {string} ticker - Stock ticker symbol
 * @param {Object} params - Strategy parameters
 * @returns {Promise<Object>} Backtest results
 */
export const runBacktest = async (strategyId, ticker, params = {}) => {
  try {
    const response = await fetch(`${API_BASE_URL}/backtest`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        strategy: strategyId,
        symbol: ticker,
        parameters: params
      })
    });
    
    if (!response.ok) {
      throw new Error(`Failed to run backtest for ${ticker}`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error running backtest for ${ticker}:`, error);
    throw error;
  }
};
