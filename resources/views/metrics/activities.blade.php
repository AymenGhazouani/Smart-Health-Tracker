@extends('layouts.app')

@section('title', 'Activities Detail')

@section('content')
<div class="metrics-detail-page">
    <header class="page-header">
        <div class="header-content">
            <h1>Activity Log</h1>
            <p>Track your physical activities and workouts</p>
        </div>
        <div class="header-icon">🏃</div>
    </header>
    
    <div class="data-container">
        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Activity Type</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Distance</th>
                            <th scope="col">Calories</th>
                            <th scope="col">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $a)
                            <tr class="data-row">
                                <td class="date-cell">
                                    <div class="date-primary">{{ $a->performed_at->format('M j, Y') }}</div>
                                    <div class="date-secondary">{{ $a->performed_at->format('g:i A') }}</div>
                                </td>
                                <td class="activity-cell">
                                    <div class="activity-type-badge">
                                        <span class="activity-icon">{{ $a->type === 'running' ? '🏃' : ($a->type === 'cycling' ? '🚴' : ($a->type === 'swimming' ? '🏊' : '💪')) }}</span>
                                        <span class="activity-name">{{ ucfirst($a->type) }}</span>
                                    </div>
                                </td>
                                <td class="duration-cell">
                                    <div class="duration-primary">{{ $a->duration_minutes }} min</div>
                                    @if($a->duration_minutes >= 60)
                                        <div class="duration-secondary">{{ floor($a->duration_minutes / 60) }}h {{ $a->duration_minutes % 60 }}m</div>
                                    @endif
                                </td>
                                <td class="distance-cell">
                                    @if($a->distance_km_times100)
                                        <div class="distance-value">{{ number_format($a->distance_km_times100/100, 2) }} km</div>
                                    @else
                                        <div class="no-data">-</div>
                                    @endif
                                </td>
                                <td class="calories-cell">
                                    @if($a->calories)
                                        <div class="calories-value">{{ $a->calories }} kcal</div>
                                    @else
                                        <div class="no-data">-</div>
                                    @endif
                                </td>
                                <td class="notes-cell">{{ $a->note ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="6" class="empty-message">
                                    <div class="empty-icon">🏃‍♂️</div>
                                    <div class="empty-text">No activity data recorded yet</div>
                                    <div class="empty-subtext">Start logging your activities to see your fitness journey here</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($activities->hasPages())
            <div class="pagination-container">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.metrics-detail-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: linear-gradient(135deg, var(--pico-background-color) 0%, rgba(var(--pico-primary-rgb), 0.05) 100%);
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 1.5rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #10b981, #f59e0b);
}

.header-content h1 {
    color: var(--pico-primary);
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.header-content p {
    color: var(--pico-muted-color);
    font-size: 1.1rem;
    margin: 0;
}

.header-icon {
    font-size: 4rem;
    opacity: 0.3;
}

.data-container {
    background: var(--pico-background-color);
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 1.5rem;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table-container {
    overflow: hidden;
    border-radius: 1rem;
    border: 1px solid var(--pico-muted-border-color);
}

.table-wrapper {
    overflow-x: auto;
    margin: 0;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.data-table thead {
    background: linear-gradient(135deg, var(--pico-muted-background-color) 0%, rgba(var(--pico-primary-rgb), 0.1) 100%);
}

.data-table th {
    padding: 1.25rem 1rem;
    font-weight: 600;
    color: var(--pico-primary);
    text-align: left;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid var(--pico-muted-border-color);
}

.data-row:hover {
    background: linear-gradient(135deg, rgba(var(--pico-primary-rgb), 0.05) 0%, rgba(var(--pico-secondary-rgb), 0.05) 100%);
    transform: translateX(4px);
}

.data-table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

.date-cell {
    min-width: 150px;
}

.date-primary {
    font-weight: 600;
    color: var(--pico-color);
    font-size: 1rem;
}

.date-secondary {
    color: var(--pico-muted-color);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.activity-cell {
    min-width: 140px;
}

.activity-type-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, rgba(var(--pico-primary-rgb), 0.1) 0%, rgba(var(--pico-secondary-rgb), 0.1) 100%);
    padding: 0.5rem 0.75rem;
    border-radius: 0.75rem;
    border: 2px solid rgba(var(--pico-primary-rgb), 0.2);
}

.activity-icon {
    font-size: 1.25rem;
}

.activity-name {
    font-weight: 600;
    color: var(--pico-primary);
    text-transform: capitalize;
}

.duration-cell {
    text-align: center;
    min-width: 120px;
}

.duration-primary {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--pico-primary);
    background: linear-gradient(135deg, rgba(var(--pico-primary-rgb), 0.1) 0%, rgba(var(--pico-secondary-rgb), 0.1) 100%);
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    display: inline-block;
    border: 2px solid rgba(var(--pico-primary-rgb), 0.2);
}

.duration-secondary {
    color: var(--pico-muted-color);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.distance-cell, .calories-cell {
    text-align: center;
    min-width: 100px;
}

.distance-value, .calories-value {
    font-weight: 600;
    color: var(--pico-secondary);
    background: rgba(var(--pico-secondary-rgb), 0.1);
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    display: inline-block;
    border: 1px solid rgba(var(--pico-secondary-rgb), 0.2);
}

.calories-value {
    color: #f59e0b;
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.2);
}

.no-data {
    color: var(--pico-muted-color);
    font-style: italic;
}

.notes-cell {
    color: var(--pico-muted-color);
    font-style: italic;
    max-width: 150px;
}

.empty-row {
    text-align: center;
}

.empty-message {
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-text {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--pico-muted-color);
    margin-bottom: 0.5rem;
}

.empty-subtext {
    font-size: 0.95rem;
    color: var(--pico-muted-color);
    opacity: 0.8;
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--pico-muted-border-color);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .metrics-detail-page {
        padding: 1rem;
    }
    
    .page-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .header-content h1 {
        font-size: 2rem;
    }
    
    .data-container {
        padding: 1rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 1rem 0.5rem;
    }
    
    .duration-primary {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }
    
    .activity-type-badge {
        flex-direction: column;
        gap: 0.25rem;
        text-align: center;
    }
    
    .distance-value, .calories-value {
        font-size: 0.875rem;
        padding: 0.375rem 0.5rem;
    }
}
</style>
@endsection


