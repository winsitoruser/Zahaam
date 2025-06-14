/**
 * Batch API Service for Zahaam Stock Prediction System
 * Allows multiple API calls to be batched into a single request
 */

import { setAuthToken } from './auth';
import { cacheStore, CACHE_KEYS } from './apiCache';
import { ensureValidToken } from './tokenRefresh';

const API_BASE_URL = 'http://localhost:8000/api';
const BATCH_ENDPOINT = `${API_BASE_URL}/batch`;

/**
 * BatchRequest class to manage batched API requests
 */
class BatchRequest {
  constructor() {
    this.requests = [];
    this.useCache = true;
  }

  /**
   * Add request to batch
   * @param {string} endpoint - API endpoint path
   * @param {string} method - HTTP method (GET, POST, etc.)
   * @param {Object} body - Request body for POST/PUT
   * @param {string} id - Unique identifier for this request in the batch
   * @return {BatchRequest} - Returns this for chaining
   */
  add(endpoint, method = 'GET', body = null, id = null) {
    const requestId = id || `req_${this.requests.length + 1}`;
    
    this.requests.push({
      id: requestId,
      path: endpoint.startsWith('/') ? endpoint : `/${endpoint}`,
      method: method.toUpperCase(),
      body: body
    });
    
    return this;
  }

  /**
   * Control whether to check cache before making batch request
   * @param {boolean} useCache - Whether to use cache
   * @return {BatchRequest} - Returns this for chaining
   */
  withCache(useCache = true) {
    this.useCache = useCache;
    return this;
  }

  /**
   * Execute the batch request
   * @param {Object} [authHeaders] - Optional authentication headers
   * @returns {Promise<Object>} - Object with results mapped by request ID
   */
  async execute(authHeaders = null) {
    // Don't make empty requests
    if (this.requests.length === 0) {
      return {};
    }
    
    // Prepare headers with authentication if needed
    const headers = {
      'Content-Type': 'application/json'
    };

    // If auth headers provided, use them
    if (authHeaders && authHeaders.Authorization) {
      headers.Authorization = authHeaders.Authorization;
    } else {
      // Otherwise try to get a valid token
      try {
        const token = await ensureValidToken();
        if (token) {
          headers.Authorization = `Bearer ${token}`;
        }
      } catch (err) {
        console.error('Error getting auth token for batch request:', err);
      }
    }
    
    // For a single request, don't use the batch endpoint
    if (this.requests.length === 1) {
      const singleRequest = this.requests[0];
      const url = `${API_BASE_URL}${singleRequest.path}`;
      
      const options = {
        method: singleRequest.method,
        headers
      };
      
      if (singleRequest.body && (singleRequest.method !== 'GET')) {
        options.body = JSON.stringify(singleRequest.body);
      }
      
      const response = await fetch(url, options);
      
      if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
      }
      
      const data = await response.json();
      
      return { [singleRequest.id]: { data } };
    }

    try {
      const response = await fetch(BATCH_ENDPOINT, {
        method: 'POST',
        headers,
        body: JSON.stringify({ requests: this.requests })
      });
      
      if (!response.ok) {
        throw new Error(`Batch request failed with status ${response.status}`);
      }
      
      return await response.json();
    } catch (error) {
      console.error('Batch request error:', error);
      
      // Fall back to individual requests if batch fails
      return this.executeIndividually();
    }
  }
  
  /**
   * Execute each request individually as fallback
   * @param {Object} [authHeaders] - Optional authentication headers
   * @returns {Promise<Object>} - Object with results mapped by request ID
   */
  async executeIndividually(authHeaders = null) {
    console.warn('Falling back to individual requests');
    const results = {};
    
    // Prepare headers with authentication if needed
    const headers = {
      'Content-Type': 'application/json'
    };

    // If auth headers provided, use them
    if (authHeaders && authHeaders.Authorization) {
      headers.Authorization = authHeaders.Authorization;
    } else {
      // Otherwise try to get a valid token
      try {
        const token = await ensureValidToken();
        if (token) {
          headers.Authorization = `Bearer ${token}`;
        }
      } catch (err) {
        console.error('Error getting auth token for individual requests:', err);
      }
    }
    
    for (const request of this.requests) {
      try {
        const url = `${API_BASE_URL}${request.path}`;
        
        const options = {
          method: request.method,
          headers
        };
        
        if (request.body && (request.method !== 'GET')) {
          options.body = JSON.stringify(request.body);
        }
        
        const response = await fetch(url, options);
        results[request.id] = {
          data: await response.json(),
          status: response.status
        };
      } catch (error) {
        console.error(`Error in individual request ${request.id}:`, error);
        results[request.id] = {
          error: error.message,
          status: 500
        };
      }
    }
    
    return results;
  }
  
  /**
   * Clear the current batch
   * @return {BatchRequest} - Returns this for chaining
   */
  clear() {
    this.requests = [];
    return this;
  }
}

/**
 * Create a new batch request
 * @returns {BatchRequest} - New batch request instance
 */
export const createBatch = () => {
  return new BatchRequest();
};

/**
 * Common batch operation: Fetch Dashboard Data
 * @param {Object} [authHeaders] - Optional authentication headers
 * @returns {Promise<Object>} - Object with all dashboard data
 */
export const batchFetchDashboardData = async (authHeaders = null) => {
  // Check cache first
  const cachedData = cacheStore.get(CACHE_KEYS.MARKET_SUMMARY);
  if (cachedData) {
    return cachedData;
  }
  
  const batch = new BatchRequest()
    .add('stocks', 'GET', null, 'stocks')
    .add('market/summary', 'GET', null, 'marketSummary')
    .add('market/indices', 'GET', null, 'marketIndices')
    .add('sectors', 'GET', null, 'sectors')
    .add('news/market', 'GET', null, 'marketNews');
  
  const results = await batch.execute(authHeaders);
  
  // Combine all results into one object
  const dashboardData = {
    stocks: results.stocks?.data?.stocks || [],
    marketSummary: results.marketSummary?.data || {},
    marketIndices: results.marketIndices?.data || [],
    sectors: results.sectors?.data || [],
    marketNews: results.marketNews?.data?.items || []
  };
  
  // Cache the combined data
  cacheStore.set(CACHE_KEYS.MARKET_SUMMARY, dashboardData, 60000); // 1 minute TTL for market data
  
  return dashboardData;
};

/**
 * Common batch operation: Fetch User Data (portfolio and watchlist)
 * @param {Object} [authHeaders] - Optional authentication headers
 * @returns {Promise<Object>} - Object with user's portfolio and watchlist
 */
export const batchFetchUserData = async (authHeaders = null) => {
  // Check cache first
  const cachedData = cacheStore.get(CACHE_KEYS.USER_DATA);
  if (cachedData) {
    return cachedData;
  }
  
  const batch = new BatchRequest()
    .add('portfolio/current', 'GET', null, 'portfolio')
    .add('watchlist/current', 'GET', null, 'watchlist')
    .add('user/preferences', 'GET', null, 'preferences')
    .add('notifications', 'GET', null, 'notifications');
  
  const results = await batch.execute(authHeaders);
  
  const userData = {
    portfolio: results.portfolio?.data || { items: [], summary: {} },
    watchlist: results.watchlist?.data || { items: [] },
    preferences: results.preferences?.data || {},
    notifications: results.notifications?.data?.items || []
  };
  
  // Cache the combined user data
  cacheStore.set(CACHE_KEYS.USER_DATA, userData, 60000); // 1 minute TTL
  
  return userData;
};

/**
 * Common batch operation: Fetch multiple stock data
 * @param {Array<string>} symbols - Array of stock symbols
 * @param {string} interval - Data interval
 * @param {Object} [authHeaders] - Optional authentication headers
 * @returns {Promise<Object>} - Object with data for each requested stock
 */
export const batchFetchMultipleStocks = async (symbols, interval = '1d', authHeaders = null) => {
  if (!symbols || !Array.isArray(symbols) || symbols.length === 0) {
    return {};
  }
  
  const batch = new BatchRequest();
  
  // Add each stock to the batch
  symbols.forEach(symbol => {
    batch.add(`stocks/${symbol}?interval=${interval}`, 'GET', null, symbol);
  });
  
  return await batch.execute(authHeaders);
};

/**
 * Advanced batch operation: Analyze multiple stocks with technical indicators
 * @param {Array<string>} symbols - Array of stock symbols
 * @param {Array<string>} indicators - Array of indicators to calculate
 * @param {Object} [authHeaders] - Optional authentication headers
 * @returns {Promise<Object>} - Object with technical analysis data for each stock
 */
export const batchAnalyzeStocks = async (symbols, indicators = ['sma', 'rsi', 'macd'], authHeaders = null) => {
  if (!symbols || !Array.isArray(symbols) || symbols.length === 0) {
    return {};
  }
  
  const batch = new BatchRequest();
  
  // Add each stock analysis to the batch
  symbols.forEach(symbol => {
    // Add basic stock data
    batch.add(`stocks/${symbol}`, 'GET', null, `${symbol}_data`);
    
    // Add each requested indicator
    indicators.forEach(indicator => {
      batch.add(`analysis/${symbol}/${indicator}`, 'GET', null, `${symbol}_${indicator}`);
    });
  });
  
  const results = await batch.execute(authHeaders);
  
  // Restructure results for easier consumption
  const analysisResults = {};
  
  symbols.forEach(symbol => {
    analysisResults[symbol] = {
      data: results[`${symbol}_data`]?.data || {},
      indicators: {}
    };
    
    // Add indicators
    indicators.forEach(indicator => {
      analysisResults[symbol].indicators[indicator] = 
        results[`${symbol}_${indicator}`]?.data || {};
    });
  });
  
  return analysisResults;
};

/**
 * Batch operation: Search for stocks and get market data in one request
 * @param {string} query - Search query
 * @param {Object} [authHeaders] - Optional authentication headers
 * @returns {Promise<Object>} - Search results and market data
 */
export const batchSearchAndMarket = async (query, authHeaders = null) => {
  const batch = new BatchRequest()
    .add(`stocks/search?q=${encodeURIComponent(query)}`, 'GET', null, 'searchResults')
    .add('market/summary', 'GET', null, 'marketSummary')
    .add('market/trending', 'GET', null, 'trending');
    
  const results = await batch.execute(authHeaders);
  
  return {
    searchResults: results.searchResults?.data?.stocks || [],
    marketSummary: results.marketSummary?.data || {},
    trending: results.trending?.data?.stocks || []
  };
};

export default {
  createBatch,
  batchFetchDashboardData,
  batchFetchUserData,
  batchFetchMultipleStocks,
  batchAnalyzeStocks,
  batchSearchAndMarket
};
