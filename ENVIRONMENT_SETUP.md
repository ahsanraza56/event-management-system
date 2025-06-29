# Environment Variables Setup for Railway Deployment

## Current .env Issues to Fix

Your current `.env` file has these settings that need to be changed for production:

### ❌ Current (Development) Settings:
```
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### ✅ Production Settings for Railway:

## Option 1: SQLite Database (Recommended - Simplest)

Add these environment variables in Railway dashboard:

```
APP_NAME="Event Management System"
APP_ENV=production
APP_KEY=base64:3wnQcyVhUFVXYxo2peKYYMtYVePYYRnTC9tllafYMlM=
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
MAIL_FROM_ADDRESS=56ahsanraza@gmail.com
MAIL_FROM_NAME="Event Management System"
```

## Option 2: Railway PostgreSQL (More Scalable)

If you want to use Railway's PostgreSQL database:

1. **Add PostgreSQL service** in Railway:
   - Go to your Railway project
   - Click "New Service" → "Database" → "PostgreSQL"
   - Railway will automatically add environment variables

2. **Set these environment variables**:
```
APP_NAME="Event Management System"
APP_ENV=production
APP_KEY=base64:3wnQcyVhUFVXYxo2peKYYMtYVePYYRnTC9tllafYMlM=
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
MAIL_FROM_ADDRESS=56ahsanraza@gmail.com
MAIL_FROM_NAME="Event Management System"
```

## Option 3: Keep Gmail SMTP (For Real Emails)

If you want booking confirmations to send real emails:

```
APP_NAME="Event Management System"
APP_ENV=production
APP_KEY=base64:3wnQcyVhUFVXYxo2peKYYMtYVePYYRnTC9tllafYMlM=
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=56ahsanraza@gmail.com
MAIL_PASSWORD=sshjnpmbdkuzdvtm
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=56ahsanraza@gmail.com
MAIL_FROM_NAME="Event Management System"
```

## How to Set Environment Variables in Railway

1. **Go to Railway Dashboard**
2. **Select your project**
3. **Click on "Variables" tab**
4. **Add each variable** one by one:
   - Click "New Variable"
   - Enter the variable name (e.g., `APP_ENV`)
   - Enter the value (e.g., `production`)
   - Click "Add"

## Security Notes

⚠️ **Important Security Changes:**
- `APP_DEBUG=false` - Hides error details from users
- `APP_ENV=production` - Optimizes for production
- `LOG_LEVEL=error` - Reduces log noise
- `SESSION_DRIVER=file` - Simpler than database sessions
- `CACHE_STORE=file` - Simpler than database cache

## Testing Your Configuration

After setting environment variables:

1. **Redeploy your app** in Railway
2. **Check the logs** for any errors
3. **Test the application** functionality
4. **Verify database connection** works

## Troubleshooting

### Common Issues:

1. **500 Error**: Check if `APP_KEY` is set correctly
2. **Database Error**: Ensure database variables are correct
3. **Mail Error**: Use `MAIL_MAILER=log` for testing
4. **Session Error**: Use `SESSION_DRIVER=file`

### Quick Fix Commands (in Railway terminal):
```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate
```

## Recommended Setup

For your event management system, I recommend:

1. **Start with Option 1 (SQLite)** - Simplest to set up
2. **Use `MAIL_MAILER=log`** initially - Emails will be logged
3. **Upgrade to PostgreSQL** later if you need more scalability
4. **Enable real email** once everything else is working

This setup will give you a fully functional event management system that's easy to deploy and maintain! 