import * as auth from '../services/auth';
import fetchMock from 'jest-fetch-mock';

// Setup fetch mock
fetchMock.enableMocks();

describe('Authentication Service', () => {
  beforeEach(() => {
    fetchMock.resetMocks();
    localStorage.clear();
    jest.spyOn(Storage.prototype, 'setItem');
    jest.spyOn(Storage.prototype, 'getItem');
    jest.spyOn(Storage.prototype, 'removeItem');
  });

  afterEach(() => {
    jest.restoreAllMocks();
  });

  test('login should store token and user in localStorage on successful login', async () => {
    const mockResponse = {
      token: 'test-auth-token',
      user: { id: '123', name: 'Test User', email: 'test@example.com' }
    };

    fetchMock.mockResponseOnce(JSON.stringify(mockResponse));

    const result = await auth.login('test@example.com', 'password');

    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(fetchMock.mock.calls[0][0]).toContain('/auth/login');
    expect(fetchMock.mock.calls[0][1].method).toBe('POST');
    expect(fetchMock.mock.calls[0][1].body).toBe(JSON.stringify({ 
      email: 'test@example.com', 
      password: 'password' 
    }));
    
    expect(localStorage.setItem).toHaveBeenCalledWith('zahaam_auth_token', 'test-auth-token');
    expect(localStorage.setItem).toHaveBeenCalledWith('zahaam_user', JSON.stringify(mockResponse.user));
    
    expect(result.success).toBe(true);
    expect(result.user).toEqual(mockResponse.user);
  });

  test('login should handle API errors gracefully', async () => {
    fetchMock.mockRejectOnce(new Error('Network error'));

    const result = await auth.login('test@example.com', 'password');

    expect(result.success).toBe(false);
    expect(result.message).toBeDefined();
  });

  test('register should call the API with correct parameters', async () => {
    const mockResponse = { 
      success: true, 
      message: 'Registration successful' 
    };

    fetchMock.mockResponseOnce(JSON.stringify(mockResponse));

    const result = await auth.register('Test User', 'test@example.com', 'password');

    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(fetchMock.mock.calls[0][0]).toContain('/auth/register');
    expect(fetchMock.mock.calls[0][1].method).toBe('POST');
    expect(JSON.parse(fetchMock.mock.calls[0][1].body)).toEqual({
      name: 'Test User',
      email: 'test@example.com',
      password: 'password'
    });

    expect(result.success).toBe(true);
  });

  test('logout should remove token and user from localStorage', () => {
    localStorage.setItem('zahaam_auth_token', 'test-token');
    localStorage.setItem('zahaam_user', JSON.stringify({ name: 'Test' }));

    const result = auth.logout();

    expect(localStorage.removeItem).toHaveBeenCalledWith('zahaam_auth_token');
    expect(localStorage.removeItem).toHaveBeenCalledWith('zahaam_user');
    expect(result.success).toBe(true);
  });

  test('getCurrentUser should retrieve and parse user from localStorage', () => {
    const mockUser = { id: '123', name: 'Test User', email: 'test@example.com' };
    localStorage.setItem('zahaam_user', JSON.stringify(mockUser));

    const user = auth.getCurrentUser();

    expect(localStorage.getItem).toHaveBeenCalledWith('zahaam_user');
    expect(user).toEqual(mockUser);
  });

  test('getCurrentUser should handle missing or invalid user data', () => {
    // No user in localStorage
    expect(auth.getCurrentUser()).toBeNull();

    // Invalid JSON
    localStorage.setItem('zahaam_user', 'not-valid-json');
    expect(auth.getCurrentUser()).toBeNull();
  });

  test('isAuthenticated should check for token existence', () => {
    expect(auth.isAuthenticated()).toBe(false);
    
    localStorage.setItem('zahaam_auth_token', 'test-token');
    expect(auth.isAuthenticated()).toBe(true);
  });

  test('verifyToken should validate token with API', async () => {
    localStorage.setItem('zahaam_auth_token', 'test-token');
    fetchMock.mockResponseOnce(JSON.stringify({ valid: true }));

    const result = await auth.verifyToken();

    expect(fetchMock).toHaveBeenCalledTimes(1);
    expect(fetchMock.mock.calls[0][0]).toContain('/auth/verify');
    expect(result).toBe(true);
  });

  test('verifyToken should handle invalid token', async () => {
    localStorage.setItem('zahaam_auth_token', 'invalid-token');
    fetchMock.mockResponseOnce(JSON.stringify({ valid: false }), { status: 401 });

    const result = await auth.verifyToken();

    expect(result).toBe(false);
    expect(localStorage.getItem('zahaam_auth_token')).toBeNull(); // Token should be removed
  });
});
