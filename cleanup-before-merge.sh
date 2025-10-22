#!/bin/bash

# Cleanup script for Laravel project before merging to main
# Run this script to clean up temporary files and logs

echo "🧹 Cleaning up temporary files before merge..."

# Remove log files
if [ -f "storage/logs/laravel.log" ]; then
    rm -f storage/logs/laravel.log
    echo "✅ Removed laravel.log"
fi

# Remove any .tmp or .temp files
find . -name "*.tmp" -type f -delete
find . -name "*.temp" -type f -delete
echo "✅ Removed temporary files"

# Remove any .bak files
find . -name "*.bak" -type f -delete
echo "✅ Removed backup files"

# Clear Laravel caches
echo "🔄 Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Remove any test database files
if [ -f "database/database.sqlite" ]; then
    rm -f database/database.sqlite
    echo "✅ Removed test database"
fi

# Remove node_modules if it exists (will be reinstalled)
if [ -d "node_modules" ]; then
    echo "🗑️  Removing node_modules (will be reinstalled)..."
    rm -rf node_modules
fi

# Remove vendor if it exists (will be reinstalled)
if [ -d "vendor" ]; then
    echo "🗑️  Removing vendor (will be reinstalled)..."
    rm -rf vendor
fi

echo "✨ Cleanup complete! Ready for merge."
echo "📝 Don't forget to run 'composer install' and 'npm install' after merge."