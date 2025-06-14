"""
API Rate Limiter

This module provides rate limiting functionality to prevent API abuse
and ensure fair usage of resources.
"""

import time
from typing import Dict, Tuple, Optional
from fastapi import Request, HTTPException, status
from starlette.middleware.base import BaseHTTPMiddleware
import logging

logger = logging.getLogger(__name__)

class RateLimiter:
    """Rate limiter implementation using in-memory storage"""
    
    # Store client requests as: {client_id: [(timestamp1, count1), (timestamp2, count2), ...]}
    _requests: Dict[str, list] = {}
    
    # Default rate limits
    DEFAULT_WINDOW_SECONDS = 60  # 1 minute window
    DEFAULT_MAX_REQUESTS = 100   # 100 requests per minute
    
    @classmethod
    def get_client_id(cls, request: Request) -> str:
        """Get client identifier from request"""
        # Use forwarded IP if present (for clients behind proxies)
        forwarded = request.headers.get("X-Forwarded-For")
        if forwarded:
            client_id = forwarded.split(",")[0].strip()
        else:
            # Otherwise use direct client IP
            client_id = str(request.client.host) if request.client else "unknown"
            
        # Append path to specific API rate limits per endpoint
        path = request.url.path
        # Only consider the route pattern, not specific IDs
        # e.g., /api/stocks/AAPL becomes /api/stocks for rate limiting
        if path.count("/") > 2:
            path_parts = path.split("/")
            path = "/" + "/".join(path_parts[1:3])
            
        return f"{client_id}:{path}"
    
    @classmethod
    def is_rate_limited(cls, client_id: str, window: int = DEFAULT_WINDOW_SECONDS, 
                       max_requests: int = DEFAULT_MAX_REQUESTS) -> Tuple[bool, int]:
        """
        Check if client has exceeded rate limit
        
        Args:
            client_id: Client identifier
            window: Time window in seconds
            max_requests: Maximum requests allowed in window
            
        Returns:
            Tuple of (is_limited, requests_remaining)
        """
        current_time = time.time()
        
        # Initialize client record if not exists
        if client_id not in cls._requests:
            cls._requests[client_id] = []
        
        # Remove expired timestamps
        cls._requests[client_id] = [
            (ts, count) for ts, count in cls._requests[client_id] 
            if current_time - ts < window
        ]
        
        # Count requests in current window
        total_requests = sum(count for _, count in cls._requests[client_id])
        
        # If first request or enough time has passed, add new timestamp
        if not cls._requests[client_id] or current_time - cls._requests[client_id][-1][0] > 1:
            cls._requests[client_id].append((current_time, 1))
        else:
            # Increment request count for current timestamp
            timestamp, count = cls._requests[client_id][-1]
            cls._requests[client_id][-1] = (timestamp, count + 1)
        
        # Clean up old client records periodically
        if len(cls._requests) > 10000:  # Arbitrary cleanup threshold
            cls._cleanup_old_records(current_time, window)
        
        # Calculate remaining requests
        requests_remaining = max(0, max_requests - total_requests)
        
        return total_requests >= max_requests, requests_remaining
    
    @classmethod
    def _cleanup_old_records(cls, current_time: float, window: int) -> None:
        """Clean up old client records to prevent memory leaks"""
        # Remove clients with no recent activity
        to_remove = []
        for client_id, timestamps in cls._requests.items():
            if not timestamps or current_time - timestamps[-1][0] > window * 2:
                to_remove.append(client_id)
        
        for client_id in to_remove:
            del cls._requests[client_id]
    
    @classmethod
    def get_stats(cls) -> Dict:
        """Get rate limiter statistics"""
        current_time = time.time()
        
        active_clients = sum(1 for timestamps in cls._requests.values() 
                           if timestamps and current_time - timestamps[-1][0] < 60)
        
        return {
            "total_tracked_clients": len(cls._requests),
            "active_clients_last_minute": active_clients,
        }


class RateLimitMiddleware(BaseHTTPMiddleware):
    """Middleware to apply rate limiting to incoming requests"""
    
    def __init__(self, app, window_seconds: int = 60, max_requests: int = 100):
        """
        Initialize middleware with rate limit settings
        
        Args:
            app: FastAPI app
            window_seconds: Time window in seconds
            max_requests: Maximum requests allowed per window
        """
        super().__init__(app)
        self.window_seconds = window_seconds
        self.max_requests = max_requests
    
    async def dispatch(self, request: Request, call_next):
        """Process request with rate limiting"""
        # Skip rate limiting for specific paths
        if self._should_skip_rate_limiting(request.url.path):
            return await call_next(request)
        
        # Get client identifier
        client_id = RateLimiter.get_client_id(request)
        
        # Check if client is rate limited
        is_limited, requests_remaining = RateLimiter.is_rate_limited(
            client_id, self.window_seconds, self.max_requests
        )
        
        # If rate limited, return 429 Too Many Requests
        if is_limited:
            logger.warning(f"Rate limit exceeded for client: {client_id}")
            raise HTTPException(
                status_code=status.HTTP_429_TOO_MANY_REQUESTS,
                detail="Rate limit exceeded. Please try again later."
            )
        
        # Process request normally
        response = await call_next(request)
        
        # Add rate limit headers to response
        response.headers["X-RateLimit-Limit"] = str(self.max_requests)
        response.headers["X-RateLimit-Remaining"] = str(requests_remaining)
        response.headers["X-RateLimit-Reset"] = str(int(time.time() + self.window_seconds))
        
        return response
    
    def _should_skip_rate_limiting(self, path: str) -> bool:
        """Check if rate limiting should be skipped for this path"""
        # Skip static files and docs
        if path.startswith("/static") or path.startswith("/docs") or path.startswith("/redoc"):
            return True
            
        # Skip health checks
        if path == "/health" or path == "/api/admin/health":
            return True
            
        return False
