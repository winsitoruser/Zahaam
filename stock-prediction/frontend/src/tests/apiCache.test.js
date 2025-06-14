import { cacheStore, CACHE_KEYS, DEFAULT_CACHE_TTL } from '../services/apiCache';

describe('API Cache Service', () => {
  beforeEach(() => {
    cacheStore.clear();
    jest.useFakeTimers();
  });

  afterEach(() => {
    jest.useRealTimers();
  });

  test('should store and retrieve items from cache', () => {
    const testData = { name: 'Test Data', value: 123 };
    cacheStore.set(CACHE_KEYS.STOCKS, testData);
    
    const cachedData = cacheStore.get(CACHE_KEYS.STOCKS);
    expect(cachedData).toEqual(testData);
  });

  test('should respect TTL and expire items', () => {
    const testData = { name: 'Test Data', value: 123 };
    const shortTTL = 1000; // 1 second
    
    cacheStore.set(CACHE_KEYS.STOCKS, testData, shortTTL);
    expect(cacheStore.get(CACHE_KEYS.STOCKS)).toEqual(testData);
    
    // Advance timer past TTL
    jest.advanceTimersByTime(1500);
    
    // Item should now be expired
    expect(cacheStore.get(CACHE_KEYS.STOCKS)).toBeNull();
  });

  test('has() should correctly check if valid item exists', () => {
    const testData = { name: 'Test Data', value: 123 };
    
    // Item doesn't exist yet
    expect(cacheStore.has(CACHE_KEYS.STOCKS)).toBe(false);
    
    cacheStore.set(CACHE_KEYS.STOCKS, testData);
    expect(cacheStore.has(CACHE_KEYS.STOCKS)).toBe(true);
    
    // Advance timer past DEFAULT_TTL
    jest.advanceTimersByTime(DEFAULT_CACHE_TTL + 1000);
    expect(cacheStore.has(CACHE_KEYS.STOCKS)).toBe(false);
  });

  test('delete() should remove item from cache', () => {
    const testData = { name: 'Test Data', value: 123 };
    cacheStore.set(CACHE_KEYS.STOCKS, testData);
    expect(cacheStore.has(CACHE_KEYS.STOCKS)).toBe(true);
    
    cacheStore.delete(CACHE_KEYS.STOCKS);
    expect(cacheStore.has(CACHE_KEYS.STOCKS)).toBe(false);
    expect(cacheStore.get(CACHE_KEYS.STOCKS)).toBeNull();
  });

  test('getWithMeta() should return item with metadata', () => {
    const testData = { name: 'Test Data', value: 123 };
    cacheStore.set(CACHE_KEYS.STOCKS, testData);
    
    const metaData = cacheStore.getWithMeta(CACHE_KEYS.STOCKS);
    
    expect(metaData.value).toEqual(testData);
    expect(metaData.expiry).toBeDefined();
    expect(metaData.cachedAt).toBeDefined();
    expect(metaData.remainingTtl).toBeDefined();
    expect(metaData.age).toBeDefined();
    
    // Age should be 0 immediately after setting
    expect(metaData.age).toBe(0);
    
    // Advance time a bit
    jest.advanceTimersByTime(1000);
    
    const updatedMeta = cacheStore.getWithMeta(CACHE_KEYS.STOCKS);
    expect(updatedMeta.age).toBe(1000);
    expect(updatedMeta.remainingTtl).toBe(DEFAULT_CACHE_TTL - 1000);
  });

  test('clear() should empty the entire cache', () => {
    cacheStore.set(CACHE_KEYS.STOCKS, { data: 'stocks' });
    cacheStore.set(CACHE_KEYS.PORTFOLIO, { data: 'portfolio' });
    
    expect(cacheStore.has(CACHE_KEYS.STOCKS)).toBe(true);
    expect(cacheStore.has(CACHE_KEYS.PORTFOLIO)).toBe(true);
    
    cacheStore.clear();
    
    expect(cacheStore.has(CACHE_KEYS.STOCKS)).toBe(false);
    expect(cacheStore.has(CACHE_KEYS.PORTFOLIO)).toBe(false);
  });

  test('clear() with prefix should only clear matching items', () => {
    cacheStore.set(`${CACHE_KEYS.STOCKS}_IDX`, { data: 'stocks_idx' });
    cacheStore.set(`${CACHE_KEYS.STOCKS}_JCI`, { data: 'stocks_jci' });
    cacheStore.set(CACHE_KEYS.PORTFOLIO, { data: 'portfolio' });
    
    cacheStore.clear(CACHE_KEYS.STOCKS);
    
    expect(cacheStore.has(`${CACHE_KEYS.STOCKS}_IDX`)).toBe(false);
    expect(cacheStore.has(`${CACHE_KEYS.STOCKS}_JCI`)).toBe(false);
    expect(cacheStore.has(CACHE_KEYS.PORTFOLIO)).toBe(true);
  });
  
  test('keys() should return all cache keys', () => {
    cacheStore.set(CACHE_KEYS.STOCKS, { data: 'stocks' });
    cacheStore.set(CACHE_KEYS.PORTFOLIO, { data: 'portfolio' });
    
    const keys = cacheStore.keys();
    
    expect(keys).toContain(CACHE_KEYS.STOCKS);
    expect(keys).toContain(CACHE_KEYS.PORTFOLIO);
    expect(keys.length).toBe(2);
  });
});
