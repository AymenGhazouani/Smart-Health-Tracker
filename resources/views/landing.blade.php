@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-gray-600 mt-2">Track your health journey with Smart Health Tracker</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <div class="text-blue-800 font-semibold">Health Score</div>
                            <div class="text-2xl font-bold text-blue-600">85%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Daily Steps</p>
                            <p class="text-2xl font-semibold text-gray-900">8,432</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Heart Rate</p>
                            <p class="text-2xl font-semibold text-gray-900">72 BPM</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Calories</p>
                            <p class="text-2xl font-semibold text-gray-900">1,847</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Sleep Hours</p>
                            <p class="text-2xl font-semibold text-gray-900">7.5h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Nutrition & Meal Planning -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Nutrition & Meal Planning</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Track your daily nutrition intake and plan healthy meals tailored to your goals.</p>
                    <div class="space-y-2">
                        <a href="{{ route('client.meals.index') }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block text-center">
                            My Meal Plans
                        </a>
                        <a href="{{ route('client.meals.create') }}" class="w-full bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block text-center">
                            Create New Meal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Health Metrics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Health Metrics</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Monitor vital signs, weight, blood pressure, and other important health indicators.</p>
                    <a href="{{ route('metrics.dashboard') }}" class="w-full inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        View Metrics
                    </a>
                </div>
            </div>

            <!-- Telemedicine -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Telemedicine</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Connect with healthcare professionals through secure video consultations.</p>
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        Book Consultation
                    </button>
                </div>
            </div>

            <!-- Psychology Visits -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-pink-100 text-pink-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Psychology Visits</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Access mental health support and schedule sessions with licensed therapists.</p>
                    @auth
                        <a href="{{ route('psychology.dashboard') }}" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out inline-block text-center">
                            Schedule Session
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out inline-block text-center">
                            Login to Schedule Session
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Doctor Recommendations -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Doctor Recommendations</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Find and connect with healthcare providers based on your location and needs.</p>
                     <a href="{{ route('doctors.index') }}" class="w-full inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150 ease-in-out">
                Find Doctors
            </a>
                </div>
            </div>

            <!-- Appointment Booking -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-teal-100 text-teal-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Book Appointment</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Schedule appointments with healthcare providers and manage your upcoming visits.</p>
                    <div class="space-y-2">
                        <a href="{{ route('appointments.create') }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block text-center">
                            Book New Appointment
                        </a>
                        <a href="{{ route('appointments.index') }}" class="w-full bg-teal-100 hover:bg-teal-200 text-teal-700 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block text-center">
                            My Appointments
                        </a>
                    </div>
                </div>
            </div>

            <!-- Symptom Checker -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Symptom Checker</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Analyze your symptoms and get preliminary health assessments with AI assistance.</p>
                    <div class="space-y-2">
                        <a href="{{ route('symptom-checker') }}" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block text-center">
                            Check Symptoms
                        </a>
                        <a href="{{ route('symptom-history') }}" class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block text-center">
                            Symptom History
                        </a>
                    </div>
                </div>
            </div>

            <!-- Health Insights -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Health Insights</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Get personalized health recommendations based on your data and trends.</p>
                    <a href="{{ route('health-ai.dashboard') }}" class="w-full inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150 ease-in-out">
                        View AI Insights
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection