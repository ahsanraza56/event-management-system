#!/bin/bash

# Laravel Event Management System - Startup Script for Railway
echo "🚀 Starting Event Management System..."

# Wait for database to be ready (if using external database)
if [ "$DB_CONNECTION" = "pgsql" ] || [ "$DB_CONNECTION" = "mysql" ]; then
    echo "⏳ Waiting for database connection..."
    sleep 5
fi

# Create necessary directories
echo "📁 Creating necessary directories..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Clear and cache configuration
echo "⚙️ Optimizing configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if needed
echo "🗄️ Checking database migrations..."
php artisan migrate --force

# Start the application
echo "🌐 Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=$PORT 