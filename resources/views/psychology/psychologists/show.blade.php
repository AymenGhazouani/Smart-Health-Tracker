@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('psychology.psychologists') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $psychologist->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $psychologist->specialty }}</p>
                    </div>
                </div>
                <a href="{{ route('psychology.book-session', ['psychologist' => $psychologist->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Book Session
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $psychologist->name }}</h3>
                                <p class="text-lg text-gray-600">{{ $psychologist->specialty }}</p>
                                @if($psychologist->hourly_rate)
                                    <p class="text-sm text-gray-500">${{ number_format($psychologist->hourly_rate, 2) }}/hour</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($psychologist->bio)
                        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">About</h4>
                            <p class="text-gray-600 leading-relaxed">{{ $psychologist->bio }}</p>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Contact Information</h4>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $psychologist->email }}
                            </div>
                            @if($psychologist->phone)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $psychologist->phone }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Availability -->
                @if($psychologist->availability)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Availability</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Available time slots for booking</p>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($psychologist->availability as $day => $times)
                                    @if(!empty($times))
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <h4 class="font-medium text-gray-900 mb-2">{{ ucfirst($day) }}</h4>
                                            <div class="space-y-1">
                                                @foreach($times as $timeSlot)
                                                    <span class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded mr-1 mb-1">
                                                        {{ $timeSlot['start'] }} - {{ $timeSlot['end'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Book Session Card -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Book a Session</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Schedule your appointment</p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            @if($psychologist->hourly_rate)
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">${{ number_format($psychologist->hourly_rate, 2) }}</div>
                                    <div class="text-sm text-gray-500">per hour</div>
                                </div>
                            @endif
                            
                            <a href="{{ route('psychology.book-session', ['psychologist' => $psychologist->id]) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-150 ease-in-out flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Book Session
                            </a>
                            
                            <p class="text-xs text-gray-500 text-center">
                                Sessions are typically 50-60 minutes long
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Info</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Specialty</dt>
                                <dd class="text-sm text-gray-900">{{ $psychologist->specialty }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $psychologist->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $psychologist->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>
                            @if($psychologist->hourly_rate)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Rate</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($psychologist->hourly_rate, 2) }}/hour</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Back to List -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <a href="{{ route('psychology.psychologists') }}" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Psychologists
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
