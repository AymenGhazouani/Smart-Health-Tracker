<?php

namespace App\Services;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProviderFilterService
{
    /**
     * Apply advanced filters to provider query
     */
    public function applyFilters(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('search'), function ($q) use ($request) {
                $this->applySearchFilter($q, $request->search);
            })
            ->when($request->filled('specialty'), function ($q) use ($request) {
                $q->where('specialty', $request->specialty);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('is_active', $request->status === 'active');
            })
            ->when($request->filled('min_rate'), function ($q) use ($request) {
                $q->where('hourly_rate', '>=', $request->min_rate);
            })
            ->when($request->filled('max_rate'), function ($q) use ($request) {
                $q->where('hourly_rate', '<=', $request->max_rate);
            })
            ->when($request->filled('has_appointments'), function ($q) use ($request) {
                if ($request->has_appointments === 'yes') {
                    $q->has('appointments');
                } elseif ($request->has_appointments === 'no') {
                    $q->doesntHave('appointments');
                }
            })
            ->when($request->filled('created_from'), function ($q) use ($request) {
                $q->where('created_at', '>=', $request->created_from);
            })
            ->when($request->filled('created_to'), function ($q) use ($request) {
                $q->where('created_at', '<=', $request->created_to);
            })
            ->when($request->filled('sort_by'), function ($q) use ($request) {
                $this->applySorting($q, $request->sort_by, $request->sort_direction ?? 'asc');
            });
    }

    /**
     * Apply search filter across multiple fields
     */
    private function applySearchFilter(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhere('specialty', 'like', "%{$search}%")
            ->orWhere('bio', 'like', "%{$search}%");
        });
    }

    /**
     * Apply sorting to the query
     */
    private function applySorting(Builder $query, string $sortBy, string $direction): void
    {
        $allowedSorts = [
            'name' => 'users.name',
            'specialty' => 'specialty',
            'hourly_rate' => 'hourly_rate',
            'created_at' => 'created_at',
            'appointments_count' => 'appointments_count',
        ];

        if (array_key_exists($sortBy, $allowedSorts)) {
            if ($sortBy === 'name') {
                $query->join('users', 'providers.user_id', '=', 'users.id')
                    ->orderBy($allowedSorts[$sortBy], $direction)
                    ->select('providers.*');
            } elseif ($sortBy === 'appointments_count') {
                $query->withCount('appointments')
                    ->orderBy('appointments_count', $direction);
            } else {
                $query->orderBy($allowedSorts[$sortBy], $direction);
            }
        }
    }

    /**
     * Get filter options for dropdowns
     */
    public function getFilterOptions(): array
    {
        return [
            'specialties' => Provider::distinct()
                ->whereNotNull('specialty')
                ->pluck('specialty')
                ->sort()
                ->values(),
            'rate_ranges' => [
                ['label' => 'Under $50', 'min' => 0, 'max' => 49.99],
                ['label' => '$50 - $100', 'min' => 50, 'max' => 100],
                ['label' => '$100 - $150', 'min' => 100, 'max' => 150],
                ['label' => 'Over $150', 'min' => 150, 'max' => null],
            ],
            'status_options' => [
                ['value' => 'active', 'label' => 'Active'],
                ['value' => 'inactive', 'label' => 'Inactive'],
            ],
            'sort_options' => [
                ['value' => 'name', 'label' => 'Name'],
                ['value' => 'specialty', 'label' => 'Specialty'],
                ['value' => 'hourly_rate', 'label' => 'Hourly Rate'],
                ['value' => 'created_at', 'label' => 'Registration Date'],
                ['value' => 'appointments_count', 'label' => 'Appointments Count'],
            ]
        ];
    }

    /**
     * Build filter summary for display
     */
    public function getFilterSummary(Request $request): array
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters[] = "Search: {$request->search}";
        }

        if ($request->filled('specialty')) {
            $filters[] = "Specialty: {$request->specialty}";
        }

        if ($request->filled('status')) {
            $filters[] = "Status: " . ucfirst($request->status);
        }

        if ($request->filled('min_rate') || $request->filled('max_rate')) {
            $min = $request->min_rate ?? '0';
            $max = $request->max_rate ?? '∞';
            $filters[] = "Rate: ${min} - ${max}";
        }

        if ($request->filled('has_appointments')) {
            $hasAppointments = $request->has_appointments === 'yes' ? 'With' : 'Without';
            $filters[] = "{$hasAppointments} appointments";
        }

        return $filters;
    }
}