@extends('layouts.app')

@section('title', 'Add Sleep Session')

@section('content')
<div class="metrics-form-page">
    <header class="page-header">
        <div class="header-content">
            <h1>Add Sleep Session</h1>
            <p>Record a new sleep session</p>
        </div>
        <div class="header-icon">😴</div>
    </header>

    <div class="form-container">
        <form method="POST" action="{{ route('metrics.sleep.store') }}" class="metrics-form">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="started_at" class="form-label">
                        Sleep Start Time <span class="required">*</span>
                    </label>
                    <input type="datetime-local" 
                           id="started_at" 
                           name="started_at" 
                           value="{{ old('started_at', now()->subHours(8)->format('Y-m-d\TH:i')) }}" 
                           class="form-input @error('started_at') error @enderror"
                           required>
                    @error('started_at')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ended_at" class="form-label">
                        Wake Up Time <span class="required">*</span>
                    </label>
                    <input type="datetime-local" 
                           id="ended_at" 
                           name="ended_at" 
                           value="{{ old('ended_at', now()->format('Y-m-d\TH:i')) }}" 
                           class="form-input @error('ended_at') error @enderror"
                           required>
                    @error('ended_at')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="quality" class="form-label">Sleep Quality (1-10)</label>
                    <select id="quality" 
                            name="quality" 
                            class="form-input @error('quality') error @enderror">
                        <option value="">Select quality (optional)</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('quality') == $i ? 'selected' : '' }}>
                                {{ $i }} - {{ $i <= 3 ? 'Poor' : ($i <= 6 ? 'Fair' : ($i <= 8 ? 'Good' : 'Excellent')) }}
                            </option>
                        @endfor
                    </select>
                    @error('quality')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                    <input type="number" 
                           id="duration_minutes" 
                           name="duration_minutes" 
                           min="1" 
                           max="1440" 
                           value="{{ old('duration_minutes') }}" 
                           class="form-input @error('duration_minutes') error @enderror"
                           placeholder="Auto-calculated from start/end times"
                           readonly>
                    @error('duration_minutes')
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
                          placeholder="Add any notes about this sleep session (e.g., had trouble falling asleep, woke up refreshed, etc.)">{{ old('note') }}</textarea>
                @error('note')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview Card -->
            <div class="preview-card">
                <h4>Preview</h4>
                <div class="preview-content">
                    <div class="preview-item">
                        <span class="preview-label">Sleep Time:</span>
                        <span class="preview-value" id="preview-start">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Wake Time:</span>
                        <span class="preview-value" id="preview-end">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Duration:</span>
                        <span class="preview-value" id="preview-duration">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Quality:</span>
                        <span class="preview-value" id="preview-quality">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Note:</span>
                        <span class="preview-value" id="preview-note">--</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('metrics.sleep') }}" class="btn-cancel">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Sleep Session
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('started_at');
    const endInput = document.getElementById('ended_at');
    const qualitySelect = document.getElementById('quality');
    const noteInput = document.getElementById('note');
    const durationInput = document.getElementById('duration_minutes');

    function updatePreview() {
        // Update start time
        if (startInput.value) {
            const startDate = new Date(startInput.value);
            document.getElementById('preview-start').textContent = startDate.toLocaleDateString() + ' ' + startDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } else {
            document.getElementById('preview-start').textContent = '--';
        }

        // Update end time
        if (endInput.value) {
            const endDate = new Date(endInput.value);
            document.getElementById('preview-end').textContent = endDate.toLocaleDateString() + ' ' + endDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } else {
            document.getElementById('preview-end').textContent = '--';
        }

        // Calculate and update duration
        if (startInput.value && endInput.value) {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            const duration = Math.round((end - start) / (1000 * 60)); // minutes
            
            if (duration > 0) {
                const hours = Math.floor(duration / 60);
                const minutes = duration % 60;
                document.getElementById('preview-duration').textContent = `${hours}h ${minutes}m (${duration} min)`;
                durationInput.value = duration;
            } else {
                document.getElementById('preview-duration').textContent = 'Invalid (end time must be after start time)';
                durationInput.value = '';
            }
        } else {
            document.getElementById('preview-duration').textContent = '--';
            durationInput.value = '';
        }

        // Update quality
        if (qualitySelect.value) {
            const quality = parseInt(qualitySelect.value);
            const qualityText = quality <= 3 ? 'Poor' : (quality <= 6 ? 'Fair' : (quality <= 8 ? 'Good' : 'Excellent'));
            document.getElementById('preview-quality').textContent = `${quality}/10 (${qualityText})`;
        } else {
            document.getElementById('preview-quality').textContent = '--';
        }

        // Update note
        document.getElementById('preview-note').textContent = noteInput.value || '--';
    }

    startInput.addEventListener('change', updatePreview);
    endInput.addEventListener('change', updatePreview);
    qualitySelect.addEventListener('change', updatePreview);
    noteInput.addEventListener('input', updatePreview);

    // Initial preview update
    updatePreview();
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

.form-input, .form-textarea, select.form-input {
    padding: 0.75rem 1rem;
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--pico-background-color);
    color: var(--pico-color);
}

.form-input:focus, .form-textarea:focus, select.form-input:focus {
    outline: none;
    border-color: var(--pico-primary);
    box-shadow: 0 0 0 3px rgba(var(--pico-primary-rgb), 0.1);
}

.form-input.error, .form-textarea.error, select.form-input.error {
    border-color: #dc2626;
}

.form-input[readonly] {
    background: var(--pico-muted-background-color);
    color: var(--pico-muted-color);
}

.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.preview-card {
    background: linear-gradient(135deg, rgba(var(--pico-primary-rgb), 0.05) 0%, rgba(var(--pico-secondary-rgb), 0.05) 100%);
    border: 1px solid rgba(var(--pico-primary-rgb), 0.2);
    border-radius: 1rem;
    padding: 1.5rem;
    margin: 1rem 0;
}

.preview-card h4 {
    color: var(--pico-primary);
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.preview-content {
    display: grid;
    gap: 0.75rem;
}

.preview-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.preview-label {
    font-weight: 500;
    color: var(--pico-muted-color);
}

.preview-value {
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

