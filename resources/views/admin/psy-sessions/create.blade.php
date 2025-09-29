@extends('layouts.admin')

@section('page-title', 'Book New Session')

@section('admin-content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Book New Session</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Schedule a new psychology session</p>
        </div>

        <form action="{{ route('admin.psy-sessions.store') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Psychologist -->
                <div>
                    <label for="psychologist_id" class="block text-sm font-medium text-gray-700">Psychologist *</label>
                    <select name="psychologist_id" id="psychologist_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('psychologist_id') border-red-300 @enderror">
                        <option value="">Select a psychologist</option>
                        @foreach($psychologists as $psychologist)
                            <option value="{{ $psychologist->id }}" {{ old('psychologist_id') == $psychologist->id ? 'selected' : '' }}>
                                {{ $psychologist->name }} - {{ $psychologist->specialty }} 
                                @if($psychologist->hourly_rate) (${{ number_format($psychologist->hourly_rate, 2) }}/hr) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('psychologist_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Patient -->
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient *</label>
                    <select name="patient_id" id="patient_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('patient_id') border-red-300 @enderror">
                        <option value="">Select a patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} ({{ $patient->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time *</label>
                    <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('start_time') border-red-300 @enderror">
                    @error('start_time')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                    <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('end_time') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Leave empty for 1-hour session</p>
                    @error('end_time')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('status') border-red-300 @enderror">
                        <option value="booked" {{ old('status', 'booked') == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ old('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Session Fee -->
                <div>
                    <label for="session_fee" class="block text-sm font-medium text-gray-700">Session Fee ($)</label>
                    <input type="number" name="session_fee" id="session_fee" value="{{ old('session_fee') }}" step="0.01" min="0"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('session_fee') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to use psychologist's default rate</p>
                    @error('session_fee')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="sm:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Public notes visible to patient</p>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.psy-sessions.index') }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Book Session
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-set end time when start time changes
document.getElementById('start_time').addEventListener('change', function() {
    const startTime = new Date(this.value);
    if (startTime && !document.getElementById('end_time').value) {
        const endTime = new Date(startTime.getTime() + 60 * 60 * 1000); // Add 1 hour
        document.getElementById('end_time').value = endTime.toISOString().slice(0, 16);
    }
});

// Auto-set session fee when psychologist changes
document.getElementById('psychologist_id').addEventListener('change', function() {
    const psychologistId = this.value;
    if (psychologistId) {
        // You could fetch the psychologist's hourly rate via AJAX here
        // For now, we'll leave it as is since the user can set it manually
    }
});
</script>
@endsection
