@extends('layouts.app')

@section('title', 'Add Activity')

@section('content')
<div class="metrics-form-page">
    <header class="page-header">
        <div class="header-content">
            <h1>Add Activity</h1>
            <p>Log a new physical activity or workout</p>
        </div>
        <div class="header-icon">🏃</div>
    </header>

    <div class="form-container">
        <form method="POST" action="{{ route('metrics.activities.store') }}" class="metrics-form">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="performed_at" class="form-label">
                        Date & Time <span class="required">*</span>
                    </label>
                    <input type="datetime-local" 
                           id="performed_at" 
                           name="performed_at" 
                           value="{{ old('performed_at', now()->format('Y-m-d\TH:i')) }}" 
                           class="form-input @error('performed_at') error @enderror"
                           required>
                    @error('performed_at')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type" class="form-label">
                        Activity Type <span class="required">*</span>
                    </label>
                    <select id="type" 
                            name="type" 
                            class="form-input @error('type') error @enderror"
                            required>
                        <option value="">Select activity type</option>
                        <option value="running" {{ old('type') == 'running' ? 'selected' : '' }}>🏃 Running</option>
                        <option value="cycling" {{ old('type') == 'cycling' ? 'selected' : '' }}>🚴 Cycling</option>
                        <option value="swimming" {{ old('type') == 'swimming' ? 'selected' : '' }}>🏊 Swimming</option>
                        <option value="walking" {{ old('type') == 'walking' ? 'selected' : '' }}>🚶 Walking</option>
                        <option value="gym" {{ old('type') == 'gym' ? 'selected' : '' }}>💪 Gym Workout</option>
                        <option value="yoga" {{ old('type') == 'yoga' ? 'selected' : '' }}>🧘 Yoga</option>
                        <option value="dancing" {{ old('type') == 'dancing' ? 'selected' : '' }}>💃 Dancing</option>
                        <option value="hiking" {{ old('type') == 'hiking' ? 'selected' : '' }}>🥾 Hiking</option>
                        <option value="sports" {{ old('type') == 'sports' ? 'selected' : '' }}>⚽ Sports</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>🏃‍♂️ Other</option>
                    </select>
                    @error('type')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="duration_minutes" class="form-label">
                        Duration (minutes) <span class="required">*</span>
                    </label>
                    <input type="number" 
                           id="duration_minutes" 
                           name="duration_minutes" 
                           min="1" 
                           max="1440" 
                           value="{{ old('duration_minutes') }}" 
                           class="form-input @error('duration_minutes') error @enderror"
                           placeholder="Enter duration in minutes"
                           required>
                    @error('duration_minutes')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="distance_km_times100" class="form-label">Distance (km)</label>
                    <input type="number" 
                           id="distance_km_times100" 
                           name="distance_km_times100" 
                           min="0" 
                           step="0.01" 
                           value="{{ old('distance_km_times100') }}" 
                           class="form-input @error('distance_km_times100') error @enderror"
                           placeholder="Enter distance in kilometers (optional)">
                    @error('distance_km_times100')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                    <small class="form-help">Enter distance in kilometers (e.g., 5.5 for 5.5 km)</small>
                </div>
            </div>

            <div class="form-group">
                <label for="calories" class="form-label">Calories Burned</label>
                <input type="number" 
                       id="calories" 
                       name="calories" 
                       min="0" 
                       max="5000" 
                       value="{{ old('calories') }}" 
                       class="form-input @error('calories') error @enderror"
                       placeholder="Enter estimated calories burned (optional)">
                @error('calories')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="note" class="form-label">Note (optional)</label>
                <textarea id="note" 
                          name="note" 
                          rows="3"
                          class="form-textarea @error('note') error @enderror"
                          placeholder="Add any notes about this activity (e.g., intensity, how you felt, equipment used, etc.)">{{ old('note') }}</textarea>
                @error('note')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview Card -->
            <div class="preview-card">
                <h4>Preview</h4>
                <div class="preview-content">
                    <div class="preview-item">
                        <span class="preview-label">Date & Time:</span>
                        <span class="preview-value" id="preview-datetime">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Activity:</span>
                        <span class="preview-value" id="preview-type">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Duration:</span>
                        <span class="preview-value" id="preview-duration">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Distance:</span>
                        <span class="preview-value" id="preview-distance">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Calories:</span>
                        <span class="preview-value" id="preview-calories">--</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Note:</span>
                        <span class="preview-value" id="preview-note">--</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('metrics.activities') }}" class="btn-cancel">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Activity
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const datetimeInput = document.getElementById('performed_at');
    const typeSelect = document.getElementById('type');
    const durationInput = document.getElementById('duration_minutes');
    const distanceInput = document.getElementById('distance_km_times100');
    const caloriesInput = document.getElementById('calories');
    const noteInput = document.getElementById('note');

    function updatePreview() {
        // Update date & time
        if (datetimeInput.value) {
            const date = new Date(datetimeInput.value);
            document.getElementById('preview-datetime').textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } else {
            document.getElementById('preview-datetime').textContent = '--';
        }

        // Update activity type
        if (typeSelect.value) {
            const typeText = typeSelect.options[typeSelect.selectedIndex].text;
            document.getElementById('preview-type').textContent = typeText;
        } else {
            document.getElementById('preview-type').textContent = '--';
        }

        // Update duration
        if (durationInput.value) {
            const duration = parseInt(durationInput.value);
            const hours = Math.floor(duration / 60);
            const minutes = duration % 60;
            if (hours > 0) {
                document.getElementById('preview-duration').textContent = `${hours}h ${minutes}m (${duration} min)`;
            } else {
                document.getElementById('preview-duration').textContent = `${minutes} min`;
            }
        } else {
            document.getElementById('preview-duration').textContent = '--';
        }

        // Update distance
        if (distanceInput.value) {
            document.getElementById('preview-distance').textContent = distanceInput.value + ' km';
        } else {
            document.getElementById('preview-distance').textContent = '--';
        }

        // Update calories
        if (caloriesInput.value) {
            document.getElementById('preview-calories').textContent = caloriesInput.value + ' kcal';
        } else {
            document.getElementById('preview-calories').textContent = '--';
        }

        // Update note
        document.getElementById('preview-note').textContent = noteInput.value || '--';
    }

    datetimeInput.addEventListener('change', updatePreview);
    typeSelect.addEventListener('change', updatePreview);
    durationInput.addEventListener('input', updatePreview);
    distanceInput.addEventListener('input', updatePreview);
    caloriesInput.addEventListener('input', updatePreview);
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

.form-help {
    color: var(--pico-muted-color);
    font-size: 0.875rem;
    margin-top: 0.25rem;
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

