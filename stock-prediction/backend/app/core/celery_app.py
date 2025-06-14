"""
Celery configuration for Zahaam application
"""
from celery import Celery, Task, signals
from time import time
from celery.schedules import crontab
import os
import logging

logger = logging.getLogger(__name__)

# Optional import for metrics - will be handled gracefully if missing
try:
    from app.utils.monitoring import SystemMonitoring
    monitoring_available = True
except ImportError:
    monitoring_available = False
    logging.warning("Monitoring module not available - task metrics will not be recorded")

# Setup Celery instance
celery_app = Celery(
    "zahaam",
    broker=os.getenv("RABBITMQ_URL", "amqp://guest:guest@localhost:5672//"),
    backend=os.getenv("REDIS_URL", "redis://localhost:6379/0"),
    include=[
        "app.tasks.ml_tasks",
        "app.tasks.stock_tasks",
        "app.tasks.maintenance_tasks"
    ]
)

# Optional configuration
celery_app.conf.update(
    # Set serializer
    accept_content=["json"],
    task_serializer="json",
    result_serializer="json",
    
    # Task execution settings
    task_acks_late=True,  # Tasks are acknowledged after execution
    worker_prefetch_multiplier=1,  # Don't prefetch tasks (for long-running tasks)
    
    # Result settings
    task_ignore_result=False,  # Store task results
    result_expires=60 * 60 * 24,  # Results expire after 24 hours
    
    # Task routing
    task_routes={
        "app.tasks.ml_tasks.*": {"queue": "ml_queue"},
        "app.tasks.stock_tasks.*": {"queue": "stock_queue"},
        "app.tasks.maintenance_tasks.*": {"queue": "maintenance_queue"}
    },
    
    # Retry settings
    task_default_retry_delay=60,  # 1 minute delay before retrying
    task_max_retries=3,  # Maximum 3 retries
    
    # Periodic tasks (replaces our scheduler)
    beat_schedule={
        "update-stocks-daily": {
            "task": "app.tasks.stock_tasks.update_active_stocks",
            "schedule": crontab(hour=16, minute=30),  # 16:30 every day
            "options": {"queue": "stock_queue"}
        },
        "clear-expired-cache": {
            "task": "app.tasks.maintenance_tasks.clear_expired_cache",
            "schedule": crontab(minute="*/30"),  # Every 30 minutes
            "options": {"queue": "maintenance_queue"}
        },
        "optimize-database": {
            "task": "app.tasks.maintenance_tasks.optimize_database",
            "schedule": crontab(hour=1, minute=0),  # 01:00 AM
            "options": {"queue": "maintenance_queue"}
        },
        "train-ml-models": {
            "task": "app.tasks.ml_tasks.train_ml_models",
            "schedule": crontab(day_of_week=0, hour=2, minute=0),  # Sunday at 02:00 AM
            "options": {"queue": "ml_queue"}
        },
        "monitor-model-performance": {
            "task": "app.tasks.ml_tasks.monitor_model_performance",
            "schedule": crontab(hour=3, minute=0),  # Daily at 03:00 AM
            "options": {"queue": "ml_queue"}
        }
    }
)

# This allows calls to app.worker_init to work correctly
@celery_app.on_after_configure.connect
def setup_periodic_tasks(sender, **kwargs):
    """Setup any additional periodic tasks"""
    logger.info("Celery periodic tasks have been initialized")

@signals.setup_logging.connect
def setup_celery_logging(**_):
    """Configure Celery logging"""
    return True

@signals.task_prerun.connect
def task_prerun(task_id, task, *args, **kwargs):
    """Store task start time for duration calculation"""
    task.start_time = time()

@signals.task_postrun.connect
def task_postrun(task_id, task, retval, state, *args, **kwargs):
    """Record task metrics in InfluxDB"""
    if not hasattr(task, 'start_time'):
        return

    # Calculate task duration
    duration_ms = (time() - task.start_time) * 1000

    # Record metrics if monitoring is available
    if monitoring_available:
        try:
            # Get queue name from task
            queue = getattr(task.request, 'delivery_info', {}).get('routing_key', 'default')
            
            monitor = SystemMonitoring.get_instance()
            monitor.record_task_execution(
                task_name=task.name,
                task_id=task_id,
                status=state,
                duration_ms=duration_ms,
                queue=queue
            )
        except Exception as e:
            logging.error(f"Error recording task metrics: {str(e)}")

def get_task_info(task_id):
    """Get information about a specific task"""
    return celery_app.AsyncResult(task_id)


if __name__ == "__main__":
    celery_app.start()
