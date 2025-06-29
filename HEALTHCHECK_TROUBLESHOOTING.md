# Healthcheck Troubleshooting Guide

## Problem: "Network › Healthcheck" Failure

This is a common issue with Laravel deployments on Railway. Here's how to fix it:

## ✅ What I've Fixed

1. **Added dedicated healthcheck route** (`/health`)
2. **Updated Railway configuration** to use the new route
3. **Increased healthcheck timeout** to 300 seconds
4. **Created startup script** that ensures proper initialization

## 🔧 Quick Fix Steps

### Step 1: Update Your Code
The files I've created will fix the issue:
- `routes/web.php` - Added `/health` route
- `railway.json` - Updated configuration
- `start.sh` - Proper startup script

### Step 2: Push to GitHub
```bash
git add .
git commit -m "Fix healthcheck issues"
git push origin main
```

### Step 3: Check Railway Environment Variables
Ensure these are set in Railway dashboard:
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:3wnQcyVhUFVXYxo2peKYYMtYVePYYRnTC9tllafYMlM=
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite
```

## 🚨 Common Causes & Solutions

### 1. **Missing APP_KEY**
**Error**: 500 Internal Server Error
**Solution**: Set `APP_KEY` in Railway environment variables

### 2. **Database Connection Issues**
**Error**: Database connection failed
**Solution**: 
- Use SQLite: `DB_CONNECTION=sqlite`
- Or ensure PostgreSQL credentials are correct

### 3. **File Permission Issues**
**Error**: Storage directory not writable
**Solution**: The `start.sh` script handles this automatically

### 4. **Application Not Starting**
**Error**: Process not responding
**Solution**: Check Railway logs for startup errors

## 🔍 Debugging Steps

### 1. Check Railway Logs
- Go to Railway dashboard
- Click on your deployment
- Check "View Logs" for errors

### 2. Test Healthcheck Manually
Once deployed, visit: `https://your-app.railway.app/health`
Should return: `{"status":"ok","message":"Event Management System is running"}`

### 3. Check Application Status
Visit: `https://your-app.railway.app/`
Should redirect to events page

## 🛠️ Manual Fix Commands

If you need to run commands manually in Railway terminal:

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerate caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check database
php artisan migrate:status

# Run migrations if needed
php artisan migrate --force
```

## 📋 Environment Variables Checklist

Make sure these are set in Railway:

### Required Variables:
```
APP_NAME="Event Management System"
APP_ENV=production
APP_KEY=base64:3wnQcyVhUFVXYxo2peKYYMtYVePYYRnTC9tllafYMlM=
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
```

### Optional Variables:
```
LOG_LEVEL=error
SESSION_LIFETIME=120
```

## 🎯 Expected Behavior After Fix

1. **Deployment succeeds** without healthcheck errors
2. **Healthcheck passes** within 300 seconds
3. **Application is accessible** at your Railway URL
4. **Database migrations run** automatically
5. **All routes work** properly

## 🆘 Still Having Issues?

If the healthcheck still fails after these fixes:

1. **Check Railway logs** for specific error messages
2. **Verify environment variables** are set correctly
3. **Try redeploying** the application
4. **Contact Railway support** if the issue persists

## 📞 Support Resources

- **Railway Documentation**: https://docs.railway.app
- **Laravel Documentation**: https://laravel.com/docs
- **Railway Discord**: https://discord.gg/railway

## 🎉 Success Indicators

You'll know it's working when:
- ✅ Healthcheck shows "Healthy" in Railway dashboard
- ✅ Your app URL loads without errors
- ✅ You can access `/health` endpoint
- ✅ All application features work normally 