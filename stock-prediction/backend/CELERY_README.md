# Celery Integration for Zahaam Stock Market Prediction

This document explains how to use the Celery integration for asynchronous task processing in the Zahaam Stock Market Prediction application.

## Overview

The system utilizes:

- **Celery**: Distributed task queue for handling asynchronous tasks
- **RabbitMQ**: Message broker for task distribution
- **Redis**: Result backend and cache store
- **Flower**: Web-based monitoring for Celery tasks

## Components

1. **Task Modules**:
   - `ml_tasks.py`: Machine learning tasks (model training, predictions)
   - `stock_tasks.py`: Stock data management tasks (updates, bulk operations)
   - `maintenance_tasks.py`: System maintenance tasks (cache cleaning, database optimization)

2. **API Integration**:
   - `tasks_api.py`: REST endpoints for task management

3. **Worker Types**:
   - ML workers: Handle ML model training and prediction tasks
   - Stock workers: Handle stock data operations
   - Maintenance workers: Handle system optimization tasks

## Getting Started

### Option 1: Running with Docker Compose

The easiest way to run the entire system:

```bash
# Start all services
docker-compose up -d

# Check logs
docker-compose logs -f celery_worker_ml  # ML worker logs
docker-compose logs -f celery_worker_stocks  # Stock worker logs
docker-compose logs -f celery_beat  # Scheduler logs

# Access Flower monitoring
open http://localhost:5555
```

### Option 2: Running Components Separately

If you prefer to run components individually:

1. **Start RabbitMQ**:
   ```bash
   # If installed via Homebrew
   brew services start rabbitmq
   # Or using Docker
   docker run -d -p 5672:5672 -p 15672:15672 rabbitmq:3.11-management
   ```

2. **Start Redis**:
   ```bash
   # If installed via Homebrew
   brew services start redis
   # Or using Docker
   docker run -d -p 6379:6379 redis:7-alpine
   ```

3. **Start Celery Workers**:
   ```bash
   # ML worker
   celery -A app.core.celery_app worker -Q ml_queue -n ml_worker@%h -l info

   # Stock worker
   celery -A app.core.celery_app worker -Q stock_queue -n stock_worker@%h -l info

   # Maintenance worker
   celery -A app.core.celery_app worker -Q maintenance_queue -n maintenance_worker@%h -l info
   ```

4. **Start Celery Beat** (scheduler):
   ```bash
   celery -A app.core.celery_app beat -l info
   ```

5. **Start Flower** (monitoring):
   ```bash
   celery -A app.core.celery_app flower --port=5555
   ```

## API Usage Examples

### Trigger Stock Data Update

```bash
curl -X POST "http://localhost:5005/api/tasks/stock-update/AAPL" \
     -H "Authorization: Bearer YOUR_TOKEN"
```

### Trigger Model Training

```bash
curl -X POST "http://localhost:5005/api/tasks/train-model/AAPL" \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"model_type": "lstm", "force": true}'
```

### Check Task Status

```bash
curl -X GET "http://localhost:5005/api/tasks/status/YOUR_TASK_ID" \
     -H "Authorization: Bearer YOUR_TOKEN"
```

## Environment Variables

Configure the system with these environment variables:

- `RABBITMQ_URL`: RabbitMQ connection URL (default: `amqp://guest:guest@localhost:5672//`)
- `REDIS_URL`: Redis connection URL (default: `redis://localhost:6379/0`)

## Scheduled Tasks

The following tasks are scheduled automatically:

| Task | Schedule | Description |
|------|----------|-------------|
| Update active stocks | Daily 5:00 AM | Updates price data for all active stocks |
| Train active models | Weekly Sunday 2:00 AM | Re-trains models for all active stocks |
| Clear expired cache | Hourly | Removes expired cache entries |
| Database optimization | Daily 3:00 AM | Analyzes and optimizes database tables |
| System stats | Daily 6:00 AM | Collects and reports system statistics |

## Monitoring

Access Flower monitoring UI at: http://localhost:5555

Features:
- View active/completed/failed tasks
- Monitor worker status
- View task execution time
- Revoke or terminate tasks

## Architecture Diagram

```
┌────────────────┐       ┌────────────────┐       ┌────────────────┐
│                │       │                │       │                │
│  FastAPI       │───────│    RabbitMQ    │───────│  Celery        │
│  Application   │ tasks │  Message Broker│ tasks │  Workers       │
│                │───────│                │───────│                │
└────────────────┘       └────────────────┘       └────────────────┘
        │                                                 │
        │                                                 │
        │                      ┌────────────────┐         │
        │                      │                │         │
        └──────────────────────│     Redis      │─────────┘
                 results       │  Result Store  │ results
                               │                │
                               └────────────────┘
```

## Troubleshooting

Common issues and solutions:

1. **Workers not processing tasks**
   - Check RabbitMQ connection
   - Verify worker queues are correctly specified

2. **Task results not showing**
   - Check Redis connection
   - Verify correct Redis database is configured

3. **Scheduled tasks not running**
   - Verify Celery Beat is running
   - Check logs for errors in task scheduling
