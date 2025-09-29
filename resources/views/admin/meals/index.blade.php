@extends('layouts.admin')

@section('page-title', 'Meal Management')

@section('admin-content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Meal Plans</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage all meal plans and their nutritional information</p>
        </div>
        <a href="{{ route('admin.meals.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Meal
        </a>
    </div>

    @if($meals->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @foreach($meals as $meal)
                @php
                    $macros = $meal->total_macros;
                @endphp
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <!-- Meal Header -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 truncate">{{ $meal->name }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $meal->foods->count() }} foods
                            </span>
                        </div>
                        
                        @if($meal->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $meal->description }}</p>
                        @endif

                        <!-- Macro Summary -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="text-center bg-yellow-50 rounded-lg p-3">
                                <div class="text-lg font-bold text-yellow-600">{{ number_format($macros['calories']) }}</div>
                                <div class="text-xs text-gray-500">Calories</div>
                            </div>
                            <div class="text-center bg-blue-50 rounded-lg p-3">
                                <div class="text-lg font-bold text-blue-600">{{ number_format($macros['protein'], 1) }}g</div>
                                <div class="text-xs text-gray-500">Protein</div>
                            </div>
                            <div class="text-center bg-green-50 rounded-lg p-3">
                                <div class="text-lg font-bold text-green-600">{{ number_format($macros['carbs'], 1) }}g</div>
                                <div class="text-xs text-gray-500">Carbs</div>
                            </div>
                            <div class="text-center bg-red-50 rounded-lg p-3">
                                <div class="text-lg font-bold text-red-600">{{ number_format($macros['fat'], 1) }}g</div>
                                <div class="text-xs text-gray-500">Fat</div>
                            </div>
                        </div>

                        <!-- Foods List -->
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Foods in this meal:</h5>
                            <div class="space-y-1">
                                @foreach($meal->foods->take(3) as $food)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ $food->name }}</span>
                                        <span class="text-gray-500">{{ $food->pivot->quantity }}x</span>
                                    </div>
                                @endforeach
                                @if($meal->foods->count() > 3)
                                    <div class="text-sm text-gray-500">
                                        +{{ $meal->foods->count() - 3 }} more foods
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.meals.show', $meal->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                View
                            </a>
                            <a href="{{ route('admin.meals.edit', $meal->id) }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-3 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                Edit
                            </a>
                            <form id="delete-form-{{ $meal->id }}" action="{{ route('admin.meals.destroy', $meal->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $meal->id }}, '{{ $meal->name }}')" class="bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Meal Footer -->
                    <div class="bg-gray-50 px-6 py-3 text-xs text-gray-500">
                        Created {{ $meal->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No meals created</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first meal plan.</p>
            <div class="mt-6">
                <a href="{{ route('admin.meals.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Meal Plan
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Stats Cards -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Meals</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $meals->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Avg Calories</dt>
                        <dd class="text-lg font-medium text-gray-900">
                            @if($meals->count() > 0)
                                {{ number_format($meals->avg(function($meal) { return $meal->total_macros['calories']; })) }}
                            @else
                                0
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">High Protein</dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ $meals->filter(function($meal) { return $meal->total_macros['protein'] > 20; })->count() }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Balanced Meals</dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ $meals->filter(function($meal) { 
                                $macros = $meal->total_macros;
                                return $macros['protein'] > 10 && $macros['carbs'] > 10 && $macros['fat'] > 5;
                            })->count() }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection