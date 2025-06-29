# Windows Deployment Guide for Laravel Event Management System

## Quick Start (Recommended: Railway)

### Step 1: Prepare Your Project

1. **Open Command Prompt or PowerShell** in your project directory
2. **Install dependencies** (if not already done):
   ```cmd
   composer install --no-dev --optimize-autoloader
   ```

3. **Generate application key**:
   ```cmd
   php artisan key:generate
   ```

4. **Optimize for production**:
   ```cmd
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Step 2: Push to GitHub

1. **Initialize Git** (if not already done):
   ```cmd
   git init
   git add .
   git commit -m "Initial commit"
   ```

2. **Create GitHub repository**:
   - Go to [github.com](https://github.com)
   - Click "New repository"
   - Name it "event-management-system"
   - Don't initialize with README (you already have one)

3. **Push to GitHub**:
   ```cmd
   git remote add origin https://github.com/YOUR_USERNAME/event-management-system.git
   git branch -M main
   git push -u origin main
   ```

### Step 3: Deploy to Railway

1. **Sign up for Railway**:
   - Go to [railway.app](https://railway.app)
   - Sign in with GitHub

2. **Create new project**:
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose your "event-management-system" repository
   - Railway will automatically detect Laravel and start deploying

3. **Configure environment variables**:
   - In Railway dashboard, go to your project
   - Click on "Variables" tab
   - Add these variables:
   ```
   APP_NAME="Event Management System"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app-name.railway.app
   
   DB_CONNECTION=sqlite
   DB_DATABASE=/app/database/database.sqlite
   
   CACHE_DRIVER=file
   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
   
   MAIL_MAILER=log
   ```

4. **Deploy and test**:
   - Railway will automatically deploy your app
   - Click on the generated URL to test
   - Your app should be live!

### Step 4: Set Up Database

1. **Open Railway terminal**:
   - In Railway dashboard, click on your project
   - Go to "Deployments" tab
   - Click on the latest deployment
   - Click "View Logs" and then "Open Shell"

2. **Run migrations**:
   ```bash
   php artisan migrate
   php artisan db:seed
   php artisan app:generate-event-seats
   ```

3. **Create admin user**:
   ```bash
   php artisan make:admin
   ```

## Alternative: Render Deployment

### Step 1: Deploy to Render

1. **Go to Render**:
   - Visit [render.com](https://render.com)
   - Sign up with GitHub

2. **Create Web Service**:
   - Click "New +"
   - Select "Web Service"
   - Connect your GitHub repository
   - Choose "event-management-system"

3. **Configure service**:
   - **Name**: event-management-system
   - **Environment**: PHP
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

4. **Add environment variables** (same as Railway)

5. **Deploy**:
   - Click "Create Web Service"
   - Render will build and deploy your app

## Troubleshooting

### Common Issues on Windows

1. **Composer not found**:
   - Download Composer from [getcomposer.org](https://getcomposer.org)
   - Install it globally

2. **PHP not in PATH**:
   - Add PHP to your system PATH
   - Or use XAMPP/WAMP which includes PHP

3. **Git not found**:
   - Download Git from [git-scm.com](https://git-scm.com)
   - Install with default settings

4. **Permission issues**:
   - Run Command Prompt as Administrator
   - Or use PowerShell

### Deployment Issues

1. **500 Error**:
   - Check if APP_KEY is set in environment variables
   - Ensure all required extensions are enabled

2. **Database connection failed**:
   - Verify database credentials
   - Check if database service is running

3. **File not found errors**:
   - Ensure all files are committed to Git
   - Check if .gitignore is excluding important files

## Cost Comparison

| Platform | Free Tier | Paid Plans | Best For |
|----------|-----------|------------|----------|
| Railway | $5/month credit | $20+/month | Best overall |
| Render | Free (with limits) | $7+/month | Good alternative |
| Heroku | Discontinued | $7+/month | Traditional choice |
| Vercel | Free | $20+/month | Frontend only |

## Next Steps

1. **Test your deployed application**
2. **Set up a custom domain** (optional)
3. **Configure email settings** for booking confirmations
4. **Set up monitoring** and logging
5. **Create backup strategies**

## Support Resources

- **Railway Docs**: https://docs.railway.app
- **Laravel Docs**: https://laravel.com/docs
- **Render Docs**: https://render.com/docs
- **GitHub**: https://github.com

## Quick Commands Reference

```cmd
# Local development
php artisan serve

# Production preparation
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database
php artisan migrate
php artisan db:seed
php artisan app:generate-event-seats

# Git
git add .
git commit -m "Your message"
git push origin main
``` 