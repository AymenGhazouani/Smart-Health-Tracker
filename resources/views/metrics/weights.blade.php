@extends('layouts.app')

@section('title', 'Weights Detail')

@section('content')
<div class="metrics-detail-page">
    <header class="page-header">
        <div class="header-content">
            <h1>Weight History</h1>
            <p>Track your weight progress over time</p>
        </div>
        <div class="header-icon">⚖️</div>
    </header>
    
    <div class="data-container">
        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Weight (kg)</th>
                            <th scope="col">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($weights as $w)
                            <tr class="data-row">
                                <td class="date-cell">
                                    <div class="date-primary">{{ $w->measured_at->format('M j, Y') }}</div>
                                    <div class="date-secondary">{{ $w->measured_at->format('g:i A') }}</div>
                                </td>
                                <td class="weight-cell">
                                    <div class="weight-value">{{ number_format((float)$w->value_kg, 2) }} kg</div>
                                </td>
                                <td class="notes-cell">{{ $w->note ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="3" class="empty-message">
                                    <div class="empty-icon">📊</div>
                                    <div class="empty-text">No weight data recorded yet</div>
                                    <div class="empty-subtext">Start tracking your weight to see your progress here</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($weights->hasPages())
            <div class="pagination-container">
                {{ $weights->links() }}
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
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
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
    padding: 1.25rem 1.5rem;
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
    padding: 1.25rem 1.5rem;
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

.weight-cell {
    text-align: center;
    min-width: 120px;
}

.weight-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--pico-primary);
    background: linear-gradient(135deg, rgba(var(--pico-primary-rgb), 0.1) 0%, rgba(var(--pico-secondary-rgb), 0.1) 100%);
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    display: inline-block;
    border: 2px solid rgba(var(--pico-primary-rgb), 0.2);
}

.notes-cell {
    color: var(--pico-muted-color);
    font-style: italic;
    max-width: 200px;
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
        padding: 1rem 0.75rem;
    }
    
    .weight-value {
        font-size: 1.25rem;
        padding: 0.5rem 0.75rem;
    }
}
</style>
@endsection


