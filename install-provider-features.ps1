# PowerShell script to install provider advanced features

Write-Host "Installing Provider Advanced Features..." -ForegroundColor Green

# Install required Composer packages
Write-Host "Installing Composer packages..." -ForegroundColor Yellow
composer require maatwebsite/excel:^3.1
composer require spatie/laravel-query-builder:^5.0

# Publish Excel config
Write-Host "Publishing Excel configuration..." -ForegroundColor Yellow
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config

# Clear caches
Write-Host "Clearing application caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Run migrations if needed
Write-Host "Running migrations..." -ForegroundColor Yellow
php artisan migrate

Write-Host "Provider Advanced Features installation completed!" -ForegroundColor Green
Write-Host ""
Write-Host "Features installed:" -ForegroundColor Cyan
Write-Host "✓ Advanced filtering with QueryBuilder" -ForegroundColor Green
Write-Host "✓ Excel/PDF/CSV export functionality" -ForegroundColor Green
Write-Host "✓ Provider analytics service" -ForegroundColor Green
Write-Host "✓ Bulk operations (activate/deactivate/delete)" -ForegroundColor Green
Write-Host "✓ Real-time search and filtering" -ForegroundColor Green
Write-Host "✓ Provider performance metrics" -ForegroundColor Green
Write-Host "✓ Advanced API endpoints" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Update your provider views to use the new advanced template"
Write-Host "2. Configure any additional permissions as needed"
Write-Host "3. Test the new features in your application"