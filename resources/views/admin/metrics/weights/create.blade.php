@extends('layouts.app')

@section('title', 'Admin · Add Weight for '.$user->name)

@section('content')
<div class="admin-form-container">
    <header class="page-header">
        <h1>Add Weight for {{ $user->name }}</h1>
        <a href="{{ route('admin.metrics.weights.index', $user) }}" class="back-btn">← Back to Weights</a>
    </header>

    <div class="form-container">
        <form method="POST" action="{{ route('admin.metrics.weights.store', $user) }}" class="admin-form">
            @csrf
            <div class="form-group">
                <label for="value_kg">Value (kg)</label>
                <input type="number" id="value_kg" name="value_kg" step="0.01" min="1" max="500" value="{{ old('value_kg') }}" required>
            </div>
            
            <div class="form-group">
                <label for="measured_at">Measured at</label>
                <input type="datetime-local" id="measured_at" name="measured_at" value="{{ old('measured_at') }}" required>
            </div>
            
            <div class="form-group">
                <label for="note">Note (optional)</label>
                <input type="text" id="note" name="note" value="{{ old('note') }}" placeholder="Add a note about this measurement">
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <h4>Please fix the following errors:</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-actions">
                <a href="{{ route('admin.metrics.weights.index', $user) }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-save">Save Weight</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-form-container {
    max-width: 800px;
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

.back-btn {
    color: var(--pico-primary);
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border: 1px solid var(--pico-primary);
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
}

.form-container {
    background: var(--pico-background-color);
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.admin-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-weight: 600;
    color: var(--pico-primary);
}

.form-group input {
    padding: 0.75rem;
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--pico-primary);
    box-shadow: 0 0 0 3px rgba(var(--pico-primary-rgb), 0.2);
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid;
}

.alert-error {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
}

.alert-error h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
}

.alert-error ul {
    margin: 0;
    padding-left: 1.5rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1rem;
}

.btn-cancel, .btn-save {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-cancel {
    background: var(--pico-muted-background-color);
    color: var(--pico-muted-color);
    border: 1px solid var(--pico-muted-border-color);
}

.btn-cancel:hover {
    background: var(--pico-muted-color);
    color: var(--pico-muted-background-color);
}

.btn-save {
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
}

.btn-save:hover {
    background: var(--pico-primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--pico-primary-rgb), 0.3);
}
</style>
@endsection


