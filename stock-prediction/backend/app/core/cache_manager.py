"""
Cache manager for the stock prediction application
"""
import time
import json
from datetime import datetime, timedelta
from functools import wraps
import logging

logger = logging.getLogger(__name__)

# In-memory cache
_cache = {}

def cache_data(ttl_seconds=300):
    """
    Decorator to cache function results in memory
    
    Args:
        ttl_seconds: Time to live in seconds (default 5 minutes)
    """
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            # Create a cache key based on function name and arguments
            key_parts = [func.__name__]
            # Add non-database arguments to the key
            for arg in args:
                if hasattr(arg, '__dict__'):  # Skip complex objects like db session
                    continue
                key_parts.append(str(arg))
            
            # Add non-database keyword arguments to the key
            for k, v in kwargs.items():
                if k == 'db':  # Skip database session
                    continue
                key_parts.append(f"{k}:{v}")
            
            cache_key = "-".join(key_parts)
            
            # Check if we have a valid cached result
            if cache_key in _cache:
                cached_time, cached_result = _cache[cache_key]
                # Check if the cached result is still valid
                if time.time() - cached_time < ttl_seconds:
                    logger.debug(f"Cache hit for {cache_key}")
                    return cached_result
                else:
                    logger.debug(f"Cache expired for {cache_key}")
            
            # If not cached or expired, call the function
            result = func(*args, **kwargs)
            
            # Cache the result
            _cache[cache_key] = (time.time(), result)
            logger.debug(f"Cached result for {cache_key}")
            
            return result
        return wrapper
    return decorator

def clear_cache(prefix=None):
    """
    Clear the entire cache or entries with a specific prefix
    
    Args:
        prefix: Optional prefix for keys to clear
    """
    global _cache
    if prefix:
        # Clear only keys that start with the prefix
        keys_to_clear = [k for k in _cache.keys() if k.startswith(prefix)]
        for k in keys_to_clear:
            del _cache[k]
        logger.info(f"Cleared {len(keys_to_clear)} cache entries with prefix {prefix}")
    else:
        # Clear all cache
        _cache = {}
        logger.info("Cleared entire cache")

def get_cache_stats():
    """Get cache statistics"""
    return {
        "total_entries": len(_cache),
        "size_estimate": sum(len(str(v[1])) for v in _cache.values()),
        "oldest_entry": min(_cache.values(), key=lambda x: x[0])[0] if _cache else None,
        "newest_entry": max(_cache.values(), key=lambda x: x[0])[0] if _cache else None
    }
