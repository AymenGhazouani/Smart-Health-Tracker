@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
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

                    <!-- Healthcare Providers Section -->
                    <div class="space-y-1">
                        <button onclick="toggleProvidersSection()" class="w-full text-left text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="text-gray-400 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Healthcare Providers
                            <svg id="providers-arrow" class="ml-auto h-5 w-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div id="providers-submenu" class="pl-8 space-y-1">
                            <a href="{{ route('providers.index') }}" class="text-gray-500 hover:bg-gray-50 hover:text-gray-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <svg class="text-gray-400 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                Providers
                            </a>

                            <a href="{{ route('availability-slots.index') }}" class="bg-green-100 text-green-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <svg class="text-green-500 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Availability Slots
                            </a>

                            <a href="{{ route('appointments.index') }}" class="text-gray-500 hover:bg-gray-50 hover:text-gray-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <svg class="text-gray-400 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Appointments
                            </a>
                        </div>
                    </div>

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
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Availability Slots Management</h1>
                        <p class="text-gray-600">Manage provider availability schedules and time slots</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('availability-slots.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            ➕ Add New Slot
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-600 text-white rounded-lg shadow-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-2xl font-bold">{{ $slots->count() }}</h4>
                                    <p class="text-blue-100">Total Slots</p>
                                </div>
                                <div class="text-blue-200">
                                    📅
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-600 text-white rounded-lg shadow-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-2xl font-bold">{{ $slots->where('is_booked', false)->count() }}</h4>
                                    <p class="text-green-100">Available Slots</p>
                                </div>
                                <div class="text-green-200">
                                    ✅
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-600 text-white rounded-lg shadow-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-2xl font-bold">{{ $slots->where('is_booked', true)->count() }}</h4>
                                    <p class="text-red-100">Booked Slots</p>
                                </div>
                                <div class="text-red-200">
                                    ❌
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-600 text-white rounded-lg shadow-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-2xl font-bold">{{ $providers->count() }}</h4>
                                    <p class="text-purple-100">Active Providers</p>
                                </div>
                                <div class="text-purple-200">
                                    👨‍⚕️
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">🔍 Filters</h3>
                    </div>
                    <div class="p-6">
                        <form method="GET" action="{{ route('availability-slots.index') }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                                    <select name="provider_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">All Providers</option>
                                        @foreach($providers as $provider)
                                            <option value="{{ $provider->id }}" {{ $provider_id == $provider->id ? 'selected' : '' }}>
                                                {{ $provider->user->name ?? 'N/A' }} - {{ $provider->specialty }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">All Status</option>
                                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                                    <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ request('date_from') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                                    <div class="flex space-x-2">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                            🔍 Filter
                                        </button>
                                        <a href="{{ route('availability-slots.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                            ❌ Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Availability Slots Table -->
                <div class="bg-white shadow rounded-lg">
                    @if($slots->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialty</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($slots as $slot)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full" 
                                                             src="{{ $slot->provider->profile_image ? asset('storage/' . $slot->provider->profile_image) : '/images/default-avatar.svg' }}" 
                                                             alt="Avatar">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $slot->provider->user->name ?? 'N/A' }}</div>
                                                        <div class="text-sm text-gray-500">{{ $slot->provider->user->email ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $slot->provider->specialty ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $slot->start_time->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $slot->start_time->format('H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $slot->end_time->format('H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $slot->start_time->diffInMinutes($slot->end_time) }} min</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($slot->is_booked)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Booked</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('availability-slots.edit', $slot) }}" class="text-indigo-600 hover:text-indigo-900">✏️</a>
                                                    <form action="{{ route('availability-slots.destroy', $slot) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                                onclick="return confirm('Are you sure you want to delete this slot?')">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No availability slots found</h3>
                                <p class="mt-1 text-sm text-gray-500">Create your first availability slot to get started.</p>
                                <div class="mt-6">
                                    <a href="{{ route('availability-slots.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                        ➕ Create First Slot
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle providers section
function toggleProvidersSection() {
    const submenu = document.getElementById('providers-submenu');
    const arrow = document.getElementById('providers-arrow');
    
    if (submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        submenu.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Auto-expand providers submenu since we're on the availability slots page
document.addEventListener('DOMContentLoaded', function() {
    // The submenu is already expanded in the HTML, no need to toggle
});
</script>
@endpush