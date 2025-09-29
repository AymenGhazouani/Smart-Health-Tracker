@extends('layouts.app')

@section('content')
<style>
@keyframes gentle-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

.psychologist-card:hover {
    animation: gentle-pulse 0.6s ease-in-out;
}

.psychologist-card:active {
    transform: scale(0.98);
    transition: transform 0.1s ease-in-out;
}
</style>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Book a Session</h1>
                    <p class="mt-1 text-sm text-gray-600">Schedule your psychology session</p>
                </div>
                <a href="{{ route('psychology.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('psychology.sessions.store') }}" method="POST" class="px-4 py-5 sm:p-6">
                @csrf
                
                <!-- Psychologist Selection -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Choose Your Psychologist</h3>
                    @if(request('psychologist'))
                        @php $selectedPsychologist = \App\Models\Psychologist::find(request('psychologist')); @endphp
                        @if($selectedPsychologist)
                            <div class="border border-blue-300 rounded-lg p-4 bg-blue-50 ring-2 ring-blue-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-12 w-12 rounded-full bg-blue-200 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-lg font-medium text-blue-900">{{ $selectedPsychologist->name }}</h4>
                                            <p class="text-sm text-blue-700">{{ $selectedPsychologist->specialty }}</p>
                                            @if($selectedPsychologist->hourly_rate)
                                                <p class="text-sm text-blue-600">${{ number_format($selectedPsychologist->hourly_rate, 2) }}/hour</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Selected
                                        </span>
                                        <a href="{{ route('psychology.book-session') }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
                                            Change
                                        </a>
                                    </div>
                                </div>
                                <input type="hidden" name="psychologist_id" value="{{ $selectedPsychologist->id }}">
                            </div>
                        @endif
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($psychologists as $psychologist)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="psychologist_id" value="{{ $psychologist->id }}" class="sr-only psychologist-radio" required>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 hover:shadow-md hover:shadow-blue-100 focus-within:ring-2 focus-within:ring-blue-200 focus-within:border-blue-300 transition-all duration-300 cursor-pointer group psychologist-card">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 group-hover:bg-blue-200 transition-colors duration-300 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-blue-600 group-hover:text-blue-700 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <h4 class="text-sm font-medium text-gray-900 group-hover:text-blue-900 transition-colors duration-300">{{ $psychologist->name }}</h4>
                                                <p class="text-sm text-gray-500 group-hover:text-blue-600 transition-colors duration-300">{{ $psychologist->specialty }}</p>
                                                @if($psychologist->hourly_rate)
                                                    <p class="text-xs text-gray-400 group-hover:text-blue-500 transition-colors duration-300">${{ number_format($psychologist->hourly_rate, 2) }}/hour</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    @error('psychologist_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Session Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Session Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label for="session_date" class="block text-sm font-medium text-gray-700">Date *</label>
                            <input type="date" name="session_date" id="session_date" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('session_date') border-red-300 @enderror"
                                   min="{{ now()->format('Y-m-d') }}">
                            @error('session_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time -->
                        <div>
                            <label for="session_time" class="block text-sm font-medium text-gray-700">Time *</label>
                            <select name="session_time" id="session_time" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('session_time') border-red-300 @enderror">
                                <option value="">Select a time</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="16:00">4:00 PM</option>
                                <option value="17:00">5:00 PM</option>
                                <option value="18:00">6:00 PM</option>
                            </select>
                            @error('session_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
                            <select name="duration" id="duration"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('duration') border-red-300 @enderror">
                                <option value="60" selected>60 minutes (Standard)</option>
                                <option value="90">90 minutes (Extended)</option>
                                <option value="120">120 minutes (Intensive)</option>
                            </select>
                            @error('duration')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Type -->
                        <div>
                            <label for="session_type" class="block text-sm font-medium text-gray-700">Session Type</label>
                            <select name="session_type" id="session_type"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('session_type') border-red-300 @enderror">
                                <option value="individual" selected>Individual Session</option>
                                <option value="couples">Couples Therapy</option>
                                <option value="family">Family Therapy</option>
                                <option value="group">Group Session</option>
                            </select>
                            @error('session_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="space-y-4">
                        <!-- Reason for Visit -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Visit</label>
                            <textarea name="reason" id="reason" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('reason') border-red-300 @enderror"
                                      placeholder="Please describe what you'd like to work on in this session...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label for="special_requests" class="block text-sm font-medium text-gray-700">Special Requests</label>
                            <textarea name="special_requests" id="special_requests" rows="2"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('special_requests') border-red-300 @enderror"
                                      placeholder="Any special accommodations or requests...">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Session Summary -->
                <div class="mb-8 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Session Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Psychologist:</span>
                            <span class="text-gray-900" id="summary-psychologist">Not selected</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Date:</span>
                            <span class="text-gray-900" id="summary-date">Not selected</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Time:</span>
                            <span class="text-gray-900" id="summary-time">Not selected</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Duration:</span>
                            <span class="text-gray-900" id="summary-duration">60 minutes</span>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-8">
                    <div class="flex items-start">
                        <input type="checkbox" name="terms_accepted" id="terms_accepted" required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="terms_accepted" class="ml-2 text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-900">Terms and Conditions</a> and <a href="#" class="text-blue-600 hover:text-blue-900">Privacy Policy</a>. I understand that:
                            <ul class="mt-2 list-disc list-inside text-xs text-gray-600">
                                <li>Sessions can be cancelled up to 24 hours in advance</li>
                                <li>Late cancellations may incur a fee</li>
                                <li>All sessions are confidential</li>
                                <li>Payment is due at the time of booking</li>
                            </ul>
                        </label>
                    </div>
                    @error('terms_accepted')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('psychology.dashboard') }}" 
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
</div>

<script>
// Update session summary in real-time
document.addEventListener('DOMContentLoaded', function() {
    const psychologistRadios = document.querySelectorAll('input[name="psychologist_id"]');
    const dateInput = document.getElementById('session_date');
    const timeSelect = document.getElementById('session_time');
    const durationSelect = document.getElementById('duration');

    function updateSummary() {
        // Update psychologist - handle both radio buttons and pre-selected cases
        const selectedPsychologist = document.querySelector('input[name="psychologist_id"]:checked');
        const hiddenPsychologist = document.querySelector('input[name="psychologist_id"][type="hidden"]');
        
        if (selectedPsychologist) {
            // Case 1: Radio button selected
            const psychologistCard = selectedPsychologist.closest('label').querySelector('h4');
            document.getElementById('summary-psychologist').textContent = psychologistCard ? psychologistCard.textContent : 'Selected';
        } else if (hiddenPsychologist) {
            // Case 2: Pre-selected psychologist (hidden input)
            const psychologistName = document.querySelector('.bg-blue-50 h4');
            document.getElementById('summary-psychologist').textContent = psychologistName ? psychologistName.textContent : 'Pre-selected';
        } else {
            // Case 3: No psychologist selected
            document.getElementById('summary-psychologist').textContent = 'Not selected';
        }

        // Update date
        document.getElementById('summary-date').textContent = dateInput.value || 'Not selected';

        // Update time
        const timeOption = timeSelect.options[timeSelect.selectedIndex];
        document.getElementById('summary-time').textContent = timeOption ? timeOption.textContent : 'Not selected';

        // Update duration
        const durationOption = durationSelect.options[durationSelect.selectedIndex];
        document.getElementById('summary-duration').textContent = durationOption ? durationOption.textContent : '60 minutes';
    }

    // Add event listeners for radio buttons (only if they exist)
    psychologistRadios.forEach(radio => {
        radio.addEventListener('change', updateSummary);
        
        // Add selected state styling
        radio.addEventListener('change', function() {
            // Remove selected class from all cards
            document.querySelectorAll('.psychologist-card').forEach(card => {
                card.classList.remove('border-blue-500', 'bg-blue-100', 'ring-2', 'ring-blue-200');
                card.classList.add('border-gray-200');
            });
            
            // Add selected class to current card
            if (this.checked) {
                const card = this.closest('label').querySelector('.psychologist-card');
                card.classList.remove('border-gray-200');
                card.classList.add('border-blue-500', 'bg-blue-100', 'ring-2', 'ring-blue-200');
            }
        });
    });

    // Add event listeners for other form elements
    if (dateInput) dateInput.addEventListener('change', updateSummary);
    if (timeSelect) timeSelect.addEventListener('change', updateSummary);
    if (durationSelect) durationSelect.addEventListener('change', updateSummary);

    // Initial update
    updateSummary();
});
</script>
@endsection
