"""
System monitoring utilities for collecting and reporting performance metrics
"""
import os
import time
import logging
import psutil
import socket
import threading
from typing import Dict, Any, List, Optional
from datetime import datetime

from app.utils.influxdb_client import InfluxDBMetrics

logger = logging.getLogger(__name__)

class SystemMonitoring:
    """System monitoring collection and reporting"""
    
    _instance = None
    
    @staticmethod
    def get_instance():
        """Singleton instance getter"""
        if SystemMonitoring._instance is None:
            SystemMonitoring._instance = SystemMonitoring()
        return SystemMonitoring._instance
    
    def __init__(self):
        """Initialize monitoring"""
        self.hostname = socket.gethostname()
        self.influx = InfluxDBMetrics.get_instance()
        self.monitor_thread = None
        self.stop_event = threading.Event()
        self.interval = int(os.environ.get("MONITORING_INTERVAL_SECONDS", "60"))
    
    def start_monitoring(self):
        """Start the background monitoring thread"""
        if self.monitor_thread is not None and self.monitor_thread.is_alive():
            logger.warning("Monitoring thread already running")
            return
            
        self.stop_event.clear()
        self.monitor_thread = threading.Thread(
            target=self._monitoring_loop,
            daemon=True,
            name="system-monitor"
        )
        self.monitor_thread.start()
        logger.info(f"System monitoring started with interval: {self.interval}s")
    
    def stop_monitoring(self):
        """Stop the background monitoring thread"""
        if self.monitor_thread is None or not self.monitor_thread.is_alive():
            logger.warning("No monitoring thread running")
            return
            
        self.stop_event.set()
        self.monitor_thread.join(timeout=5.0)
        if self.monitor_thread.is_alive():
            logger.warning("Monitoring thread did not terminate gracefully")
        else:
            logger.info("System monitoring stopped")
    
    def _monitoring_loop(self):
        """Background thread loop for collecting metrics"""
        while not self.stop_event.is_set():
            try:
                # Collect and report system metrics
                self.collect_system_metrics()
                
                # Collect and report process metrics
                self.collect_process_metrics()
                
                # Sleep for the interval or until stopped
                self.stop_event.wait(self.interval)
            except Exception as e:
                logger.error(f"Error in monitoring loop: {str(e)}")
                # Don't hammer if there's an error
                time.sleep(5)
    
    def collect_system_metrics(self):
        """Collect and report system-level metrics"""
        try:
            # System metrics
            cpu_percent = psutil.cpu_percent(interval=1)
            memory = psutil.virtual_memory()
            disk = psutil.disk_usage('/')
            
            # Write to InfluxDB
            self.influx.write_point(
                measurement="system_metrics",
                tags={
                    "host": self.hostname,
                    "service": "zahaam"
                },
                fields={
                    "cpu_percent": float(cpu_percent),
                    "memory_percent": float(memory.percent),
                    "memory_used_mb": float(memory.used) / (1024 * 1024),
                    "memory_free_mb": float(memory.available) / (1024 * 1024),
                    "disk_percent": float(disk.percent),
                    "disk_used_gb": float(disk.used) / (1024 * 1024 * 1024),
                    "disk_free_gb": float(disk.free) / (1024 * 1024 * 1024)
                }
            )
        except Exception as e:
            logger.error(f"Error collecting system metrics: {str(e)}")
    
    def collect_process_metrics(self):
        """Collect and report process-level metrics"""
        try:
            process = psutil.Process()
            
            # Process metrics
            process_cpu = process.cpu_percent(interval=0.5)
            process_memory = process.memory_info()
            
            # Write to InfluxDB
            self.influx.write_point(
                measurement="process_metrics",
                tags={
                    "host": self.hostname,
                    "service": "zahaam",
                    "process_name": process.name(),
                    "pid": str(process.pid)
                },
                fields={
                    "cpu_percent": float(process_cpu),
                    "memory_rss_mb": float(process_memory.rss) / (1024 * 1024),
                    "memory_vms_mb": float(process_memory.vms) / (1024 * 1024),
                    "open_files": len(process.open_files()),
                    "threads": process.num_threads()
                }
            )
        except Exception as e:
            logger.error(f"Error collecting process metrics: {str(e)}")
    
    # --- API Monitoring Methods ---
    
    def record_api_call(self, endpoint: str, method: str, status_code: int, 
                       duration_ms: float, user_id: Optional[str] = None):
        """
        Record API call metrics
        
        Args:
            endpoint: API endpoint path
            method: HTTP method (GET, POST, etc)
            status_code: HTTP status code
            duration_ms: Request duration in milliseconds
            user_id: Optional user ID
        """
        try:
            tags = {
                "host": self.hostname,
                "endpoint": endpoint,
                "method": method, 
                "status_code": str(status_code)
            }
            
            if user_id:
                tags["user_id"] = user_id
                
            self.influx.write_point(
                measurement="api_metrics",
                tags=tags,
                fields={
                    "duration_ms": float(duration_ms),
                    "count": 1
                }
            )
        except Exception as e:
            logger.error(f"Error recording API metrics: {str(e)}")
    
    # --- ML Model Monitoring Methods ---
    
    def record_ml_model_metrics(self, ticker, model_type, accuracy=None, mse=None, r2_score=None, training_time=None, data_points=None):
        """Record metrics for an ML model

        Args:
            ticker: Stock ticker
            model_type: Type of ML model
            accuracy: Model accuracy score
            mse: Mean Squared Error
            r2_score: R-squared score
            training_time: Time taken for training in ms
            data_points: Number of data points used
        """
        fields = {}
        if accuracy is not None:
            fields['accuracy'] = float(accuracy)
        if mse is not None:
            fields['mse'] = float(mse)
        if r2_score is not None:
            fields['r2_score'] = float(r2_score)
        if training_time is not None:
            fields['training_time'] = float(training_time)
        if data_points is not None:
            fields['data_points'] = int(data_points)
            
        if fields:  # Only write if we have at least one field
            self._write_point('ml_model_metrics', fields, {'ticker': ticker, 'model_type': model_type})
    
    def record_model_metrics(self, ticker: str, model_type: str, metrics: Dict[str, float]):
        """
        Record ML model performance metrics
        
        Args:
            ticker: Stock ticker symbol
            model_type: Type of ML model (lstm, random_forest, etc)
            metrics: Dictionary of metric names and values
        """
        try:
            self.influx.write_point(
                measurement="model_metrics",
                tags={
                    "host": self.hostname,
                    "ticker": ticker,
                    "model_type": model_type
                },
                fields=metrics
            )
        except Exception as e:
            logger.error(f"Error recording model metrics: {str(e)}")
    
    def record_prediction_metrics(self, ticker: str, model_type: str, 
                                prediction_horizon: int, accuracy: float):
        """
        Record prediction performance metrics
        
        Args:
            ticker: Stock ticker symbol
            model_type: Type of ML model used
            prediction_horizon: Number of days in prediction
            accuracy: Accuracy metric (e.g., MAPE or similar)
        """
        try:
            self.influx.write_point(
                measurement="prediction_metrics",
                tags={
                    "host": self.hostname,
                    "ticker": ticker,
                    "model_type": model_type,
                    "horizon": str(prediction_horizon)
                },
                fields={
                    "accuracy": float(accuracy)
                }
            )
        except Exception as e:
            logger.error(f"Error recording prediction metrics: {str(e)}")
    
    # --- Stock Data Monitoring Methods ---
    
    def record_stock_update(self, ticker, source, duration_ms, data_points, error=None):
        """Record metrics for stock data updates

        Args:
            ticker: Stock ticker
            source: Data source
            duration_ms: Time taken for update in ms
            data_points: Number of data points updated
            error: Any error encountered (optional)
        """
        tags = {'ticker': ticker, 'source': source}
        fields = {
            'duration_ms': float(duration_ms),
            'data_points': int(data_points)
        }
            
        if error:
            tags['error'] = str(error)[:255]  # Limit error message length
            fields['has_error'] = 1
        else:
            fields['has_error'] = 0
            
        self._write_point('stock_updates', fields, tags)
    
    # --- Celery Task Monitoring ---
    
    def record_task_execution(self, task_name, task_id, status, duration_ms, queue=None):
        """Record metrics for Celery task execution

        Args:
            task_name: Name of the Celery task
            task_id: Task ID
            status: Task status (e.g., 'SUCCESS', 'FAILURE')
            duration_ms: Duration of task execution in ms
            queue: Queue the task was executed in
        """
        tags = {
            'task_name': task_name,
            'status': status,
        }
        if queue:
            tags['queue'] = queue
            
        fields = {
            'duration_ms': float(duration_ms)
        }
        
        # Add status as numeric field for easier querying
        if status.lower() == 'success':
            fields['success'] = 1
            fields['failure'] = 0
        elif status.lower() == 'failure':
            fields['success'] = 0
            fields['failure'] = 1
            
        self._write_point('task_metrics', fields, tags)

    def record_ml_model_metrics(self, ticker, model_type, accuracy=None, mse=None, r2_score=None, training_time=None, data_points=None):
        """Record metrics for an ML model

        Args:
            ticker: Stock ticker
            model_type: Type of ML model
            accuracy: Model accuracy score
            mse: Mean Squared Error
            r2_score: R-squared score
            training_time: Time taken for training in ms
            data_points: Number of data points used
        """
        fields = {}
        if accuracy is not None:
            fields['accuracy'] = float(accuracy)
        if mse is not None:
            fields['mse'] = float(mse)
        if r2_score is not None:
            fields['r2_score'] = float(r2_score)
        if training_time is not None:
            fields['training_time'] = float(training_time)
        if data_points is not None:
            fields['data_points'] = int(data_points)
            
        if fields:  # Only write if we have at least one field
            self._write_point('ml_model_metrics', fields, {'ticker': ticker, 'model_type': model_type})

    def record_model_metrics(self, ticker: str, model_type: str, metrics: Dict[str, float]):
        """
        Record ML model performance metrics
        
        Args:
            ticker: Stock ticker symbol
            model_type: Type of ML model (lstm, random_forest, etc)
            metrics: Dictionary of metric names and values
        """
        try:
            self._write_point(
                measurement="model_metrics",
                fields=metrics,
                tags={
                    "ticker": ticker,
                    "model_type": model_type
                }
            )
        except Exception as e:
            logger.error(f"Error recording model metrics: {str(e)}")

    def record_prediction_metrics(self, ticker: str, model_type: str, 
                                prediction_horizon: int, accuracy: float):
        """
        Record prediction performance metrics
        
        Args:
            ticker: Stock ticker symbol
            model_type: Type of ML model used
            prediction_horizon: Number of days in prediction
            accuracy: Accuracy metric (e.g., MAPE or similar)
        """
        try:
            self._write_point(
                measurement="prediction_metrics",
                fields={
                    "accuracy": float(accuracy)
                },
                tags={
                    "ticker": ticker,
                    "model_type": model_type,
                    "horizon": str(prediction_horizon)
                }
            )
        except Exception as e:
            logger.error(f"Error recording prediction metrics: {str(e)}")

    def record_stock_update(self, ticker, source, duration_ms, data_points, error=None):
        """Record metrics for stock data updates

        Args:
            ticker: Stock ticker
            source: Data source
            duration_ms: Time taken for update in ms
            data_points: Number of data points updated
            error: Any error encountered (optional)
        """
        tags = {'ticker': ticker, 'source': source}
        fields = {
            'duration_ms': float(duration_ms),
            'data_points': int(data_points)
        }
                
        if error:
            tags['error'] = str(error)[:255]  # Limit error message length
            fields['has_error'] = 1
        else:
            fields['has_error'] = 0
                
        self._write_point('stock_updates', fields, tags)
        
    def _write_point(self, measurement, fields, tags):
        """Write a data point to InfluxDB
        
        Args:
            measurement: Measurement name
            fields: Dictionary of field names and values
            tags: Dictionary of tag names and values
        """
        try:
            # Add hostname to all tags
            tags_with_host = {**tags, "host": self.hostname}
            
            self.influx.write_point(
                measurement=measurement,
                tags=tags_with_host,
                fields=fields
            )
        except Exception as e:
            logger.error(f"Error writing point to InfluxDB: {str(e)}")


# Create a monitoring middleware for FastAPI
class MonitoringMiddleware:
    """FastAPI middleware for API request monitoring"""
    
    def __init__(self, app):
        self.app = app
        self.monitor = SystemMonitoring.get_instance()
    
    async def __call__(self, scope, receive, send):
        if scope["type"] != "http":
            return await self.app(scope, receive, send)
            
        start_time = time.time()
        
        # Create a wrapper for the send function to capture the status code
        original_send = send
        status_code = 500  # Default value
        
        async def send_wrapper(message):
            nonlocal status_code
            if message["type"] == "http.response.start":
                status_code = message["status"]
            return await original_send(message)
        
        try:
            # Process the request
            await self.app(scope, receive, send_wrapper)
        finally:
            # Record the API call
            duration_ms = (time.time() - start_time) * 1000
            endpoint = scope.get("path", "")
            method = scope.get("method", "")
            
            # Extract user ID if available
            user_id = None  # We would extract from auth context if available
            
            self.monitor.record_api_call(
                endpoint=endpoint,
                method=method,
                status_code=status_code,
                duration_ms=duration_ms,
                user_id=user_id
            )
