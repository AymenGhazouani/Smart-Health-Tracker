<?php

namespace App\Services;

use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProviderAnalyticsService
{
    /**
     * Get comprehensive provider analytics
     */
    public function getAnalytics(): array
    {
        return [
            'overview' => $this->getOverviewStats(),
            'specialty_distribution' => $this->getSpecialtyDistribution(),
            'rate_analysis' => $this->getRateAnalysis(),
            'activity_trends' => $this->getActivityTrends(),
            'performance_metrics' => $this->getPerformanceMetrics(),
        ];
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats(): array
    {
        return [
            'total_providers' => Provider::count(),
            'active_providers' => Provider::where('is_active', true)->count(),
            'inactive_providers' => Provider::where('is_active', false)->count(),
            'providers_with_appointments' => Provider::has('appointments')->count(),
            'providers_without_appointments' => Provider::doesntHave('appointments')->count(),
        ];
    }

    /**
     * Get specialty distribution
     */
    private function getSpecialtyDistribution(): array
    {
        return Provider::select('specialty', DB::raw('count(*) as count'))
            ->groupBy('specialty')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get rate analysis
     */
    private function getRateAnalysis(): array
    {
        $rates = Provider::whereNotNull('hourly_rate')->pluck('hourly_rate');
        
        return [
            'average_rate' => $rates->avg(),
            'median_rate' => $rates->median(),
            'min_rate' => $rates->min(),
            'max_rate' => $rates->max(),
            'rate_ranges' => [
                'under_50' => Provider::where('hourly_rate', '<', 50)->count(),
                '50_to_100' => Provider::whereBetween('hourly_rate', [50, 100])->count(),
                '100_to_150' => Provider::whereBetween('hourly_rate', [100, 150])->count(),
                'over_150' => Provider::where('hourly_rate', '>', 150)->count(),
            ]
        ];
    }

    /**
     * Get activity trends
     */
    private function getActivityTrends(): array
    {
        $monthlyData = Provider::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as registrations')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return [
            'monthly_registrations' => $monthlyData,
            'growth_rate' => $this->calculateGrowthRate($monthlyData),
        ];
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(): array
    {
        return [
            'top_providers_by_appointments' => Provider::withCount('appointments')
                ->orderBy('appointments_count', 'desc')
                ->limit(10)
                ->get(),
            'providers_by_availability' => Provider::withCount('availabilitySlots')
                ->orderBy('availability_slots_count', 'desc')
                ->limit(10)
                ->get(),
        ];
    }

    /**
     * Calculate growth rate
     */
    private function calculateGrowthRate($monthlyData): float
    {
        if ($monthlyData->count() < 2) {
            return 0;
        }

        $current = $monthlyData->first()->registrations;
        $previous = $monthlyData->skip(1)->first()->registrations;

        if ($previous == 0) {
            return 0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    /**
     * Get provider performance report
     */
    public function getProviderPerformanceReport(Provider $provider): array
    {
        return [
            'appointment_stats' => [
                'total_appointments' => $provider->appointments()->count(),
                'completed_appointments' => $provider->appointments()->where('status', 'completed')->count(),
                'cancelled_appointments' => $provider->appointments()->where('status', 'cancelled')->count(),
            ],
            'availability_stats' => [
                'total_slots' => $provider->availabilitySlots()->count(),
                'booked_slots' => $provider->availabilitySlots()->whereHas('appointments')->count(),
            ],
            'rating_stats' => [
                // Assuming you have a rating system
                'average_rating' => 0, // Implement based on your rating system
                'total_reviews' => 0,
            ]
        ];
    }
}