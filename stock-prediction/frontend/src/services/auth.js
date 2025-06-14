// Authentication service for Zahaam Stock Prediction System
import axios from 'axios';

// Constants
const API_URL = 'http://localhost:8000/api';
const TOKEN_KEY = 'zahaam_auth_token';
const USER_KEY = 'zahaam_user';

// Set auth token for all future requests
const setAuthToken = (token) => {
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    localStorage.setItem(TOKEN_KEY, token);
  } else {
    delete axios.defaults.headers.common['Authorization'];
    localStorage.removeItem(TOKEN_KEY);
  }
};

// Initialize authentication from stored token
const initializeAuth = () => {
  const token = localStorage.getItem(TOKEN_KEY);
  if (token) {
    setAuthToken(token);
    return true;
  }
  return false;
};

// Login user and store token
const login = async (email, password) => {
  try {
    const response = await axios.post(`${API_URL}/auth/login`, { email, password });
    const { token, user } = response.data;
    
    setAuthToken(token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
    
    return { success: true, user };
  } catch (error) {
    console.error('Login error:', error);
    return { 
      success: false, 
      message: error.response?.data?.message || 'Login failed. Please check your credentials.'
    };
  }
};

// Register new user
const register = async (name, email, password) => {
  try {
    const response = await axios.post(`${API_URL}/auth/register`, { 
      name, 
      email, 
      password 
    });
    
    return { success: true, message: 'Registration successful. Please log in.' };
  } catch (error) {
    console.error('Registration error:', error);
    return { 
      success: false, 
      message: error.response?.data?.message || 'Registration failed. Please try again.'
    };
  }
};

// Logout user and clear storage
const logout = () => {
  setAuthToken(null);
  localStorage.removeItem(USER_KEY);
  return { success: true };
};

// Get current user from storage
const getCurrentUser = () => {
  try {
    const user = localStorage.getItem(USER_KEY);
    return user ? JSON.parse(user) : null;
  } catch (e) {
    console.error('Error getting current user:', e);
    return null;
  }
};

// Check if user is authenticated
const isAuthenticated = () => {
  return !!localStorage.getItem(TOKEN_KEY);
};

// Verify token validity with backend
const verifyToken = async () => {
  const token = localStorage.getItem(TOKEN_KEY);
  if (!token) return false;
  
  try {
    const response = await axios.get(`${API_URL}/auth/verify`);
    return response.data.valid;
  } catch (error) {
    console.error('Token verification failed:', error);
    // If verification fails, logout user
    logout();
    return false;
  }
};

export {
  login,
  register,
  logout,
  getCurrentUser,
  isAuthenticated,
  verifyToken,
  initializeAuth,
  setAuthToken
};
