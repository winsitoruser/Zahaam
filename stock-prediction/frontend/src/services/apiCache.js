/**
 * API Caching service for Zahaam Stock Prediction System
 * Implements client-side caching for frequently accessed data
 */

// Cache configuration
const DEFAULT_CACHE_TTL = 5 * 60 * 1000; // 5 minutes default TTL
const CACHE_KEYS = {
  STOCKS: 'stocks',
  STOCK_DATA: 'stock_data',
  WATCHLIST: 'watchlist',
  PORTFOLIO: 'portfolio',
  STRATEGIES: 'strategies',
  MARKET_SUMMARY: 'market_summary',
  PREDICTIONS: 'predictions'
};

// Cache storage
class CacheStore {
  constructor() {
    this.cache = new Map();
  }

  /**
   * Set item in cache with TTL
   * @param {string} key - Cache key
   * @param {any} value - Value to cache
   * @param {number} ttl - Time to live in milliseconds
   */
  set(key, value, ttl = DEFAULT_CACHE_TTL) {
    const now = Date.now();
    const item = {
      value,
      expiry: now + ttl,
      cachedAt: now
    };
    this.cache.set(key, item);
    return item;
  }

  /**
   * Get item from cache
   * @param {string} key - Cache key
   * @returns {any|null} Cached value or null if expired/not found
   */
  get(key) {
    const item = this.cache.get(key);
    
    // If item doesn't exist or is expired
    if (!item || Date.now() > item.expiry) {
      if (item) {
        this.cache.delete(key); // Clean up expired item
      }
      return null;
    }
    
    return item.value;
  }

  /**
   * Check if item exists in cache and is valid
   * @param {string} key - Cache key
   * @returns {boolean} True if valid item exists
   */
  has(key) {
    const item = this.cache.get(key);
    return item && Date.now() <= item.expiry;
  }

  /**
   * Delete item from cache
   * @param {string} key - Cache key
   */
  delete(key) {
    this.cache.delete(key);
  }

  /**
   * Get cache item with metadata
   * @param {string} key - Cache key
   * @returns {Object|null} Cache item with metadata or null
   */
  getWithMeta(key) {
    const item = this.cache.get(key);
    if (!item || Date.now() > item.expiry) {
      return null;
    }
    
    return {
      ...item,
      remainingTtl: item.expiry - Date.now(),
      age: Date.now() - item.cachedAt
    };
  }

  /**
   * Clear all cache or items with specific prefix
   * @param {string} [prefix] - Optional prefix to clear
   */
  clear(prefix = null) {
    if (prefix) {
      for (const key of this.cache.keys()) {
        if (key.startsWith(prefix)) {
          this.cache.delete(key);
        }
      }
    } else {
      this.cache.clear();
    }
  }

  /**
   * Get all cache keys
   * @returns {Array<string>} Array of cache keys
   */
  keys() {
    return Array.from(this.cache.keys());
  }
}

// Create cache instance
const cacheStore = new CacheStore();

export {
  cacheStore,
  CACHE_KEYS,
  DEFAULT_CACHE_TTL
};
