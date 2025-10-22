<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Services\ProviderAnalyticsService;
use App\Services\ProviderFilterService;
use App\Services\ProviderExportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProviderApiController extends Controller
{
    protected $analyticsService;
    protected $filterService;
    protected $exportService;

    public function __construct(
        ProviderAnalyticsService $analyticsService,
        ProviderFilterService $filterService,
        ProviderExportService $exportService
    ) {
        $this->analyticsService = $analyticsService;
        $this->filterService = $filterService;
        $this->exportService = $exportService;
    }

    /**
     * Get filtered providers with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $query = Provider::with(['user', 'appointments', 'availabilitySlots']);
        
        $filteredQuery = $this->filterService->applyFilters($query, $request);
        
        $providers = $filteredQuery->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $providers,
            'filters' => $this->filterService->getFilterOptions(),
            'filter_summary' => $this->filterService->getFilterSummary($request),
        ]);
    }

    /**
     * Get provider analytics
     */
    public function analytics(): JsonResponse
    {
        $analytics = $this->analyticsService->getAnalytics();
        
        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get specific provider details
     */
    public function show(Provider $provider): JsonResponse
    {
        $provider->load(['user', 'appointments', 'availabilitySlots']);
        
        $performance = $this->analyticsService->getProviderPerformanceReport($provider);
        
        return response()->json([
            'success' => true,
            'data' => [
                'provider' => $provider,
                'performance' => $performance
            ]
        ]);
    }

    /**
     * Search providers
     */
    public function search(Request $request): JsonResponse
    {
        $query = Provider::with(['user', 'appointments']);
        
        $providers = $this->filterService->applyFilters($query, $request)
            ->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $providers
        ]);
    }

    /**
     * Toggle provider status
     */
    public function toggleStatus(Provider $provider): JsonResponse
    {
        $provider->update(['is_active' => !$provider->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => $provider->is_active ? 'Provider activated' : 'Provider deactivated',
            'status' => $provider->is_active
        ]);
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'provider_ids' => 'required|array',
            'provider_ids.*' => 'exists:providers,id'
        ]);

        $providers = Provider::whereIn('id', $request->provider_ids);
        $count = $providers->count();

        switch ($request->action) {
            case 'activate':
                $providers->update(['is_active' => true]);
                $message = "{$count} providers activated successfully.";
                break;
            case 'deactivate':
                $providers->update(['is_active' => false]);
                $message = "{$count} providers deactivated successfully.";
                break;
            case 'delete':
                $providers->delete();
                $message = "{$count} providers deleted successfully.";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'affected_count' => $count
        ]);
    }

    /**
     * Get export formats
     */
    public function exportFormats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->exportService->getExportFormats()
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function dashboardStats(): JsonResponse
    {
        $stats = [
            'total_providers' => Provider::count(),
            'active_providers' => Provider::where('is_active', true)->count(),
            'inactive_providers' => Provider::where('is_active', false)->count(),
            'providers_with_appointments' => Provider::has('appointments')->count(),
            'recent_registrations' => Provider::where('created_at', '>=', now()->subDays(30))->count(),
            'top_specialties' => Provider::select('specialty')
                ->selectRaw('count(*) as count')
                ->groupBy('specialty')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get filter suggestions
     */
    public function filterSuggestions(Request $request): JsonResponse
    {
        $type = $request->get('type');
        $query = $request->get('query', '');

        $suggestions = [];

        switch ($type) {
            case 'specialty':
                $suggestions = Provider::where('specialty', 'like', "%{$query}%")
                    ->distinct()
                    ->pluck('specialty')
                    ->take(10);
                break;
            case 'provider_name':
                $suggestions = Provider::whereHas('user', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->with('user:id,name')
                ->take(10)
                ->get()
                ->pluck('user.name');
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }
}