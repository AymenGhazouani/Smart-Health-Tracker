@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Find a Psychologist</h1>
                    <p class="mt-1 text-sm text-gray-600">Browse available psychologists and book your session</p>
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

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Filter Psychologists</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Find the right psychologist for your needs</p>
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                <form method="GET" action="{{ route('psychology.psychologists') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="specialty" class="block text-sm font-medium text-gray-700">Specialty</label>
                        <select name="specialty" id="specialty" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Specialties</option>
                            <option value="Anxiety Disorders" {{ request('specialty') == 'Anxiety Disorders' ? 'selected' : '' }}>Anxiety Disorders</option>
                            <option value="Depression & Trauma" {{ request('specialty') == 'Depression & Trauma' ? 'selected' : '' }}>Depression & Trauma</option>
                            <option value="Relationship Counseling" {{ request('specialty') == 'Relationship Counseling' ? 'selected' : '' }}>Relationship Counseling</option>
                            <option value="Cognitive Behavioral Therapy" {{ request('specialty') == 'Cognitive Behavioral Therapy' ? 'selected' : '' }}>Cognitive Behavioral Therapy</option>
                            <option value="Family Therapy" {{ request('specialty') == 'Family Therapy' ? 'selected' : '' }}>Family Therapy</option>
                            <option value="Addiction Counseling" {{ request('specialty') == 'Addiction Counseling' ? 'selected' : '' }}>Addiction Counseling</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="availability" class="block text-sm font-medium text-gray-700">Available</label>
                        <select name="availability" id="availability" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Any Time</option>
                            <option value="today" {{ request('availability') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="tomorrow" {{ request('availability') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                            <option value="this_week" {{ request('availability') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="next_week" {{ request('availability') == 'next_week' ? 'selected' : '' }}>Next Week</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="price_range" class="block text-sm font-medium text-gray-700">Price Range</label>
                        <select name="price_range" id="price_range" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Any Price</option>
                            <option value="0-50" {{ request('price_range') == '0-50' ? 'selected' : '' }}>$0 - $50</option>
                            <option value="50-100" {{ request('price_range') == '50-100' ? 'selected' : '' }}>$50 - $100</option>
                            <option value="100-150" {{ request('price_range') == '100-150' ? 'selected' : '' }}>$100 - $150</option>
                            <option value="150+" {{ request('price_range') == '150+' ? 'selected' : '' }}>$150+</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Psychologists Grid -->
        @if($psychologists->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($psychologists as $psychologist)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $psychologist->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $psychologist->specialty }}</p>
                                </div>
                            </div>

                            @if($psychologist->bio)
                                <p class="text-sm text-gray-600 mb-4">{{ str_limit($psychologist->bio, 120) }}</p>
                            @endif

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $psychologist->email }}
                                </div>
                                @if($psychologist->phone)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $psychologist->phone }}
                                    </div>
                                @endif
                                @if($psychologist->hourly_rate)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        ${{ number_format($psychologist->hourly_rate, 2) }}/hour
                                    </div>
                                @endif
                            </div>

                            <!-- Availability Preview -->
                            @if($psychologist->availability)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Available Days</h4>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($psychologist->availability as $day => $times)
                                            @if(!empty($times))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ ucfirst($day) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex space-x-2">
                                <a href="{{ route('psychology.psychologists.show', $psychologist->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                    View Profile
                                </a>
                                <a href="{{ route('psychology.book-session', ['psychologist' => $psychologist->id]) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                    Book Session
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($psychologists->hasPages())
                <div class="mt-8">
                    {{ $psychologists->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No psychologists found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or check back later.</p>
                <div class="mt-6">
                    <a href="{{ route('psychology.psychologists') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Clear Filters
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
