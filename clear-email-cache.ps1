# PowerShell script to clear all Laravel caches and ensure email configuration is updated

Write-Host "Clearing Laravel caches to remove email configuration..." -ForegroundColor Green

# Clear configuration cache
Write-Host "Clearing config cache..." -ForegroundColor Yellow
php artisan config:clear

# Clear application cache
Write-Host "Clearing application cache..." -ForegroundColor Yellow
php artisan cache:clear

# Clear route cache
Write-Host "Clearing route cache..." -ForegroundColor Yellow
php artisan route:clear

# Clear view cache
Write-Host "Clearing view cache..." -ForegroundColor Yellow
php artisan view:clear

# Clear compiled services
Write-Host "Clearing compiled services..." -ForegroundColor Yellow
php artisan clear-compiled

# Optimize for production (optional)
Write-Host "Optimizing application..." -ForegroundColor Yellow
php artisan optimize:clear

Write-Host "All caches cleared! Email configuration should now be updated." -ForegroundColor Green
Write-Host ""
Write-Host "Current mail configuration:" -ForegroundColor Cyan
Write-Host "MAIL_MAILER=log (emails will be logged, not sent)" -ForegroundColor Green
Write-Host ""
Write-Host "If you're still getting mail errors, restart your web server." -ForegroundColor Yellow