@extends('layouts.admin')

@section('page-title', 'Edit Psychologist')

@section('admin-content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Psychologist</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Update psychologist information</p>
        </div>

        <form action="{{ route('admin.psychologists.update', $psychologist->id) }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $psychologist->name) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $psychologist->email) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $psychologist->phone) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('phone') border-red-300 @enderror">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Specialty -->
                <div>
                    <label for="specialty" class="block text-sm font-medium text-gray-700">Specialty *</label>
                    <select name="specialty" id="specialty" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('specialty') border-red-300 @enderror">
                        <option value="">Select a specialty</option>
                        <option value="Anxiety" {{ old('specialty', $psychologist->specialty) == 'Anxiety' ? 'selected' : '' }}>Anxiety</option>
                        <option value="Depression" {{ old('specialty', $psychologist->specialty) == 'Depression' ? 'selected' : '' }}>Depression</option>
                        <option value="Trauma" {{ old('specialty', $psychologist->specialty) == 'Trauma' ? 'selected' : '' }}>Trauma</option>
                        <option value="Relationship Counseling" {{ old('specialty', $psychologist->specialty) == 'Relationship Counseling' ? 'selected' : '' }}>Relationship Counseling</option>
                        <option value="Family Therapy" {{ old('specialty', $psychologist->specialty) == 'Family Therapy' ? 'selected' : '' }}>Family Therapy</option>
                        <option value="Child Psychology" {{ old('specialty', $psychologist->specialty) == 'Child Psychology' ? 'selected' : '' }}>Child Psychology</option>
                        <option value="Addiction Counseling" {{ old('specialty', $psychologist->specialty) == 'Addiction Counseling' ? 'selected' : '' }}>Addiction Counseling</option>
                        <option value="Cognitive Behavioral Therapy" {{ old('specialty', $psychologist->specialty) == 'Cognitive Behavioral Therapy' ? 'selected' : '' }}>Cognitive Behavioral Therapy</option>
                        <option value="Other" {{ old('specialty', $psychologist->specialty) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('specialty')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hourly Rate -->
                <div>
                    <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Hourly Rate ($)</label>
                    <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $psychologist->hourly_rate) }}" step="0.01" min="0"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('hourly_rate') border-red-300 @enderror">
                    @error('hourly_rate')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="sm:col-span-2">
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                    <textarea name="bio" id="bio" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('bio') border-red-300 @enderror">{{ old('bio', $psychologist->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Availability -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Availability Schedule</label>
                    <p class="mt-1 text-sm text-gray-500">Set the weekly availability schedule</p>
                    
                    <div class="mt-4 space-y-4">
                        @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $availability = old('availability', $psychologist->availability ?? []);
                        @endphp
                        
                        @foreach($days as $day)
                            <div class="flex items-center space-x-4">
                                <div class="w-24">
                                    <label class="block text-sm font-medium text-gray-700 capitalize">{{ $day }}</label>
                                </div>
                                <div class="flex-1 flex space-x-2">
                                    <input type="time" name="availability[{{ $day }}][0][start]" 
                                           value="{{ $availability[$day][0]['start'] ?? '09:00' }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error("availability.{$day}.0.start") border-red-300 @enderror">
                                    <span class="flex items-center text-gray-500">to</span>
                                    <input type="time" name="availability[{{ $day }}][0][end]" 
                                           value="{{ $availability[$day][0]['end'] ?? '17:00' }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error("availability.{$day}.0.end") border-red-300 @enderror">
                                </div>
                                @error("availability.{$day}.0.start")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                @error("availability.{$day}.0.end")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                    @error('availability')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="sm:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $psychologist->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active (available for bookings)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.psychologists.show', $psychologist->id) }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Psychologist
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

