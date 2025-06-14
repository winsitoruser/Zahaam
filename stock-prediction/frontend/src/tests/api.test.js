import * as api from '../services/api';
import { cacheStore, CACHE_KEYS } from '../services/apiCache';
import fetchMock from 'jest-fetch-mock';

// Setup fetch mock
fetchMock.enableMocks();

describe('API Service', () => {
  beforeEach(() => {
    fetchMock.resetMocks();
    cacheStore.clear(); // Clear cache before each test
  });

  test('fetchStocks should retrieve and return stocks data', async () => {
    const mockData = {
      stocks: [
        { symbol: 'BBRI', name: 'Bank BRI', price: 5150 },
        { symbol: 'TLKM', name: 'Telkom Indonesia', price: 3970 }
      ],
      marketIndices: [
        { name: 'IHSG', value: 7025.36, change: 0.38 }
      ]
    };

    fetchMock.mockResponseOnce(JSON.stringify(mockData));

    const result = await api.fetchStocks();

    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(result).toEqual(mockData);
    expect(result.stocks.length).toBe(2);
  });

  test('fetchStocks should use fallback data when API fails', async () => {
    fetchMock.mockRejectOnce(new Error('API is down'));

    const result = await api.fetchStocks();

    expect(result).toBeDefined();
    expect(result.stocks).toBeInstanceOf(Array);
    expect(result.marketIndices).toBeInstanceOf(Array);
  });

  test('fetchStockData should fetch and return data for a specific stock', async () => {
    const mockData = {
      symbol: 'BBRI',
      name: 'Bank BRI',
      priceData: [
        { date: '2023-01-01', open: 5000, high: 5200, low: 4950, close: 5150, volume: 12345678 }
      ],
      company: { 
        sector: 'Finance',
        industry: 'Banking' 
      }
    };

    fetchMock.mockResponseOnce(JSON.stringify(mockData));

    const result = await api.fetchStockData('BBRI', '1y', '1d');

    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(result).toEqual(mockData);
  });

  test('formatCurrency should correctly format currency values', () => {
    expect(api.formatCurrency(1000)).toBe('Rp1,000');
    expect(api.formatCurrency(1500.75)).toBe('Rp1,501'); // Rounded
    expect(api.formatCurrency(1000000)).toBe('Rp1,000,000');
    expect(api.formatCurrency(0)).toBe('Rp0');
  });

  test('getValueColor should return correct color class based on value', () => {
    expect(api.getValueColor(5)).toBe('text-success');
    expect(api.getValueColor(-3.2)).toBe('text-danger');
    expect(api.getValueColor(0)).toBe('text-secondary');
    expect(api.getValueColor(null)).toBe('text-secondary');
    expect(api.getValueColor(undefined)).toBe('text-secondary');
  });

  test('fetchPrediction should fetch prediction results', async () => {
    const mockData = {
      symbol: 'BBRI',
      strategy: 'moving_average',
      currentPrice: 5150,
      predictedPrice: 5250,
      predictedChangePercent: 1.94
    };

    fetchMock.mockResponseOnce(JSON.stringify(mockData));

    const result = await api.fetchPrediction('BBRI', 'moving_average', { period: 180 });

    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(fetchMock.mock.calls[0][0]).toContain('/prediction/BBRI');
    expect(fetchMock.mock.calls[0][0]).toContain('strategy=moving_average');
    expect(result).toEqual(mockData);
  });

  test('addToWatchlist should post to watchlist API endpoint', async () => {
    const mockResponse = { 
      success: true,
      message: 'Stock added to watchlist',
      watchlist: [{ symbol: 'BBRI', name: 'Bank BRI' }]
    };

    fetchMock.mockResponseOnce(JSON.stringify(mockResponse));

    const result = await api.addToWatchlist('BBRI');

    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(fetchMock.mock.calls[0][1].method).toBe('POST');
    expect(fetchMock.mock.calls[0][1].body).toBe(JSON.stringify({ symbol: 'BBRI' }));
    expect(result).toEqual(mockResponse);
  });
});
