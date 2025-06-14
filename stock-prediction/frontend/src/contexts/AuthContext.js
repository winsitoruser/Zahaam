import React, { createContext, useState, useEffect, useContext } from 'react';
import { 
  login as authLogin, 
  logout as authLogout, 
  register as authRegister,
  getCurrentUser, 
  isAuthenticated, 
  verifyToken,
  initializeAuth
} from '../services/auth';
import { 
  ensureValidToken, 
  setupTokenRefreshInterceptor, 
  isTokenExpired,
  refreshAuthToken
} from '../services/tokenRefresh';

// Create the authentication context
const AuthContext = createContext();

// Provider component that wraps app and makes auth available to any child component
export const AuthProvider = ({ children }) => {
  const [currentUser, setCurrentUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [authToken, setAuthToken] = useState(null);
  const [tokenRefreshTimer, setTokenRefreshTimer] = useState(null);

  // Set up token refresh timer
  const setupTokenRefresh = () => {
    // Clear any existing timer
    if (tokenRefreshTimer) {
      clearTimeout(tokenRefreshTimer);
    }
    
    // Set up a new timer to check token every minute
    const timerId = setInterval(async () => {
      try {
        // Check if token is valid and refresh if needed
        await ensureValidToken();
      } catch (err) {
        console.error('Token refresh failed:', err);
        // If refresh fails, log out the user
        logout();
      }
    }, 60000); // Check every minute
    
    setTokenRefreshTimer(timerId);
  };

  // Clean up token refresh timer on unmount
  useEffect(() => {
    return () => {
      if (tokenRefreshTimer) {
        clearInterval(tokenRefreshTimer);
      }
    };
  }, [tokenRefreshTimer]);

  // Initialize auth state from local storage on component mount
  useEffect(() => {
    const initAuth = async () => {
      // Initialize auth token from localStorage if exists
      const hasToken = initializeAuth();
      
      // Setup axios interceptor for automatic token refresh
      setupTokenRefreshInterceptor();
      
      if (hasToken) {
        try {
          // Ensure token is valid, refresh if needed
          const token = await ensureValidToken();
          
          if (token) {
            const user = getCurrentUser();
            setCurrentUser(user);
            setIsLoggedIn(true);
            setAuthToken(token);
            
            // Set up token refresh timer
            setupTokenRefresh();
          }
        } catch (err) {
          console.error('Error verifying authentication:', err);
        }
      }
      
      setLoading(false);
    };

    initAuth();
  }, []);

  // Login user
  const login = async (email, password) => {
    setLoading(true);
    setError('');
    
    try {
      const result = await authLogin(email, password);
      
      if (result.success) {
        setCurrentUser(result.user);
        setIsLoggedIn(true);
        setAuthToken(result.token);
        
        // Set up token refresh timer after successful login
        setupTokenRefresh();
        
        return { success: true };
      } else {
        setError(result.message);
        return { success: false, message: result.message };
      }
    } catch (err) {
      setError('Login failed. Please try again.');
      return { success: false, message: 'Login failed. Please try again.' };
    } finally {
      setLoading(false);
    }
  };

  // Register new user
  const register = async (name, email, password) => {
    setLoading(true);
    setError('');
    
    try {
      const result = await authRegister(name, email, password);
      return result;
    } catch (err) {
      setError('Registration failed. Please try again.');
      return { success: false, message: 'Registration failed. Please try again.' };
    } finally {
      setLoading(false);
    }
  };

  // Logout user
  const logout = () => {
    // Clear token refresh timer
    if (tokenRefreshTimer) {
      clearInterval(tokenRefreshTimer);
      setTokenRefreshTimer(null);
    }
    
    authLogout();
    setCurrentUser(null);
    setIsLoggedIn(false);
    setAuthToken(null);
  };

  // Manual token refresh function
  const refreshToken = async () => {
    try {
      const newToken = await refreshAuthToken();
      if (newToken) {
        setAuthToken(newToken);
        return true;
      }
      return false;
    } catch (err) {
      console.error('Manual token refresh failed:', err);
      return false;
    }
  };

  // Context value
  const value = {
    currentUser,
    isLoggedIn,
    loading,
    error,
    authToken,
    login,
    register,
    logout,
    refreshToken,
    isAuthenticated: () => isLoggedIn && !!authToken
  };

  return (
    <AuthContext.Provider value={value}>
      {!loading && children}
    </AuthContext.Provider>
  );
};

// Custom hook to use auth context
export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
