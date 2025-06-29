#!/bin/bash

# Laravel Event Management System - Deployment Script
# This script prepares your Laravel application for deployment

echo "🚀 Preparing Laravel Event Management System for deployment..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from your Laravel project root."
    exit 1
fi

# Create necessary directories if they don't exist
echo "📁 Creating necessary directories..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key if not exists
if [ -z "$(grep 'APP_KEY=base64:' .env 2>/dev/null)" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Clear and cache configuration
echo "⚙️ Optimizing configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed database if needed
read -p "Do you want to seed the database with sample data? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force
    php artisan app:generate-event-seats
fi

# Create admin user if needed
read -p "Do you want to create an admin user? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "👤 Creating admin user..."
    php artisan make:admin
fi

echo "✅ Deployment preparation completed!"
echo ""
echo "📋 Next steps:"
echo "1. Push your code to GitHub:"
echo "   git add ."
echo "   git commit -m 'Prepare for deployment'"
echo "   git push origin main"
echo ""
echo "2. Deploy to Railway:"
echo "   - Go to https://railway.app"
echo "   - Connect your GitHub repository"
echo "   - Railway will automatically deploy your app"
echo ""
echo "3. Configure environment variables in Railway dashboard"
echo "4. Test your deployed application"
echo ""
echo "📖 For detailed instructions, see DEPLOYMENT.md" 