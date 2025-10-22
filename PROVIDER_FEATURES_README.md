# Provider Advanced Features Documentation

## Overview

This implementation provides comprehensive advanced features for healthcare provider management, including filtering, export capabilities, analytics, and bulk operations.

## Features Implemented

### 🔍 Advanced Filtering & Search
- **Real-time search** across provider names, emails, and specialties
- **Multi-criteria filtering** by specialty, status, hourly rate ranges, appointment history
- **Date range filtering** for registration dates
- **Smart suggestions** for filter values
- **Filter persistence** across page navigation
- **Clear filter summary** with active filter badges

### 📊 Export Capabilities
- **Excel Export** (.xlsx) with formatted data and styling
- **PDF Export** with professional layout and statistics
- **CSV Export** for data analysis
- **Filtered exports** - export only filtered results
- **Custom export templates** with company branding

### 📈 Analytics & Reporting
- **Provider statistics dashboard** with key metrics
- **Specialty distribution analysis**
- **Hourly rate analysis** (average, median, ranges)
- **Activity trends** and growth rates
- **Performance metrics** per provider
- **Appointment statistics** and booking rates

### ⚡ Bulk Operations
- **Bulk activate/deactivate** providers
- **Bulk delete** with confirmation
- **Select all/none** functionality
- **Progress indicators** for bulk operations
- **Undo capabilities** for reversible actions

### 🎯 Enhanced User Experience
- **Responsive design** for all screen sizes
- **Loading indicators** for async operations
- **Toast notifications** for user feedback
- **Pagination** with customizable page sizes
- **Status toggle** buttons for quick actions
- **Keyboard shortcuts** for power users

## File Structure

```
app/
├── Http/Controllers/
│   ├── ProviderController.php (Enhanced)
│   └── Api/ProviderApiController.php (New)
├── Services/
│   ├── ProviderAnalyticsService.php (New)
│   ├── ProviderFilterService.php (New)
│   └── ProviderExportService.php (New)
├── Exports/
│   └── ProvidersExport.php (New)
├── Providers/
│   └── ProviderServiceProvider.php (New)
├── Models/
│   └── Provider.php (Enhanced with scopes)
└── Http/Middleware/
    └── ProviderManagementAccess.php (New)

resources/views/
└── admin/providers/
    ├── index-advanced.blade.php (New)
    └── pdf.blade.php (New)

public/js/
└── providers-advanced.js (New)

routes/
├── web.php (Enhanced)
└── api.php (Enhanced)
```

## Installation

### 1. Run the Installation Script
```powershell
.\install-provider-features.ps1
```

### 2. Manual Installation (Alternative)
```bash
# Install required packages
composer require maatwebsite/excel:^3.1
composer require spatie/laravel-query-builder:^5.0

# Publish configurations
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Usage Examples

### Controller Usage
```php
// Basic filtering
$providers = QueryBuilder::for(Provider::class)
    ->allowedFilters(['specialty', 'is_active'])
    ->allowedSorts(['specialty', 'hourly_rate'])
    ->paginate(15);

// Export to Excel
return Excel::download(new ProvidersExport($providers), 'providers.xlsx');

// Get analytics
$analytics = app(ProviderAnalyticsService::class)->getAnalytics();
```

### API Endpoints
```javascript
// Get filtered providers
GET /api/v1/providers?filter[specialty]=cardiology&sort=hourly_rate

// Get analytics
GET /api/v1/providers/analytics

// Bulk operations
POST /api/v1/providers/bulk-action
{
    "action": "activate",
    "provider_ids": [1, 2, 3]
}

// Toggle status
POST /api/v1/providers/1/toggle-status
```

### Frontend JavaScript
```javascript
// Initialize the providers manager
const providersManager = new ProvidersManager();

// Load providers with filters
providersManager.loadProviders();

// Export data
providersManager.handleExport('excel');

// Bulk operations
providersManager.handleBulkAction();
```

## Configuration

### Environment Variables
Add these to your `.env` file:
```env
# Excel export settings
EXCEL_CACHE_DRIVER=file
EXCEL_TEMPORARY_FILES_PATH=storage/framework/laravel-excel

# PDF export settings
DOMPDF_ENABLE_REMOTE=true
DOMPDF_ENABLE_CSS_FLOAT=true
```

### Permissions
The system supports role-based access control:
```php
// In your User model or permission system
public function canManageProviders()
{
    return $this->isAdmin() || $this->hasPermission('manage_providers');
}
```

## API Documentation

### Provider Filtering
The system uses Spatie Query Builder for advanced filtering:

**Available Filters:**
- `filter[specialty]` - Filter by specialty
- `filter[is_active]` - Filter by active status
- `filter[user.name]` - Partial match on provider name
- `filter[user.email]` - Partial match on email
- `filter[hourly_rate_range]` - Rate range (min,max)
- `filter[created_date_range]` - Date range (start,end)
- `filter[has_appointments]` - Providers with/without appointments

**Available Sorts:**
- `sort=specialty` - Sort by specialty
- `sort=hourly_rate` - Sort by hourly rate
- `sort=created_at` - Sort by registration date
- `sort=user.name` - Sort by provider name

### Export Formats
All exports support the same filtering parameters:

```bash
# Export filtered results
GET /providers/export/excel?filter[specialty]=cardiology&filter[is_active]=1

# Export with custom filename
GET /providers/export/pdf?filename=active_cardiologists_2024
```

## Customization

### Adding New Filters
1. Add the filter to the Provider model:
```php
public function scopeCustomFilter(Builder $query, $value)
{
    return $query->where('custom_field', $value);
}
```

2. Register in the controller:
```php
->allowedFilters([
    // existing filters...
    AllowedFilter::scope('custom_filter'),
])
```

### Custom Export Templates
Create new export classes extending the base:
```php
class CustomProvidersExport extends ProvidersExport
{
    public function headings(): array
    {
        return ['Custom', 'Headers', 'Here'];
    }
    
    public function map($provider): array
    {
        return [
            // Custom mapping logic
        ];
    }
}
```

### Adding Analytics Metrics
Extend the ProviderAnalyticsService:
```php
public function getCustomMetrics(): array
{
    return [
        'custom_metric' => Provider::where('custom_condition', true)->count(),
        // Add more custom metrics
    ];
}
```

## Performance Considerations

### Database Optimization
- **Indexes**: Ensure proper indexes on filtered columns
- **Eager Loading**: Use `with()` to prevent N+1 queries
- **Pagination**: Always paginate large datasets
- **Caching**: Cache analytics data for better performance

### Frontend Optimization
- **Debouncing**: Search inputs are debounced (300ms)
- **Lazy Loading**: Large datasets are paginated
- **Caching**: Filter options are cached client-side
- **Compression**: Enable gzip for API responses

## Security Features

### Access Control
- **Middleware protection** for admin routes
- **CSRF protection** for all forms
- **Input validation** for all parameters
- **SQL injection prevention** via Eloquent ORM

### Data Protection
- **Sanitized exports** - sensitive data excluded
- **Audit logging** for bulk operations
- **Rate limiting** on API endpoints
- **Permission checks** for all operations

## Troubleshooting

### Common Issues

1. **Excel export fails**
   ```bash
   # Check storage permissions
   chmod -R 775 storage/
   
   # Clear Excel cache
   php artisan excel:clear-cache
   ```

2. **PDF generation errors**
   ```bash
   # Install required fonts
   sudo apt-get install php-gd
   
   # Check DomPDF config
   php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   ```

3. **Slow filtering performance**
   ```sql
   -- Add database indexes
   CREATE INDEX idx_providers_specialty ON providers(specialty);
   CREATE INDEX idx_providers_is_active ON providers(is_active);
   CREATE INDEX idx_providers_hourly_rate ON providers(hourly_rate);
   ```

### Debug Mode
Enable debug logging in your `.env`:
```env
LOG_LEVEL=debug
APP_DEBUG=true
```

## Support & Maintenance

### Regular Maintenance Tasks
- **Clear caches** weekly: `php artisan cache:clear`
- **Optimize database** monthly: `php artisan db:optimize`
- **Update packages** quarterly: `composer update`
- **Backup exports** regularly for audit trails

### Monitoring
Monitor these metrics:
- Export generation times
- Filter query performance
- API response times
- User engagement with features

## Future Enhancements

### Planned Features
- **Advanced charts** with Chart.js integration
- **Email notifications** for bulk operations
- **Scheduled exports** with cron jobs
- **Provider comparison** tools
- **Mobile app** API endpoints
- **Real-time updates** with WebSockets

### Integration Opportunities
- **CRM systems** for provider management
- **Payment gateways** for billing integration
- **Calendar systems** for appointment scheduling
- **Notification services** for alerts
- **Business intelligence** tools for advanced analytics

---

For technical support or feature requests, please contact the development team or create an issue in the project repository.