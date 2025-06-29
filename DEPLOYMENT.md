# Laravel Event Management System - Deployment Guide

## Free Deployment Options

### 1. Railway (Recommended)
Railway offers the best free tier for Laravel applications with $5 monthly credit.

#### Prerequisites
- GitHub account
- Railway account (sign up at railway.app)

#### Step-by-Step Deployment

1. **Prepare Your Repository**
   ```bash
   # Ensure your code is committed to GitHub
   git add .
   git commit -m "Prepare for deployment"
   git push origin main
   ```

2. **Deploy to Railway**
   - Go to [railway.app](https://railway.app)
   - Sign in with GitHub
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose your repository
   - Railway will automatically detect Laravel and deploy

3. **Configure Environment Variables**
   In Railway dashboard, add these environment variables:
   ```
   APP_NAME="Event Management System"
   APP_ENV=production
   APP_KEY=base64:your-generated-key
   APP_DEBUG=false
   APP_URL=https://your-app-name.railway.app
   
   DB_CONNECTION=sqlite
   DB_DATABASE=/app/database/database.sqlite
   
   CACHE_DRIVER=file
   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
   
   MAIL_MAILER=log
   ```

4. **Generate Application Key**
   ```bash
   # In Railway terminal or locally
   php artisan key:generate
   ```

5. **Run Migrations**
   ```bash
   # In Railway terminal
   php artisan migrate
   php artisan db:seed
   ```

6. **Set Up Custom Domain (Optional)**
   - In Railway dashboard, go to Settings
   - Add your custom domain
   - Update DNS records

### 2. Render (Alternative)
Render offers a free tier with some limitations.

#### Deployment Steps
1. Go to [render.com](https://render.com)
2. Connect your GitHub repository
3. Create a new Web Service
4. Configure build command: `composer install --no-dev --optimize-autoloader`
5. Configure start command: `php artisan serve --host=0.0.0.0 --port=$PORT`
6. Add environment variables similar to Railway

### 3. Heroku (Paid but Affordable)
- Free tier discontinued, but still very affordable
- Excellent Laravel support
- Easy deployment process

## Database Options

### Free Database Services
1. **Railway PostgreSQL** (included with Railway)
2. **PlanetScale** (free MySQL)
3. **Supabase** (free PostgreSQL)
4. **Neon** (free PostgreSQL)

### Using SQLite (Simplest)
Your project is already configured for SQLite, which works great for small to medium applications.

## Environment Configuration

### Production Environment Variables
```env
APP_NAME="Event Management System"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
LOG_CHANNEL=stack
LOG_LEVEL=error
```

## Post-Deployment Steps

1. **Test Your Application**
   - Visit your deployed URL
   - Test user registration/login
   - Test event creation and booking
   - Test seat selection

2. **Set Up Admin User**
   ```bash
   php artisan make:admin
   # Or manually create admin user through registration
   ```

3. **Generate Sample Data**
   ```bash
   php artisan db:seed
   php artisan app:generate-event-seats
   ```

4. **Monitor Logs**
   - Check Railway/Render logs for any errors
   - Monitor application performance

## Troubleshooting

### Common Issues
1. **500 Error**: Check APP_KEY is set
2. **Database Connection**: Ensure database credentials are correct
3. **File Permissions**: Ensure storage and bootstrap/cache are writable
4. **Memory Issues**: Optimize with `composer install --no-dev --optimize-autoloader`

### Performance Optimization
1. Enable caching: `php artisan config:cache`
2. Optimize autoloader: `composer install --optimize-autoloader --no-dev`
3. Use production environment: `APP_ENV=production`

## Security Considerations

1. **Environment Variables**: Never commit .env files
2. **Debug Mode**: Set `APP_DEBUG=false` in production
3. **HTTPS**: Most platforms provide automatic HTTPS
4. **Database**: Use strong passwords and secure connections

## Cost Optimization

1. **Railway**: $5/month credit (usually sufficient for small apps)
2. **Render**: Free tier with limitations
3. **Database**: Use free tiers from PlanetScale, Supabase, or Neon

## Support

- Railway Documentation: https://docs.railway.app
- Laravel Documentation: https://laravel.com/docs
- Render Documentation: https://render.com/docs 