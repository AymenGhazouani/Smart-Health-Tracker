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
                            <a href="{{ route('providers.index') }}" class="bg-blue-100 text-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <svg class="text-blue-500 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                Providers
                            </a>

                            <a href="{{ route('availability-slots.index') }}" class="text-gray-500 hover:bg-gray-50 hover:text-gray-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <svg class="text-gray-400 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <h1 class="text-2xl font-bold text-gray-900">Providers Management</h1>
                        <p class="text-gray-600">Manage healthcare providers with advanced filtering and analytics</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out" onclick="showAnalytics()">
                            📊 Analytics
                        </button>
                        <div class="relative inline-block text-left" x-data="{ open: false }">
                            <button @click="open = !open" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                📥 Export
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="#" onclick="exportData('excel')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📊 Excel (.xlsx)</a>
                                    <a href="#" onclick="exportData('pdf')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📄 PDF (.pdf)</a>
                                    <a href="#" onclick="exportData('csv')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📋 CSV (.csv)</a>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('providers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            ➕ Add Provider
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
                                    <h4 class="text-2xl font-bold" id="total-providers">{{ $stats['total'] ?? 0 }}</h4>
                                    <p class="text-blue-100">Total Providers</p>
                                </div>
                                <div class="text-blue-200">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-600 text-white rounded-lg shadow-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-2xl font-bold" id="active-providers">{{ $stats['active'] ?? 0 }}</h4>
                                    <p class="text-green-100">Active Providers</p>
                                </div>
                                <div class="text-green-200">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-600 text-white rounded-lg shadow-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-2xl font-bold" id="inactive-providers">{{ $stats['inactive'] ?? 0 }}</h4>
                                    <p class="text-yellow-100">Inactive Providers</p>
                                </div>
                                <div class="text-yellow-200">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-600 text-white rounded-lg shadow-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-2xl font-bold" id="providers-with-appointments">{{ $stats['with_appointments'] ?? 0 }}</h4>
                                    <p class="text-purple-100">With Appointments</p>
                                </div>
                                <div class="text-purple-200">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">🔍 Advanced Filters</h3>
                            <button class="text-sm text-gray-500 hover:text-gray-700" onclick="clearFilters()">
                                Clear All Filters
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="provider-search" name="search" placeholder="Search providers...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Specialty</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="specialty">
                                    <option value="">All Specialties</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty }}">{{ $specialty }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="status">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Rate ($)</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       name="min_rate" placeholder="0" min="0" step="0.01">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Rate ($)</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       name="max_rate" placeholder="1000" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Has Appointments</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="has_appointments">
                                    <option value="">All</option>
                                    <option value="yes">With Appointments</option>
                                    <option value="no">Without Appointments</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created From</label>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="created_from">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created To</label>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="created_to">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="sort_by">
                                    <option value="">Default</option>
                                    <option value="name">Name</option>
                                    <option value="specialty">Specialty</option>
                                    <option value="hourly_rate">Hourly Rate</option>
                                    <option value="created_at">Registration Date</option>
                                    <option value="appointments_count">Appointments Count</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="select-all-providers" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="select-all-providers" class="ml-2 text-sm text-gray-700">Select All</label>
                                </div>
                                <select id="bulk-action-select" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Choose Action</option>
                                    <option value="activate">Activate</option>
                                    <option value="deactivate">Deactivate</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button id="bulk-action-btn" disabled class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                    Bulk Actions
                                </button>
                            </div>
                            <div id="loading-indicator" class="hidden">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Providers Table -->
                <div class="bg-white shadow rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hourly Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointments</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($providers as $provider)
                                    <tr class="hover:bg-gray-50" data-provider-id="{{ $provider->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" class="provider-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="{{ $provider->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" 
                                                         src="{{ $provider->profile_image ? asset('storage/' . $provider->profile_image) : '/images/default-avatar.svg' }}" 
                                                         alt="Avatar">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $provider->user->name ?? 'N/A' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $provider->user->email ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $provider->specialty }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($provider->hourly_rate)
                                                ${{ number_format($provider->hourly_rate, 2) }}
                                            @else
                                                <span class="text-gray-400">Not set</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($provider->is_active)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $provider->appointments->count() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $provider->created_at->format('M j, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('providers.show', $provider->id) }}" class="text-blue-600 hover:text-blue-900">👁️</a>
                                                <a href="{{ route('providers.edit', $provider->id) }}" class="text-indigo-600 hover:text-indigo-900">✏️</a>
                                                <button class="text-{{ $provider->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $provider->is_active ? 'yellow' : 'green' }}-900 status-toggle" 
                                                        data-provider-id="{{ $provider->id }}">
                                                    {{ $provider->is_active ? '⏸️' : '▶️' }}
                                                </button>
                                                <form action="{{ route('providers.destroy', $provider->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this provider?')">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">No providers found</h3>
                                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new provider.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if(method_exists($providers, 'links'))
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $providers->links() }}
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

// Export functionality
function exportData(format) {
    const params = new URLSearchParams(window.location.search);
    window.open(`/providers/export/${format}?${params}`, '_blank');
}

// Show analytics
function showAnalytics() {
    fetch('/providers/analytics')
        .then(response => response.json())
        .then(data => {
            alert('Analytics data loaded! Check console for details.');
            console.log('Provider Analytics:', data);
        })
        .catch(error => {
            console.error('Error loading analytics:', error);
            alert('Error loading analytics data.');
        });
}

// Clear filters
function clearFilters() {
    document.querySelectorAll('input, select').forEach(element => {
        if (element.type === 'checkbox') {
            element.checked = false;
        } else {
            element.value = '';
        }
    });
    window.location.href = window.location.pathname;
}

// Status toggle
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('status-toggle')) {
        const providerId = e.target.dataset.providerId;
        
        fetch(`/providers/${providerId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating provider status.');
        });
    }
});

// Bulk actions
document.getElementById('select-all-providers').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.provider-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActionButton();
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('provider-checkbox')) {
        updateBulkActionButton();
    }
});

function updateBulkActionButton() {
    const checkedBoxes = document.querySelectorAll('.provider-checkbox:checked');
    const bulkButton = document.getElementById('bulk-action-btn');
    
    if (checkedBoxes.length > 0) {
        bulkButton.disabled = false;
        bulkButton.textContent = `Bulk Actions (${checkedBoxes.length})`;
    } else {
        bulkButton.disabled = true;
        bulkButton.textContent = 'Bulk Actions';
    }
}

document.getElementById('bulk-action-btn').addEventListener('click', function() {
    const action = document.getElementById('bulk-action-select').value;
    const checkedBoxes = document.querySelectorAll('.provider-checkbox:checked');
    
    if (!action || checkedBoxes.length === 0) {
        alert('Please select an action and at least one provider.');
        return;
    }
    
    if (!confirm(`Are you sure you want to ${action} ${checkedBoxes.length} provider(s)?`)) {
        return;
    }
    
    const providerIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    fetch('/providers/bulk-action', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: action,
            provider_ids: providerIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error performing bulk action.');
    });
});

// Auto-expand providers submenu since we're on the providers page
document.addEventListener('DOMContentLoaded', function() {
    // The submenu is already expanded in the HTML, no need to toggle
});
</script>
@endpush
