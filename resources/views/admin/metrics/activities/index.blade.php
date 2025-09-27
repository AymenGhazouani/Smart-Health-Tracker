@extends('layouts.app')

@section('title', 'Admin · Activities for '.$user->name)

@section('content')
<div class="admin-crud-container">
    <header class="page-header">
        <h1>Activities for {{ $user->name }}</h1>
        <a href="{{ route('admin.metrics.activities.create', $user) }}" role="button" class="add-btn">Add Activity</a>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-container">
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Duration</th>
                        <th>Distance</th>
                        <th>Calories</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $a)
                        <tr>
                            <td>{{ $a->performed_at->format('Y-m-d H:i') }}</td>
                            <td class="activity-type"><strong>{{ ucfirst($a->type) }}</strong></td>
                            <td class="text-center contrast">{{ $a->duration_minutes }} min</td>
                            <td class="text-center">{{ $a->distance_km_times100 ? number_format($a->distance_km_times100/100, 2).' km' : '-' }}</td>
                            <td class="text-center calories">{{ $a->calories ?? '-' }}</td>
                            <td>{{ $a->note ?: '-' }}</td>
                            <td class="text-center">
                                <a role="button" href="{{ route('admin.metrics.activities.edit', [$user, $a]) }}" class="btn-edit">Edit</a>
                                <form method="POST" action="{{ route('admin.metrics.activities.destroy', [$user, $a]) }}" style="display:inline" onsubmit="return confirm('Delete this activity?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center"><em>No activities.</em></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-wrapper">
        {{ $activities->links() }}
    </div>

    <div class="back-link">
        <a href="{{ route('admin.metrics.dashboard') }}">← Back to Admin Metrics</a>
    </div>
</div>

<style>
.admin-crud-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--pico-muted-border-color);
}

.page-header h1 {
    color: var(--pico-primary);
    margin: 0;
}

.add-btn {
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.add-btn:hover {
    background: var(--pico-primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--pico-primary-rgb), 0.3);
}

.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border-color: #a7f3d0;
}

.table-container {
    background: var(--pico-background-color);
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table-wrapper {
    overflow-x: auto;
    margin: 0;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.admin-table thead {
    background: var(--pico-muted-background-color);
}

.admin-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--pico-primary);
    border-bottom: 2px solid var(--pico-primary);
}

.admin-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--pico-muted-border-color);
}

.admin-table tbody tr:hover {
    background: var(--pico-background-color-hover);
}

.text-center {
    text-align: center;
}

.contrast {
    color: var(--pico-primary);
    font-weight: 600;
}

.activity-type strong {
    color: var(--pico-primary);
}

.calories {
    color: var(--pico-secondary);
    font-weight: 600;
}

.btn-edit, .btn-delete {
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-edit {
    background: var(--pico-secondary);
    color: var(--pico-secondary-inverse);
}

.btn-edit:hover {
    background: var(--pico-secondary-hover);
    transform: translateY(-1px);
}

.btn-delete {
    background: #dc2626;
    color: white;
}

.btn-delete:hover {
    background: #b91c1c;
    transform: translateY(-1px);
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin: 2rem 0;
}

.back-link {
    text-align: center;
    margin-top: 2rem;
}

.back-link a {
    color: var(--pico-primary);
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border: 1px solid var(--pico-primary);
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.back-link a:hover {
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
}
</style>
@endsection


