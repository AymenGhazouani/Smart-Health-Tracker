@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar (same as other views) -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow pt-5 overflow-y-auto bg-white border-r">
                <div class="flex items-center flex-shrink-0 px-4">
                    <h2 class="text-lg font-semibold text-gray-900">Admin Panel</h2>
                </div>
                <div class="mt-5 flex-grow flex flex-col">
                    <nav class="flex-1 px-2 pb-4 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="text-gray-400 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('providers.index') }}" class="bg-blue-100 text-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="text-blue-500 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Providers
                        </a>

                        <a href="{{ route('admin.foods.index') }}" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="text-gray-400 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Foods
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900">Edit Provider</h1>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                        <form action="{{ route('providers.update', $provider->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- User info (read-only) -->
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Associated User</label>
                                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                        <p>{{ $provider->user->name ?? 'N/A' }} ({{ $provider->user->email ?? 'N/A' }})</p>
                                    </div>
                                    <input type="hidden" name="user_id" value="{{ $provider->user_id }}">
                                </div>

                                <!-- Specialty Field -->
                                <div class="col-span-1">
                                    <label for="specialty" class="block text-sm font-medium text-gray-700">Specialty</label>
                                    <input type="text" name="specialty" id="specialty" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('specialty') border-red-500 @enderror" value="{{ old('specialty', $provider->specialty) }}">
                                    @error('specialty')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Hourly Rate Field -->
                                <div class="col-span-1">
                                    <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Hourly Rate ($)</label>
                                    <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('hourly_rate') border-red-500 @enderror" value="{{ old('hourly_rate', $provider->hourly_rate) }}">
                                    @error('hourly_rate')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status Field -->
                                <div class="col-span-1">
                                    <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="is_active" id="is_active" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="1" {{ old('is_active', $provider->is_active) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $provider->is_active) ? '' : 'selected' }}>Inactive</option>
                                    </select>
                                </div>

                                <!-- Profile Image Field -->
                                <div class="col-span-2">
                                    <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>

                                    @if($provider->profile_image)
                                        <div class="mt-2 mb-2">
                                            <img src="{{ asset('storage/' . $provider->profile_image) }}" alt="Current Profile Image" class="h-32 w-32 rounded-full object-cover">
                                            <p class="text-xs text-gray-500 mt-1">Current profile image</p>
                                        </div>
                                    @endif

                                    <input type="file" name="profile_image" id="profile_image" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 @error('profile_image') border-red-500 @enderror">
                                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep the current image</p>
                                    @error('profile_image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bio Field -->
                                <div class="col-span-2">
                                    <label for="bio" class="block text-sm font-medium text-gray-700">Biography</label>
                                    <textarea name="bio" id="bio" rows="4" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('bio') border-red-500 @enderror">{{ old('bio', $provider->bio) }}</textarea>
                                    @error('bio')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end">
                                <a href="{{ route('providers.index') }}" class="bg-gray-200 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update Provider
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
