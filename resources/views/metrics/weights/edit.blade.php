@extends('layouts.app')

@section('title', 'Edit Weight Entry')

@section('content')
<div class="metrics-form-page">
    <header class="page-header">
        <div class="header-content">
            <h1>Edit Weight Entry</h1>
            <p>Update your weight measurement</p>
        </div>
        <div class="header-icon">⚖️</div>
    </header>

    <div class="form-container">
        <form method="POST" action="{{ route('metrics.weights.update', $weight) }}" class="metrics-form">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="value_kg" class="form-label">
                        Weight (kg) <span class="required">*</span>
                    </label>
                    <input type="number" 
                           id="value_kg" 
                           name="value_kg" 
                           step="0.01" 
                           min="1" 
                           max="500" 
                           value="{{ old('value_kg', $weight->value_kg) }}" 
                           class="form-input @error('value_kg') error @enderror"
                           placeholder="Enter weight in kilograms"
                           required>
                    @error('value_kg')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="measured_at" class="form-label">
                        Date & Time <span class="required">*</span>
                    </label>
                    <input type="datetime-local" 
                           id="measured_at" 
                           name="measured_at" 
                           value="{{ old('measured_at', $weight->measured_at->format('Y-m-d\TH:i')) }}" 
                           class="form-input @error('measured_at') error @enderror"
                           required>
                    @error('measured_at')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="note" class="form-label">Note (optional)</label>
                <textarea id="note" 
                          name="note" 
                          rows="3"
                          class="form-textarea @error('note') error @enderror"
                          placeholder="Add any notes about this measurement (e.g., after workout, morning weight, etc.)">{{ old('note', $weight->note) }}</textarea>
                @error('note')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview Card -->
            <div class="preview-card">
                <h4>Preview</h4>
                <div class="preview-content">
                    <div class="preview-item">
                        <span class="preview-label">Weight:</span>
                        <span class="preview-value" id="preview-weight">{{ number_format($weight->value_kg, 2) }} kg</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Date:</span>
                        <span class="preview-value" id="preview-date">{{ $weight->measured_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Note:</span>
                        <span class="preview-value" id="preview-note">{{ $weight->note ?: '--' }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="metadata-card">
                <h4>Entry Information</h4>
                <div class="metadata-content">
                    <div class="metadata-item">
                        <span class="metadata-label">Created:</span>
                        <span class="metadata-value">{{ $weight->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label">Last Updated:</span>
                        <span class="metadata-value">{{ $weight->updated_at->format('M j, Y g:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('metrics.weights') }}" class="btn-cancel">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Update Weight Entry
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const weightInput = document.getElementById('value_kg');
    const dateInput = document.getElementById('measured_at');
    const noteInput = document.getElementById('note');

    function updatePreview() {
        document.getElementById('preview-weight').textContent = weightInput.value ? weightInput.value + ' kg' : '-- kg';
        
        if (dateInput.value) {
            const date = new Date(dateInput.value);
            document.getElementById('preview-date').textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } else {
            document.getElementById('preview-date').textContent = '--';
        }
        
        document.getElementById('preview-note').textContent = noteInput.value || '--';
    }

    weightInput.addEventListener('input', updatePreview);
    dateInput.addEventListener('change', updatePreview);
    noteInput.addEventListener('input', updatePreview);
});
</script>

<style>
.metrics-form-page {
    max-width: 800px;
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

.form-container {
    background: var(--pico-background-color);
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 1.5rem;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.metrics-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: var(--pico-color);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.required {
    color: #dc2626;
}

.form-input, .form-textarea {
    padding: 0.75rem 1rem;
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--pico-background-color);
    color: var(--pico-color);
}

.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--pico-primary);
    box-shadow: 0 0 0 3px rgba(var(--pico-primary-rgb), 0.1);
}

.form-input.error, .form-textarea.error {
    border-color: #dc2626;
}

.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.preview-card, .metadata-card {
    background: linear-gradient(135deg, rgba(var(--pico-primary-rgb), 0.05) 0%, rgba(var(--pico-secondary-rgb), 0.05) 100%);
    border: 1px solid rgba(var(--pico-primary-rgb), 0.2);
    border-radius: 1rem;
    padding: 1.5rem;
    margin: 1rem 0;
}

.preview-card h4, .metadata-card h4 {
    color: var(--pico-primary);
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.preview-content, .metadata-content {
    display: grid;
    gap: 0.75rem;
}

.preview-item, .metadata-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.preview-label, .metadata-label {
    font-weight: 500;
    color: var(--pico-muted-color);
}

.preview-value, .metadata-value {
    font-weight: 600;
    color: var(--pico-color);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1rem;
}

.btn-cancel, .btn-submit {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-cancel {
    background: var(--pico-muted-background-color);
    color: var(--pico-muted-color);
    border: 2px solid var(--pico-muted-border-color);
}

.btn-cancel:hover {
    background: var(--pico-muted-border-color);
    color: var(--pico-color);
}

.btn-submit {
    background: linear-gradient(135deg, var(--pico-primary) 0%, var(--pico-secondary) 100%);
    color: var(--pico-primary-inverse);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .metrics-form-page {
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
    
    .form-container {
        padding: 1rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endsection

