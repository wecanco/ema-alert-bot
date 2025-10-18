#!/bin/bash
set -e

echo "Starting EMA Alert Bot..."

# Wait for database to be ready if using external database
if [ "$DB_CONNECTION" != "sqlite" ]; then
    echo "Waiting for database connection..."
    sleep 5
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Seed database if needed
if [ "$DB_SEED" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force --no-interaction
fi

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application ready!"

# Execute the main command
exec "$@"
