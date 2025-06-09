"""
Cache Manager for Stock Data

This module provides caching functionality to reduce database load
and improve API response times for frequently accessed data.
"""

import time
import logging
import functools
import json
from datetime import datetime, timedelta
from typing import Dict, Any, Callable, Optional, TypeVar, cast

# Type definitions for better typing
T = TypeVar('T')
CacheDict = Dict[str, Dict[str, Any]]

logger = logging.getLogger(__name__)

class CacheManager:
    """Cache manager for stock data and frequently accessed API responses"""
    
    # Cache storage
    _cache: CacheDict = {}
    
    # Default TTL (Time to live) in seconds
    DEFAULT_TTL = 300  # 5 minutes
    
    @classmethod
    def get(cls, key: str) -> Optional[Dict[str, Any]]:
        """
        Get an item from cache if it exists and is not expired
        
        Args:
            key: Cache key to retrieve
            
        Returns:
            Cache entry or None if not found or expired
        """
        if key not in cls._cache:
            return None
        
        cache_entry = cls._cache[key]
        
        # Check if entry is expired
        if time.time() > cache_entry.get('expires_at', 0):
            # Remove expired entry
            del cls._cache[key]
            return None
            
        return cache_entry.get('data')
    
    @classmethod
    def set(cls, key: str, data: Dict[str, Any], ttl: int = DEFAULT_TTL) -> None:
        """
        Set an item in cache with expiration
        
        Args:
            key: Cache key to set
            data: Data to cache
            ttl: Time to live in seconds (default: 5 minutes)
        """
        expires_at = time.time() + ttl
        
        cls._cache[key] = {
            'data': data,
            'expires_at': expires_at,
            'created_at': time.time()
        }
    
    @classmethod
    def delete(cls, key: str) -> bool:
        """
        Delete an item from cache
        
        Args:
            key: Cache key to delete
            
        Returns:
            True if item was deleted, False if not found
        """
        if key in cls._cache:
            del cls._cache[key]
            return True
        return False
    
    @classmethod
    def clear(cls) -> None:
        """Clear all cache entries"""
        cls._cache.clear()
    
    @classmethod
    def get_stats(cls) -> Dict[str, Any]:
        """Get cache statistics"""
        total_entries = len(cls._cache)
        expired_entries = sum(
            1 for entry in cls._cache.values() 
            if time.time() > entry.get('expires_at', 0)
        )
        
        # Calculate average age of cache entries
        if total_entries > 0:
            current_time = time.time()
            avg_age = sum(
                current_time - entry.get('created_at', current_time) 
                for entry in cls._cache.values()
            ) / total_entries
        else:
            avg_age = 0
            
        return {
            'total_entries': total_entries,
            'expired_entries': expired_entries,
            'active_entries': total_entries - expired_entries,
            'avg_age_seconds': round(avg_age, 2),
            'memory_usage_estimate_kb': total_entries * 2  # Rough estimate
        }
    
    @classmethod
    def clear_expired(cls) -> int:
        """
        Clear all expired cache entries
        
        Returns:
            Number of entries cleared
        """
        expired_keys = [
            key for key, entry in cls._cache.items()
            if time.time() > entry.get('expires_at', 0)
        ]
        
        for key in expired_keys:
            del cls._cache[key]
            
        return len(expired_keys)


# Decorator for function-level caching
def cached(ttl: int = CacheManager.DEFAULT_TTL, key_prefix: str = ''):
    """
    Decorator to cache function results
    
    Args:
        ttl: Time to live in seconds (default: 5 minutes)
        key_prefix: Prefix for cache key
        
    Returns:
        Decorator function
    """
    def decorator(func: Callable[..., T]) -> Callable[..., T]:
        @functools.wraps(func)
        def wrapper(*args, **kwargs) -> T:
            # Create cache key from function name, args and kwargs
            cache_key = key_prefix + func.__name__
            
            # Add args to cache key
            if args:
                cache_key += '_' + '_'.join(str(arg) for arg in args)
            
            # Add kwargs to cache key (sorted for consistency)
            if kwargs:
                kwargs_str = '_'.join(f"{k}={v}" for k, v in sorted(kwargs.items()))
                cache_key += '_' + kwargs_str
            
            # Attempt to get from cache
            cached_result = CacheManager.get(cache_key)
            if cached_result is not None:
                logger.debug(f"Cache hit for {cache_key}")
                return cast(T, cached_result['result'])
            
            # Cache miss, execute function
            logger.debug(f"Cache miss for {cache_key}")
            result = func(*args, **kwargs)
            
            # Store in cache if cacheable
            try:
                # Test if result is JSON serializable
                json.dumps(result)
                CacheManager.set(cache_key, {'result': result}, ttl)
            except (TypeError, OverflowError):
                # If result is not JSON serializable, don't cache
                logger.warning(f"Result for {cache_key} is not cacheable (not JSON serializable)")
            
            return result
        
        return wrapper
    
    return decorator
