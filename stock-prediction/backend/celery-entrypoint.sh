#!/bin/bash
set -e

# Wait for RabbitMQ to be ready
until nc -z -v -w30 rabbitmq 5672; do
  echo "Waiting for RabbitMQ connection..."
  sleep 2
done
echo "RabbitMQ is up and running!"

# Wait for Redis to be ready
until nc -z -v -w30 redis 6379; do
  echo "Waiting for Redis connection..."
  sleep 2
done
echo "Redis is up and running!"

# Execute command
exec "$@"
