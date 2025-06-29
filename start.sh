#!/bin/bash

# Laravel Event Management System - Startup Script for Railway
echo "🚀 Starting Event Management System..."

# Exit on any error
set -e

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
mkdir -p database

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create SQLite database file if it doesn't exist
if [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "🗄️ Setting up SQLite database..."
    touch database/database.sqlite
    chmod 664 database/database.sqlite
fi

# Clear all caches first
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Cache configuration
echo "⚙️ Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check database connection
echo "🔍 Checking database connection..."
php artisan app:check-database

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Update existing bookings with total amounts
echo "💰 Updating booking totals..."
php artisan app:update-booking-totals

# Seed database if needed (only if it's empty)
echo "🌱 Checking if database needs seeding..."
if ! php artisan tinker --execute='echo App\Models\Event::count();' 2>/dev/null | grep -q "[1-9]"; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force || true
    php artisan app:generate-event-seats || true
else
    echo "✅ Database already has data"
fi

# Final health check
echo "🏥 Performing final health check..."
curl -f http://localhost:$PORT/health || echo "Health check failed, but continuing..."

# Start the application
echo "🌐 Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT 