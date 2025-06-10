"""
InfluxDB client for time series metrics
"""
import os
import logging
from datetime import datetime
from typing import Dict, Any, List, Optional, Union

try:
    import influxdb_client
    from influxdb_client.client.write_api import SYNCHRONOUS, ASYNCHRONOUS
    from influxdb_client.client.exceptions import InfluxDBError
except ImportError:
    logging.warning("influxdb_client package not installed. InfluxDB metrics will not be available.")
    influxdb_client = None

logger = logging.getLogger(__name__)

class InfluxDBMetrics:
    """InfluxDB metrics client for storing time series data"""
    
    _instance = None
    
    @staticmethod
    def get_instance():
        """Singleton instance getter"""
        if InfluxDBMetrics._instance is None:
            InfluxDBMetrics._instance = InfluxDBMetrics()
        return InfluxDBMetrics._instance
    
    def __init__(self):
        """Initialize InfluxDB client"""
        self.url = os.environ.get("INFLUXDB_URL", "http://localhost:8086")
        self.token = os.environ.get("INFLUXDB_TOKEN", "zahaam-token-secret")
        self.org = os.environ.get("INFLUXDB_ORG", "zahaam")
        self.bucket = os.environ.get("INFLUXDB_BUCKET", "stock_metrics")
        self.client = None
        self.write_api = None
        self.query_api = None
        self._init_client()
    
    def _init_client(self):
        """Initialize InfluxDB client connection"""
        if influxdb_client is None:
            logger.warning("influxdb_client package not installed. InfluxDB metrics will not be available.")
            return
            
        try:
            self.client = influxdb_client.InfluxDBClient(
                url=self.url,
                token=self.token,
                org=self.org
            )
            self.write_api = self.client.write_api(write_options=SYNCHRONOUS)
            self.query_api = self.client.query_api()
            logger.info(f"InfluxDB client initialized: {self.url}, org: {self.org}, bucket: {self.bucket}")
        except Exception as e:
            logger.error(f"Failed to initialize InfluxDB client: {str(e)}")
            self.client = None
            self.write_api = None
            self.query_api = None
    
    def write_point(self, measurement: str, tags: Dict[str, str], 
                   fields: Dict[str, Union[float, int, bool, str]], 
                   timestamp: Optional[datetime] = None):
        """
        Write a single data point to InfluxDB
        
        Args:
            measurement: The measurement name
            tags: Dictionary of tags (indexed values)
            fields: Dictionary of fields (metric values)
            timestamp: Optional timestamp (default: now)
        """
        if self.write_api is None:
            logger.warning("InfluxDB client not initialized, skipping metrics write")
            return False
            
        try:
            point = influxdb_client.Point(measurement)
            
            # Add tags
            for tag_key, tag_value in tags.items():
                point = point.tag(tag_key, tag_value)
            
            # Add fields
            for field_key, field_value in fields.items():
                point = point.field(field_key, field_value)
            
            # Add timestamp if provided
            if timestamp:
                point = point.time(timestamp)
            
            self.write_api.write(bucket=self.bucket, record=point)
            return True
        except Exception as e:
            logger.error(f"Error writing point to InfluxDB: {str(e)}")
            return False
    
    def write_points(self, points: List[Dict[str, Any]]):
        """
        Write multiple data points to InfluxDB
        
        Args:
            points: List of dictionaries with 'measurement', 'tags', 'fields', and optional 'timestamp'
        """
        if self.write_api is None:
            logger.warning("InfluxDB client not initialized, skipping metrics write")
            return False
            
        try:
            influx_points = []
            for p in points:
                point = influxdb_client.Point(p['measurement'])
                
                # Add tags
                for tag_key, tag_value in p.get('tags', {}).items():
                    point = point.tag(tag_key, tag_value)
                
                # Add fields
                for field_key, field_value in p.get('fields', {}).items():
                    point = point.field(field_key, field_value)
                
                # Add timestamp if provided
                if 'timestamp' in p:
                    point = point.time(p['timestamp'])
                
                influx_points.append(point)
            
            self.write_api.write(bucket=self.bucket, record=influx_points)
            return True
        except Exception as e:
            logger.error(f"Error writing points to InfluxDB: {str(e)}")
            return False
            
    def query_data(self, query: str):
        """
        Query data from InfluxDB using Flux query language
        
        Args:
            query: The Flux query string
        
        Returns:
            Query results or None on error
        """
        if self.query_api is None:
            logger.warning("InfluxDB client not initialized, skipping metrics query")
            return None
            
        try:
            result = self.query_api.query(query=query, org=self.org)
            return result
        except Exception as e:
            logger.error(f"Error querying InfluxDB: {str(e)}")
            return None
    
    def health_check(self):
        """
        Check if InfluxDB is reachable and healthy
        
        Returns:
            True if healthy, False otherwise
        """
        if self.client is None:
            return False
            
        try:
            health = self.client.health()
            return health.status == "pass"
        except Exception:
            return False
