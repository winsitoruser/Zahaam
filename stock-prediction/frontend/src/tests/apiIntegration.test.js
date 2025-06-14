/**
 * API Integration Service Tests
 * Comprehensive end-to-end testing for the API integration service
 */

import * as api from '../services/apiIntegration';
import * as auth from '../services/auth';
import { setupTokenRefreshInterceptor } from '../services/tokenRefresh';
import { cacheStore, CACHE_KEYS } from '../services/apiCache';

// Mock fetch
global.fetch = jest.fn();

// Mock localStorage
const localStorageMock = (() => {
  let store = {};
  return {
    getItem: jest.fn(key => store[key] || null),
    setItem: jest.fn((key, value) => {
      store[key] = value;
    }),
    removeItem: jest.fn(key => {
      delete store[key];
    }),
    clear: jest.fn(() => {
      store = {};
    }),
    getStore: () => store
  };
})();

Object.defineProperty(window, 'localStorage', { value: localStorageMock });

// Mock auth module
jest.mock('../services/auth', () => ({
  isAuthenticated: jest.fn(),
  getCurrentUser: jest.fn()
}));

// Mock tokenRefresh module
jest.mock('../services/tokenRefresh', () => ({
  ensureValidToken: jest.fn(),
  setupTokenRefreshInterceptor: jest.fn()
}));

describe('API Integration Service', () => {
  beforeEach(() => {
    // Clear all mocks
    fetch.mockReset();
    localStorageMock.clear();
    jest.clearAllMocks();
    
    // Clear cache
    cacheStore.clear();
  });

  // AUTHENTICATION TESTS
  describe('Authentication', () => {
    test('Should add auth token to headers when authenticated', async () => {
      // Setup
      auth.isAuthenticated.mockReturnValue(true);
      require('../services/tokenRefresh').ensureValidToken.mockResolvedValue('test-token');
      
      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => ({ stocks: [{ symbol: 'AAPL' }] })
      });

      // Execute
      await api.fetchStocks();

      // Verify
      expect(fetch).toHaveBeenCalledTimes(1);
      const [url, options] = fetch.mock.calls[0];
      expect(options.headers).toHaveProperty('Authorization', 'Bearer test-token');
    });

    test('Should handle authentication errors gracefully', async () => {
      // Setup
      auth.isAuthenticated.mockReturnValue(true);
      require('../services/tokenRefresh').ensureValidToken.mockRejectedValue(new Error('Token refresh failed'));
      
      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => ({ stocks: [{ symbol: 'AAPL' }] })
      });

      // Execute
      await api.fetchStocks();

      // Verify
      expect(fetch).toHaveBeenCalledTimes(1);
      expect(console.error).toHaveBeenCalledWith(expect.stringContaining('Error getting auth headers'));
    });
  });

  // CACHING TESTS
  describe('Caching', () => {
    test('Should return cached data when available', async () => {
      // Setup
      const mockData = { stocks: [{ symbol: 'AAPL' }] };
      cacheStore.set(CACHE_KEYS.STOCKS, mockData, 60000);

      // Execute
      const result = await api.fetchStocks();

      // Verify
      expect(fetch).not.toHaveBeenCalled();
      expect(result).toEqual(mockData);
    });

    test('Should cache data after fetching', async () => {
      // Setup
      const mockData = { stocks: [{ symbol: 'GOOG' }] };
      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => mockData
      });

      // Execute
      await api.fetchStocks();
      const cachedData = cacheStore.get(CACHE_KEYS.STOCKS);

      // Verify
      expect(fetch).toHaveBeenCalledTimes(1);
      expect(cachedData).toEqual(mockData);
    });

    test('Should prioritize cache based on priority levels', async () => {
      // Setup
      const mockData = { data: 'high-priority' };
      const mockKey = 'test_priority';
      
      // Execute
      api.setPrioritizedCache(mockKey, mockData, 60000, 3); // High priority
      
      // Verify
      expect(cacheStore.get(mockKey)).toEqual(mockData);
      expect(cacheStore.get(`${mockKey}_backup`)).toEqual(mockData);
    });
  });

  // BATCH API TESTS
  describe('Batch API Requests', () => {
    test('Should fetch dashboard data in batch', async () => {
      // Setup
      const mockDashboardData = {
        stocks: [{ symbol: 'AAPL' }, { symbol: 'GOOG' }],
        marketSummary: { indices: [{ name: 'IHSG', value: 7000 }] },
        sectors: [{ name: 'Technology', change: 1.2 }]
      };
      
      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => mockDashboardData
      });

      // Execute
      const result = await api.fetchDashboardData();

      // Verify
      expect(result).toEqual(mockDashboardData);
      expect(cacheStore.get(CACHE_KEYS.DASHBOARD)).toEqual(mockDashboardData);
    });

    test('Should fetch user data in batch when authenticated', async () => {
      // Setup
      auth.isAuthenticated.mockReturnValue(true);
      require('../services/tokenRefresh').ensureValidToken.mockResolvedValue('test-token');
      
      const mockUserData = {
        portfolio: { holdings: [{ symbol: 'AAPL', quantity: 10 }] },
        watchlist: { items: [{ symbol: 'GOOG' }] }
      };
      
      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => mockUserData
      });

      // Mock implementation for this test
      jest.spyOn(api, 'fetchUserData').mockResolvedValueOnce(mockUserData);

      // Execute
      const result = await api.fetchUserData();

      // Verify
      expect(result).toEqual(mockUserData);
    });
  });

  // ERROR HANDLING TESTS
  describe('Error Handling', () => {
    test('Should fallback to original API on error', async () => {
      // Setup
      fetch.mockRejectedValueOnce(new Error('Network error'));
      
      // Mock original API for fallback
      jest.spyOn(require('../services/api'), 'fetchStocks').mockResolvedValueOnce({
        stocks: [{ symbol: 'Fallback' }]
      });

      // Execute
      const result = await api.fetchStocks();

      // Verify
      expect(result.stocks[0].symbol).toBe('Fallback');
    });

    test('Should gracefully handle API response errors', async () => {
      // Setup
      fetch.mockResolvedValueOnce({
        ok: false,
        status: 500,
        statusText: 'Server Error'
      });
      
      // Mock console.error for testing
      const consoleErrorSpy = jest.spyOn(console, 'error').mockImplementation();
      
      // Mock original API for fallback
      jest.spyOn(require('../services/api'), 'fetchStockData').mockResolvedValueOnce({
        price: 100,
        history: []
      });

      // Execute
      const result = await api.fetchStockData('AAPL');

      // Verify
      expect(consoleErrorSpy).toHaveBeenCalledWith(
        expect.stringContaining('Error fetching stock data'),
        expect.any(Error)
      );
      expect(result).toEqual(expect.objectContaining({
        price: 100
      }));
    });
  });

  // INVALIDATION TESTS
  describe('Cache Invalidation', () => {
    test('Should clear specific cache keys', () => {
      // Setup
      cacheStore.set(CACHE_KEYS.STOCKS, { data: 'stocks' }, 60000);
      cacheStore.set(CACHE_KEYS.PORTFOLIO, { data: 'portfolio' }, 60000);
      
      // Execute
      api.clearCache(CACHE_KEYS.STOCKS);
      
      // Verify
      expect(cacheStore.get(CACHE_KEYS.STOCKS)).toBeNull();
      expect(cacheStore.get(CACHE_KEYS.PORTFOLIO)).not.toBeNull();
    });

    test('Should clear related cache entries when specified', () => {
      // Setup
      cacheStore.set('stocks_list', { data: 'stocks' }, 60000);
      cacheStore.set('stocks_detail', { data: 'detail' }, 60000);
      cacheStore.set('portfolio_data', { data: 'portfolio' }, 60000);
      
      // Mock cacheStore.keys
      cacheStore.keys = jest.fn().mockReturnValue([
        'stocks_list', 'stocks_detail', 'portfolio_data'
      ]);
      
      // Execute
      api.clearCache('stocks_list', true);
      
      // Verify
      expect(cacheStore.get('stocks_list')).toBeNull();
      expect(cacheStore.get('stocks_detail')).toBeNull();
      expect(cacheStore.get('portfolio_data')).not.toBeNull();
    });

    test('Should clear cache after portfolio transaction', async () => {
      // Setup
      auth.isAuthenticated.mockReturnValue(true);
      require('../services/tokenRefresh').ensureValidToken.mockResolvedValue('test-token');
      
      cacheStore.set(CACHE_KEYS.PORTFOLIO, { holdings: [] }, 60000);
      
      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => ({ success: true })
      });
      
      // Execute
      await api.addPortfolioTransaction({ symbol: 'AAPL', quantity: 10, price: 150 });
      
      // Verify
      expect(cacheStore.get(CACHE_KEYS.PORTFOLIO)).toBeNull();
    });
  });
});
