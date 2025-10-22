<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProviderAnalyticsService;
use App\Services\ProviderFilterService;
use App\Services\ProviderExportService;

class ProviderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ProviderAnalyticsService::class);
        $this->app->singleton(ProviderFilterService::class);
        $this->app->singleton(ProviderExportService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}