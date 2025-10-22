<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProvidersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $providers = QueryBuilder::for(Provider::class)
            ->with(['user', 'appointments', 'availabilitySlots'])
            ->allowedFilters([
                'specialty',
                'is_active',
                AllowedFilter::partial('user.name'),
                AllowedFilter::partial('user.email'),
                AllowedFilter::scope('hourly_rate_range'),
                AllowedFilter::scope('created_date_range'),
                AllowedFilter::scope('has_appointments'),
            ])
            ->allowedSorts(['specialty', 'hourly_rate', 'created_at', 'user.name'])
            ->defaultSort('-created_at')
            ->paginate($request->get('per_page', 15))
            ->withQueryString();

        $specialties = Provider::distinct()->pluck('specialty')->filter();
        $stats = $this->getProviderStats();

        if ($request->ajax()) {
            return response()->json([
                'providers' => $providers,
                'stats' => $stats
            ]);
        }

        return view('admin.providers.index', compact('providers', 'specialties', 'stats'));
    }

    public function create()
    {
        $users = User::where('role', '=', 'admin')
            ->orWhereNull('role')
            ->get();
        return view('admin.providers.create', compact('users'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('providers', 'public');
        }

        Provider::create($validated);

        return redirect()->route('providers.index')
            ->with('success', 'Provider created successfully.');
    }

    public function show(Provider $provider)
    {
        return view('admin.providers.show', compact('provider'));
    }

    public function edit(Provider $provider)
    {
        $users = User::where('role', '!=', 'admin')
            ->orWhereNull('role')
            ->get();
        return view('admin.providers.edit', compact('provider', 'users'));
    }

    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'hourly_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('providers', 'public');
        }

        $provider->update($validated);

        return redirect()->route('providers.index')
            ->with('success', 'Provider updated successfully.');
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('providers.index')
            ->with('success', 'Provider deleted successfully.');
    }

    /**
     * Export providers to Excel
     */
    public function exportExcel(Request $request)
    {
        $providers = $this->getFilteredProviders($request);
        
        return Excel::download(
            new ProvidersExport($providers), 
            'providers_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * Export providers to PDF
     */
    public function exportPdf(Request $request)
    {
        $providers = $this->getFilteredProviders($request);
        $stats = $this->getProviderStats();
        
        $pdf = Pdf::loadView('admin.providers.pdf', compact('providers', 'stats'));
        
        return $pdf->download('providers_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Bulk actions for providers
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'provider_ids' => 'required|array',
            'provider_ids.*' => 'exists:providers,id'
        ]);

        $providers = Provider::whereIn('id', $request->provider_ids);

        switch ($request->action) {
            case 'activate':
                $providers->update(['is_active' => true]);
                $message = 'Providers activated successfully.';
                break;
            case 'deactivate':
                $providers->update(['is_active' => false]);
                $message = 'Providers deactivated successfully.';
                break;
            case 'delete':
                $providers->delete();
                $message = 'Providers deleted successfully.';
                break;
        }

        return response()->json(['message' => $message]);
    }

    /**
     * Get provider analytics
     */
    public function analytics()
    {
        $analytics = [
            'total_providers' => Provider::count(),
            'active_providers' => Provider::where('is_active', true)->count(),
            'providers_by_specialty' => Provider::select('specialty', DB::raw('count(*) as count'))
                ->groupBy('specialty')
                ->get(),
            'average_hourly_rate' => Provider::whereNotNull('hourly_rate')->avg('hourly_rate'),
            'providers_with_appointments' => Provider::has('appointments')->count(),
            'monthly_registrations' => Provider::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get(),
        ];

        return response()->json($analytics);
    }

    /**
     * Search providers with advanced filters
     */
    public function search(Request $request)
    {
        $query = Provider::with(['user', 'appointments'])
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                })->orWhere('specialty', 'like', "%{$request->search}%");
            })
            ->when($request->specialty, function ($q) use ($request) {
                $q->where('specialty', $request->specialty);
            })
            ->when($request->status !== null, function ($q) use ($request) {
                $q->where('is_active', $request->status);
            })
            ->when($request->min_rate, function ($q) use ($request) {
                $q->where('hourly_rate', '>=', $request->min_rate);
            })
            ->when($request->max_rate, function ($q) use ($request) {
                $q->where('hourly_rate', '<=', $request->max_rate);
            });

        $providers = $query->paginate(15);

        return response()->json($providers);
    }

    /**
     * Toggle provider status
     */
    public function toggleStatus(Provider $provider)
    {
        $provider->update(['is_active' => !$provider->is_active]);
        
        return response()->json([
            'status' => $provider->is_active,
            'message' => $provider->is_active ? 'Provider activated' : 'Provider deactivated'
        ]);
    }

    /**
     * Get provider statistics
     */
    private function getProviderStats()
    {
        return [
            'total' => Provider::count(),
            'active' => Provider::where('is_active', true)->count(),
            'inactive' => Provider::where('is_active', false)->count(),
            'with_appointments' => Provider::has('appointments')->count(),
            'average_rate' => Provider::whereNotNull('hourly_rate')->avg('hourly_rate'),
        ];
    }

    /**
     * Get filtered providers for export
     */
    private function getFilteredProviders(Request $request)
    {
        return QueryBuilder::for(Provider::class)
            ->with(['user', 'appointments', 'availabilitySlots'])
            ->allowedFilters([
                'specialty',
                'is_active',
                AllowedFilter::partial('user.name'),
                AllowedFilter::partial('user.email'),
                AllowedFilter::scope('hourly_rate_range'),
                AllowedFilter::scope('created_date_range'),
            ])
            ->get();
    }
}
