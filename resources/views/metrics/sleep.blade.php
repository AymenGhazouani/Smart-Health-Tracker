@extends('layouts.app')

@section('title', 'Sleep Detail')

@section('content')
<div class="metrics-detail-page">
    <header class="page-header">
        <div class="header-content">
            <h1>Sleep Sessions</h1>
            <p>Track your sleep patterns and quality</p>
        </div>
        <div class="header-icon">😴</div>
    </header>
    
    <div class="data-container">
        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Sleep Time</th>
                            <th scope="col">Wake Time</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Quality</th>
                            <th scope="col">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sleepSessions as $s)
                            <tr class="data-row">
                                <td class="date-cell">
                                    <div class="date-primary">{{ $s->started_at->format('M j, Y') }}</div>
                                </td>
                                <td class="time-cell">
                                    <div class="time-value">{{ $s->started_at->format('g:i A') }}</div>
                                </td>
                                <td class="time-cell">
                                    <div class="time-value">{{ $s->ended_at->format('g:i A') }}</div>
                                </td>
                                <td class="duration-cell">
                                    <div class="duration-primary">{{ $s->duration_minutes }} min</div>
                                    <div class="duration-secondary">{{ floor($s->duration_minutes / 60) }}h {{ $s->duration_minutes % 60 }}m</div>
                                </td>
                                <td class="quality-cell">
                                    @if($s->quality)
                                        <div class="quality-badge quality-{{ $s->quality }}">
                                            {{ $s->quality }}/10
                                        </div>
                                    @else
                                        <div class="no-quality">-</div>
                                    @endif
                                </td>
                                <td class="notes-cell">{{ $s->note ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="6" class="empty-message">
                                    <div class="empty-icon">🌙</div>
                                    <div class="empty-text">No sleep data recorded yet</div>
                                    <div class="empty-subtext">Start tracking your sleep to see your patterns here</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($sleepSessions->hasPages())
            <div class="pagination-container">
                {{ $sleepSessions->links() }}
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
    background: linear-gradient(90deg, #06b6d4, #3b82f6);
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
    min-width: 120px;
}

.date-primary {
    font-weight: 600;
    color: var(--pico-color);
    font-size: 1rem;
}

.time-cell {
    text-align: center;
    min-width: 100px;
}

.time-value {
    font-weight: 600;
    color: var(--pico-primary);
    font-size: 1rem;
    background: rgba(var(--pico-primary-rgb), 0.1);
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    display: inline-block;
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

.quality-cell {
    text-align: center;
    min-width: 100px;
}

.quality-badge {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-align: center;
    min-width: 4rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.quality-badge:hover {
    transform: scale(1.05);
}

.quality-1, .quality-2, .quality-3 {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
    border-color: #fca5a5;
}

.quality-4, .quality-5, .quality-6 {
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    color: #d97706;
    border-color: #fdba74;
}

.quality-7, .quality-8 {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #059669;
    border-color: #6ee7b7;
}

.quality-9, .quality-10 {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
    border-color: #93c5fd;
}

.no-quality {
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
    
    .time-value {
        font-size: 0.875rem;
        padding: 0.375rem 0.5rem;
    }
}
</style>
@endsection


