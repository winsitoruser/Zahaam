/**
 * Token Refresh Service
 * Handles authentication token refresh when expiring
 */

import axios from 'axios';
import jwtDecode from 'jwt-decode';

// Constants
const API_URL = 'http://localhost:8000/api';
const TOKEN_KEY = 'zahaam_auth_token';
const REFRESH_TOKEN_KEY = 'zahaam_refresh_token';

// Buffer time in seconds (refresh token if it expires within this time)
const TOKEN_REFRESH_BUFFER = 300; // 5 minutes

/**
 * Decode a JWT token and extract payload
 * @param {string} token - JWT token
 * @returns {Object} Decoded token payload or null if invalid
 */
export const decodeToken = (token) => {
  if (!token) return null;
  
  try {
    return jwtDecode(token);
  } catch (error) {
    console.error('Error decoding token:', error);
    return null;
  }
};

/**
 * Check if token is expired
 * @param {string} token - JWT token
 * @returns {boolean} True if token is expired or about to expire within buffer time
 */
export const isTokenExpired = (token) => {
  const decoded = decodeToken(token);
  
  if (!decoded || !decoded.exp) {
    return true;
  }
  
  // Get current time in seconds
  const currentTime = Math.floor(Date.now() / 1000);
  
  // Return true if token is expired or about to expire within buffer time
  return decoded.exp - currentTime < TOKEN_REFRESH_BUFFER;
};

/**
 * Get stored auth token
 * @returns {string|null} Auth token or null if not found
 */
export const getStoredToken = () => {
  return localStorage.getItem(TOKEN_KEY);
};

/**
 * Store auth tokens
 * @param {string} token - Auth token
 * @param {string} refreshToken - Refresh token
 */
export const storeTokens = (token, refreshToken) => {
  if (token) {
    localStorage.setItem(TOKEN_KEY, token);
  }
  if (refreshToken) {
    localStorage.setItem(REFRESH_TOKEN_KEY, refreshToken);
  }
};

/**
 * Remove stored tokens
 */
export const removeTokens = () => {
  localStorage.removeItem(TOKEN_KEY);
  localStorage.removeItem(REFRESH_TOKEN_KEY);
};

/**
 * Refresh auth token using refresh token
 * @returns {Promise<string|null>} New auth token or null if refresh failed
 */
export const refreshAuthToken = async () => {
  try {
    const refreshToken = localStorage.getItem(REFRESH_TOKEN_KEY);
    
    if (!refreshToken) {
      throw new Error('No refresh token available');
    }
    
    const response = await axios.post(`${API_URL}/auth/refresh`, {
      refreshToken
    });
    
    const { token: newToken, refreshToken: newRefreshToken } = response.data;
    
    // Store new tokens
    storeTokens(newToken, newRefreshToken);
    
    // Update axios default headers
    axios.defaults.headers.common['Authorization'] = `Bearer ${newToken}`;
    
    console.log('Token refreshed successfully');
    return newToken;
  } catch (error) {
    console.error('Failed to refresh token:', error);
    // On failure, clear tokens and return null
    removeTokens();
    return null;
  }
};

/**
 * Check token and refresh if needed
 * @returns {Promise<string|null>} Valid auth token or null if refresh failed
 */
export const ensureValidToken = async () => {
  const token = getStoredToken();
  
  if (!token) {
    return null;
  }
  
  if (isTokenExpired(token)) {
    return await refreshAuthToken();
  }
  
  return token;
};

/**
 * Set up axios interceptor to handle token refresh
 */
export const setupTokenRefreshInterceptor = () => {
  // Response interceptor to handle 401 errors
  axios.interceptors.response.use(
    (response) => response,
    async (error) => {
      const originalRequest = error.config;
      
      // If error is 401 Unauthorized and we haven't retried yet
      if (error.response?.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true;
        
        try {
          // Try to refresh the token
          const newToken = await refreshAuthToken();
          
          if (newToken) {
            // Update request Authorization header with new token
            originalRequest.headers['Authorization'] = `Bearer ${newToken}`;
            // Retry the original request
            return axios(originalRequest);
          }
        } catch (refreshError) {
          console.error('Error during token refresh:', refreshError);
        }
      }
      
      return Promise.reject(error);
    }
  );
};
