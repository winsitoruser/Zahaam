import { createBatch, batchFetchDashboardData } from '../services/batchApi';
import { cacheStore, CACHE_KEYS } from '../services/apiCache';
import fetchMock from 'jest-fetch-mock';

// Setup fetch mock
fetchMock.enableMocks();

describe('Batch API Service', () => {
  beforeEach(() => {
    fetchMock.resetMocks();
    cacheStore.clear();
  });

  test('createBatch should create a batch request object', () => {
    const batch = createBatch();
    expect(batch).toBeDefined();
    expect(batch.requests).toEqual([]);
  });

  test('add() should add requests to the batch', () => {
    const batch = createBatch()
      .add('stocks', 'GET', null, 'stocksReq')
      .add('portfolio', 'POST', { data: 'test' }, 'portfolioReq');
    
    expect(batch.requests.length).toBe(2);
    expect(batch.requests[0].id).toBe('stocksReq');
    expect(batch.requests[0].path).toBe('/stocks');
    expect(batch.requests[0].method).toBe('GET');
    
    expect(batch.requests[1].id).toBe('portfolioReq');
    expect(batch.requests[1].path).toBe('/portfolio');
    expect(batch.requests[1].method).toBe('POST');
    expect(batch.requests[1].body).toEqual({ data: 'test' });
  });

  test('execute() should send the batch request and return results', async () => {
    const mockBatchResponse = {
      stocksReq: {
        data: { stocks: [{ symbol: 'BBRI' }] },
        status: 200
      },
      marketReq: {
        data: { market: { idx: 7000 } },
        status: 200
      }
    };

    fetchMock.mockResponseOnce(JSON.stringify(mockBatchResponse));

    const batch = createBatch()
      .add('stocks', 'GET', null, 'stocksReq')
      .add('market', 'GET', null, 'marketReq');
    
    const results = await batch.execute();
    
    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(fetchMock.mock.calls[0][0]).toContain('/batch');
    expect(JSON.parse(fetchMock.mock.calls[0][1].body)).toEqual({
      requests: [
        { id: 'stocksReq', path: '/stocks', method: 'GET', body: null },
        { id: 'marketReq', path: '/market', method: 'GET', body: null }
      ]
    });
    
    expect(results).toEqual(mockBatchResponse);
  });

  test('clear() should empty the requests array', () => {
    const batch = createBatch()
      .add('stocks', 'GET')
      .add('market', 'GET');
    
    expect(batch.requests.length).toBe(2);
    
    batch.clear();
    expect(batch.requests.length).toBe(0);
  });

  test('execute() with single request should use direct API call instead of batch', async () => {
    const mockResponse = { stocks: [{ symbol: 'BBRI' }] };
    fetchMock.mockResponseOnce(JSON.stringify(mockResponse));

    const batch = createBatch().add('stocks', 'GET', null, 'stocksReq');
    
    const results = await batch.execute();
    
    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(fetchMock.mock.calls[0][0]).toContain('/stocks');
    expect(fetchMock.mock.calls[0][0]).not.toContain('/batch');
    
    expect(results).toEqual({ stocksReq: { data: mockResponse } });
  });

  test('batchFetchDashboardData should use cache when available', async () => {
    const cachedData = {
      stocks: [{ symbol: 'BBRI' }],
      marketSummary: { idx: 7000 },
      marketIndices: [{ name: 'IDX' }],
      sectors: [{ name: 'Finance' }]
    };
    
    cacheStore.set(CACHE_KEYS.MARKET_SUMMARY, cachedData);
    
    const result = await batchFetchDashboardData();
    
    expect(fetchMock).not.toHaveBeenCalled();
    expect(result).toEqual(cachedData);
  });

  test('batchFetchDashboardData should fetch and cache data when not in cache', async () => {
    const mockBatchResponse = {
      stocks: {
        data: { stocks: [{ symbol: 'BBRI' }] },
        status: 200
      },
      marketSummary: {
        data: { idx: 7000 },
        status: 200
      },
      marketIndices: {
        data: [{ name: 'IDX' }],
        status: 200
      },
      sectors: {
        data: [{ name: 'Finance' }],
        status: 200
      }
    };

    fetchMock.mockResponseOnce(JSON.stringify(mockBatchResponse));
    
    const result = await batchFetchDashboardData();
    
    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(result).toEqual({
      stocks: [{ symbol: 'BBRI' }],
      marketSummary: { idx: 7000 },
      marketIndices: [{ name: 'IDX' }],
      sectors: [{ name: 'Finance' }]
    });
    
    // Verify data was cached
    expect(cacheStore.has(CACHE_KEYS.MARKET_SUMMARY)).toBe(true);
  });

  test('executeIndividually should fallback to individual requests if batch fails', async () => {
    // Mock batch request to fail
    fetchMock.mockRejectOnce(new Error('Batch API unavailable'));
    
    // Mock individual requests to succeed
    fetchMock.mockResponseOnce(JSON.stringify({ stocks: [{ symbol: 'BBRI' }] }));
    fetchMock.mockResponseOnce(JSON.stringify({ market: { idx: 7000 } }));
    
    const batch = createBatch()
      .add('stocks', 'GET', null, 'stocksReq')
      .add('market', 'GET', null, 'marketReq');
    
    const results = await batch.execute();
    
    expect(fetchMock).toHaveBeenCalledTimes(3); // 1 batch fail + 2 individual requests
    expect(results.stocksReq).toBeDefined();
    expect(results.marketReq).toBeDefined();
    expect(results.stocksReq.data).toEqual({ stocks: [{ symbol: 'BBRI' }] });
  });
});
