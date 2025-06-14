/**
 * API Integration Service for Zahaam Stock Prediction System
 * Integrates original API with authentication, caching, and batch requests
 */

import * as api from './api';
import * as auth from './auth';
import { cacheStore, CACHE_KEYS } from './apiCache';
import * as batchApi from './batchApi';
import { ensureValidToken } from './tokenRefresh';

// Cache TTLs (Time-To-Live) in milliseconds
const CACHE_TTL = {
  STOCKS: 60 * 1000,            // 1 minute
  MARKET_DATA: 60 * 1000,       // 1 minute
  STOCK_DATA: 5 * 60 * 1000,    // 5 minutes
  STRATEGIES: 30 * 60 * 1000,   // 30 minutes
  WATCHLIST: 60 * 1000,         // 1 minute
  PORTFOLIO: 60 * 1000,         // 1 minute
  DASHBOARD: 2 * 60 * 1000,     // 2 minutes
  NEWS: 10 * 60 * 1000,         // 10 minutes
  TECHNICAL_INDICATORS: 15 * 60 * 1000, // 15 minutes
  USER_DATA: 3 * 60 * 1000      // 3 minutes
};

// Priority levels for cache (higher number = higher priority)
const CACHE_PRIORITY = {
  LOW: 1,
  MEDIUM: 2,
  HIGH: 3,
  CRITICAL: 4
};

/**
 * Check authentication and append token to request headers
 * Ensures token is refreshed if expired
 * @returns {Promise<Object>} Headers with authentication token if available
 */
const getAuthHeaders = async () => {
  const headers = {
    'Content-Type': 'application/json'
  };
  
  if (auth.isAuthenticated()) {
    try {
      // Get a valid token (refreshes if needed)
      const token = await ensureValidToken();
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }
    } catch (err) {
      console.error('Error getting auth headers:', err);
    }
  }
  
  return headers;
};

/**
 * Fetch stocks with authentication and caching
 * @returns {Promise<Object>} Stock list and market data
 */
export const fetchStocks = async () => {
  // Try to get data from cache first
  const cachedData = cacheStore.get(CACHE_KEYS.STOCKS);
  if (cachedData) {
    console.log('Using cached stocks data');
    return cachedData;
  }
  
  try {
    // If authenticated, get user-specific data using batch API
    if (auth.isAuthenticated()) {
      console.log('Fetching authenticated stocks data');
      const dashboardData = await batchApi.batchFetchDashboardData();
      
      // Cache the response
      cacheStore.set(CACHE_KEYS.STOCKS, dashboardData, CACHE_TTL.STOCKS);
      return dashboardData;
    }
    
    // Fall back to regular API for non-authenticated users
    const result = await api.fetchStocks();
    
    // Cache the response
    cacheStore.set(CACHE_KEYS.STOCKS, result, CACHE_TTL.STOCKS);
    return result;
  } catch (error) {
    console.error('Error in fetchStocks integration:', error);
    // Use original API fallback mechanism
    return api.fetchStocks();
  }
};

/**
 * Fetch stock data with authentication and caching
 * @param {string} ticker - Stock ticker symbol
 * @param {string} period - Data period
 * @param {string} interval - Data interval
 * @returns {Promise<Object>} Stock data
 */
export const fetchStockData = async (ticker, period = '1y', interval = '1d') => {
  if (!ticker) {
    throw new Error('Ticker symbol is required');
  }
  
  // Generate cache key
  const cacheKey = `${CACHE_KEYS.STOCK_DATA}_${ticker}_${period}_${interval}`;
  
  // Try to get data from cache first
  const cachedData = cacheStore.get(cacheKey);
  if (cachedData) {
    console.log(`Using cached data for ${ticker}`);
    return cachedData;
  }
  
  try {
    // Get headers with auth token if available
    const headers = await getAuthHeaders();
    
    // Fetch the data with authentication
    const response = await fetch(`${api.API_BASE_URL}/stocks/${ticker}?period=${period}&interval=${interval}`, {
      headers
    });
    
    if (!response.ok) {
      throw new Error(`Failed to fetch data for ${ticker}`);
    }
    
    const result = await response.json();
    
    // Cache the response
    cacheStore.set(cacheKey, result, CACHE_TTL.STOCK_DATA);
    
    return result;
  } catch (error) {
    console.error(`Error fetching stock data for ${ticker}:`, error);
    // Fall back to the original API implementation
    return api.fetchStockData(ticker, period, interval);
  }
};

/**
 * Fetch strategies with authentication and caching
 * @returns {Promise<Object>} Available strategies
 */
export const fetchStrategies = async () => {
  // Try to get data from cache first
  const cachedData = cacheStore.get(CACHE_KEYS.STRATEGIES);
  if (cachedData) {
    console.log('Using cached strategies data');
    return cachedData;
  }
  
  try {
    // Get headers with auth token if available
    const headers = await getAuthHeaders();
    
    // Fetch the data with authentication
    const response = await fetch(`${api.API_BASE_URL}/strategies`, {
      headers
    });
    
    if (!response.ok) {
      throw new Error('Failed to fetch strategies');
    }
    
    const result = await response.json();
    
    // Cache the response
    cacheStore.set(CACHE_KEYS.STRATEGIES, result, CACHE_TTL.STRATEGIES);
    
    return result;
  } catch (error) {
    console.error('Error fetching strategies:', error);
    // Fall back to the original API implementation
    return api.fetchStrategies();
  }
};

/**
 * Fetch user portfolio data with authentication and caching
 * @returns {Promise<Object>} Portfolio data
 */
export const fetchPortfolio = async () => {
  // Require authentication for portfolio data
  if (!auth.isAuthenticated()) {
    throw new Error('Authentication required to access portfolio data');
  }
  
  // Try to get data from cache first
  const cachedData = cacheStore.get(CACHE_KEYS.PORTFOLIO);
  if (cachedData) {
    console.log('Using cached portfolio data');
    return cachedData;
  }
  
  try {
    // Get headers with auth token
    const headers = getAuthHeaders();
    
    // Fetch the data with authentication
    const response = await fetch(`${api.API_BASE_URL}/portfolio/current`, {
      headers
    });
    
    if (!response.ok) {
      throw new Error('Failed to fetch portfolio data');
    }
    
    const result = await response.json();
    
    // Cache the response
    cacheStore.set(CACHE_KEYS.PORTFOLIO, result, CACHE_TTL.PORTFOLIO);
    
    return result;
  } catch (error) {
    console.error('Error fetching portfolio:', error);
    // Fall back to the original API implementation, but include auth token
    return api.fetchPortfolio();
  }
};

/**
 * Fetch user watchlist with authentication and caching
 * @returns {Promise<Object>} Watchlist data
 */
export const fetchWatchlist = async () => {
  // Require authentication for watchlist data
  if (!auth.isAuthenticated()) {
    throw new Error('Authentication required to access watchlist data');
  }
  
  // Try to get data from cache first
  const cachedData = cacheStore.get(CACHE_KEYS.WATCHLIST);
  if (cachedData) {
    console.log('Using cached watchlist data');
    return cachedData;
  }
  
  try {
    // Get headers with auth token
    const headers = getAuthHeaders();
    
    // Fetch the data with authentication
    const response = await fetch(`${api.API_BASE_URL}/watchlist/current`, {
      headers
    });
    
    if (!response.ok) {
      throw new Error('Failed to fetch watchlist data');
    }
    
    const result = await response.json();
    
    // Cache the response
    cacheStore.set(CACHE_KEYS.WATCHLIST, result, CACHE_TTL.WATCHLIST);
    
    return result;
  } catch (error) {
    console.error('Error fetching watchlist:', error);
    // Fall back to the original API implementation
    return api.fetchWatchlist();
  }
};

/**
 * Add stock to watchlist with authentication
 * @param {string} ticker - Stock ticker symbol
 * @returns {Promise<Object>} Updated watchlist
 */
export const addToWatchlist = async (ticker) => {
  // Require authentication
  if (!auth.isAuthenticated()) {
    throw new Error('Authentication required to modify watchlist');
  }
  
  try {
    // Get headers with auth token
    const headers = getAuthHeaders();
    
    // Send request with authentication
    const response = await fetch(`${api.API_BASE_URL}/watchlist/add`, {
      method: 'POST',
      headers,
      body: JSON.stringify({ symbol: ticker })
    });
    
    if (!response.ok) {
      throw new Error(`Failed to add ${ticker} to watchlist`);
    }
    
    const result = await response.json();
    
    // Invalidate watchlist cache to refresh data
    cacheStore.delete(CACHE_KEYS.WATCHLIST);
    
    return result;
  } catch (error) {
    console.error(`Error adding ${ticker} to watchlist:`, error);
    throw error;
  }
};

/**
 * Remove stock from watchlist with authentication
 * @param {string} ticker - Stock ticker symbol
 * @returns {Promise<Object>} Updated watchlist
 */
export const removeFromWatchlist = async (ticker) => {
  // Require authentication
  if (!auth.isAuthenticated()) {
    throw new Error('Authentication required to modify watchlist');
  }
  
  try {
    // Get headers with auth token
    const headers = getAuthHeaders();
    
    // Send request with authentication
    const response = await fetch(`${api.API_BASE_URL}/watchlist/remove`, {
      method: 'POST',
      headers,
      body: JSON.stringify({ symbol: ticker })
    });
    
    if (!response.ok) {
      throw new Error(`Failed to remove ${ticker} from watchlist`);
    }
    
    const result = await response.json();
    
    // Invalidate watchlist cache to refresh data
    cacheStore.delete(CACHE_KEYS.WATCHLIST);
    
    return result;
  } catch (error) {
    console.error(`Error removing ${ticker} from watchlist:`, error);
    throw error;
  }
};

/**
 * Run prediction with authentication
 * @param {string} ticker - Stock ticker symbol
 * @param {string} strategyId - Strategy ID
 * @param {Object} params - Strategy parameters
 * @returns {Promise<Object>} Prediction results
 */
export const fetchPrediction = async (ticker, strategyId, params = {}) => {
  try {
    // Get headers with auth token if available
    const headers = getAuthHeaders();
    
    const queryParams = new URLSearchParams({
      strategy: strategyId,
      ...params
    }).toString();
    
    // Send request with optional authentication
    const response = await fetch(`${api.API_BASE_URL}/prediction/${ticker}?${queryParams}`, {
      headers
    });
    
    if (!response.ok) {
      throw new Error(`Failed to fetch prediction for ${ticker}`);
    }
    
    return await response.json();
  } catch (error) {
    console.error(`Error fetching prediction for ${ticker}:`, error);
    // Fall back to original implementation
    return api.fetchPrediction(ticker, strategyId, params);
  }
};

/**
 * Run backtest with authentication
 * @param {string} strategyId - Strategy ID
 * @param {string} ticker - Stock ticker symbol
 * @param {Object} params - Strategy parameters
 * @returns {Promise<Object>} Backtest results
 */
export const runBacktest = async (strategyId, ticker, params = {}) => {
  try {
    // Get headers with auth token if available
    const headers = getAuthHeaders();
    
    // Send request with optional authentication
    const response = await fetch(`${api.API_BASE_URL}/backtest`, {
      method: 'POST',
      headers,
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
    // Fall back to original implementation
    return api.runBacktest(strategyId, ticker, params);
  }
};

/**
 * Fetch dashboard data optimized with batch request and caching
 * @returns {Promise<Object>} Dashboard data including stocks, indices, sectors
 */
export const fetchDashboardData = async () => {
  // Try to get data from prioritized cache first
  const cachedData = getPrioritizedCache(CACHE_KEYS.DASHBOARD);
  if (cachedData) {
    console.log('Using cached dashboard data');
    return cachedData;
  }
  
  try {
    // Get headers with auth token if available
    const headers = await getAuthHeaders();
    
    const dashboardData = await batchApi.batchFetchDashboardData(headers);
    
    // Cache the response with high priority
    setPrioritizedCache(
      CACHE_KEYS.DASHBOARD, 
      dashboardData, 
      CACHE_TTL.DASHBOARD, 
      CACHE_PRIORITY.HIGH
    );
    
    return dashboardData;
  } catch (error) {
    console.error('Error fetching batch dashboard data:', error);
    // Fall back to original API
    const fallbackData = await api.fetchStocks();
    
    // Still cache the fallback data but with lower priority
    setPrioritizedCache(
      CACHE_KEYS.DASHBOARD, 
      fallbackData, 
      CACHE_TTL.DASHBOARD / 2, // Shorter TTL for fallback data
      CACHE_PRIORITY.MEDIUM
    );
    
    return fallbackData;
  }
};

/**
 * Batch operation: Fetch user data (portfolio and watchlist)
 * @returns {Promise<Object>} User data
 */
export const fetchUserData = async () => {
  if (!auth.isAuthenticated()) {
    throw new Error('Authentication required to access user data');
  }
  
  try {
    return await batchApi.batchFetchUserData();
  } catch (error) {
    console.error('Error fetching user data in batch:', error);
    
    // Fall back to individual requests
    const portfolio = await fetchPortfolio();
    const watchlist = await fetchWatchlist();
    
    return { portfolio, watchlist };
  }
};

/**
 * Add transaction to portfolio
 * @param {Object} transaction - Transaction details
 * @returns {Promise<Object>} Updated portfolio
 */
export const addPortfolioTransaction = async (transaction) => {
  // Require authentication
  if (!auth.isAuthenticated()) {
    throw new Error('Authentication required to modify portfolio');
  }
  
  try {
    // Get headers with auth token
    const headers = getAuthHeaders();
    
    // Send request with authentication
    const response = await fetch(`${api.API_BASE_URL}/portfolio/transaction`, {
      method: 'POST',
      headers,
      body: JSON.stringify(transaction)
    });
    
    if (!response.ok) {
      throw new Error('Failed to add transaction');
    }
    
    const result = await response.json();
    
    // Invalidate portfolio cache
    cacheStore.delete(CACHE_KEYS.PORTFOLIO);
    
    return result;
  } catch (error) {
    console.error('Error adding portfolio transaction:', error);
    throw error;
  }
};

/**
 * Clear API cache
 * @param {string} [cacheKey] - Optional specific cache key to clear
 * @param {boolean} [clearRelated] - Whether to clear related cache entries
 */
export const clearCache = (cacheKey = null, clearRelated = false) => {
  if (cacheKey) {
    cacheStore.delete(cacheKey);
    
    // Clear related caches based on key prefix
    if (clearRelated) {
      const allKeys = cacheStore.keys();
      const relatedKeys = allKeys.filter(key => key.startsWith(cacheKey.split('_')[0]));
      relatedKeys.forEach(key => {
        if (key !== cacheKey) {
          cacheStore.delete(key);
        }
      });
    }
    
    console.log(`Cleared cache for ${cacheKey}${clearRelated ? ' and related entries' : ''}`);
  } else {
    cacheStore.clear();
    console.log('Cleared all API cache');
  }
};

/**
 * Advanced caching strategy: Store data with priority levels
 * @param {string} key - Cache key
 * @param {any} data - Data to cache
 * @param {number} ttl - Time to live in milliseconds
 * @param {number} priority - Cache priority level
 */
export const setPrioritizedCache = (key, data, ttl, priority = CACHE_PRIORITY.MEDIUM) => {
  cacheStore.set(key, data, ttl, { priority });
  
  // For high priority data, store a backup with longer TTL
  if (priority >= CACHE_PRIORITY.HIGH) {
    const backupKey = `${key}_backup`;
    cacheStore.set(backupKey, data, ttl * 3, { priority: priority - 1 });
  }
};

/**
 * Get cached data with fallback to backup for high priority items
 * @param {string} key - Cache key
 * @returns {any} Cached data or null
 */
export const getPrioritizedCache = (key) => {
  const data = cacheStore.get(key);
  
  if (data === null) {
    // Check for backup cache
    const backupKey = `${key}_backup`;
    const backupData = cacheStore.get(backupKey);
    
    if (backupData) {
      console.log(`Using backup cache for ${key}`);
      // Restore primary cache from backup with shorter TTL
      cacheStore.set(key, backupData, CACHE_TTL.MARKET_DATA);
      return backupData;
    }
  }
  
  return data;
};

// Re-export utility functions from original API
export const {
  formatNumber,
  formatCurrency,
  formatPercentage,
  getValueColor,
  fetchTechnicalSignals
} = api;
