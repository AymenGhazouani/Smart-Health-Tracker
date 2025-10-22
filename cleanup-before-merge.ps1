# Cleanup script for Laravel project before merging to main
# Run this script to clean up temporary files and logs

Write-Host "🧹 Cleaning up temporary files before merge..." -ForegroundColor Green

# Remove log files
if (Test-Path "storage/logs/laravel.log") {
    Remove-Item "storage/logs/laravel.log" -Force
    Write-Host "✅ Removed laravel.log" -ForegroundColor Yellow
}

# Remove any .tmp or .temp files
Get-ChildItem -Recurse -Include "*.tmp", "*.temp" | Remove-Item -Force
Write-Host "✅ Removed temporary files" -ForegroundColor Yellow

# Remove any .bak files
Get-ChildItem -Recurse -Include "*.bak" | Remove-Item -Force
Write-Host "✅ Removed backup files" -ForegroundColor Yellow

# Clear Laravel caches
Write-Host "🔄 Clearing Laravel caches..." -ForegroundColor Blue
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Remove any test database files
if (Test-Path "database/database.sqlite") {
    Remove-Item "database/database.sqlite" -Force
    Write-Host "✅ Removed test database" -ForegroundColor Yellow
}

# Remove node_modules if it exists (will be reinstalled)
if (Test-Path "node_modules") {
    Write-Host "🗑️  Removing node_modules (will be reinstalled)..." -ForegroundColor Yellow
    Remove-Item "node_modules" -Recurse -Force
}

# Remove vendor if it exists (will be reinstalled)
if (Test-Path "vendor") {
    Write-Host "🗑️  Removing vendor (will be reinstalled)..." -ForegroundColor Yellow
    Remove-Item "vendor" -Recurse -Force
}

Write-Host "✨ Cleanup complete! Ready for merge." -ForegroundColor Green
Write-Host "📝 Don't forget to run 'composer install' and 'npm install' after merge." -ForegroundColor Cyan