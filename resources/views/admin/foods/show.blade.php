@extends('layouts.admin')

@section('page-title', 'Food Details')

@section('admin-content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $food->name }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Food ID: #{{ $food->id }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.foods.edit', $food->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.foods.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Nutritional Information -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Nutritional Information</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Per 100g serving</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Food Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ $food->name }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Calories</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    {{ $food->calories }} cal
                                </span>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Protein</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ number_format($food->protein, 1) }}g
                                </span>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Carbohydrates</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ number_format($food->carbs, 1) }}g
                                </span>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Fat</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    {{ number_format($food->fat, 1) }}g
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Macronutrient Breakdown -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Macronutrient Breakdown</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @php
                        $totalMacros = $food->protein + $food->carbs + $food->fat;
                        $proteinPercent = $totalMacros > 0 ? ($food->protein / $totalMacros) * 100 : 0;
                        $carbsPercent = $totalMacros > 0 ? ($food->carbs / $totalMacros) * 100 : 0;
                        $fatPercent = $totalMacros > 0 ? ($food->fat / $totalMacros) * 100 : 0;
                    @endphp
                    
                    <div class="space-y-4">
                        <!-- Protein -->
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-blue-600 font-medium">Protein</span>
                                <span class="text-gray-900">{{ number_format($proteinPercent, 1) }}%</span>
                            </div>
                            <div class="mt-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $proteinPercent }}%"></div>
                            </div>
                        </div>

                        <!-- Carbs -->
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-green-600 font-medium">Carbohydrates</span>
                                <span class="text-gray-900">{{ number_format($carbsPercent, 1) }}%</span>
                            </div>
                            <div class="mt-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $carbsPercent }}%"></div>
                            </div>
                        </div>

                        <!-- Fat -->
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-red-600 font-medium">Fat</span>
                                <span class="text-gray-900">{{ number_format($fatPercent, 1) }}%</span>
                            </div>
                            <div class="mt-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full" style="width: {{ $fatPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calorie Breakdown -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Calorie Breakdown</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @php
                        $proteinCals = $food->protein * 4;
                        $carbsCals = $food->carbs * 4;
                        $fatCals = $food->fat * 9;
                        $totalCalculatedCals = $proteinCals + $carbsCals + $fatCals;
                    @endphp
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-600">From Protein:</span>
                            <span class="font-medium">{{ number_format($proteinCals) }} cal</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-green-600">From Carbs:</span>
                            <span class="font-medium">{{ number_format($carbsCals) }} cal</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-red-600">From Fat:</span>
                            <span class="font-medium">{{ number_format($fatCals) }} cal</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between text-sm font-semibold">
                            <span>Calculated Total:</span>
                            <span>{{ number_format($totalCalculatedCals) }} cal</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span>Listed Total:</span>
                            <span>{{ $food->calories }} cal</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Item Information</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <div class="font-medium">{{ $food->created_at->format('M d, Y') }}</div>
                            <div class="text-gray-400">{{ $food->created_at->format('g:i A') }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <div class="font-medium">{{ $food->updated_at->format('M d, Y') }}</div>
                            <div class="text-gray-400">{{ $food->updated_at->format('g:i A') }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Time Ago:</span>
                            <div class="font-medium">{{ $food->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Actions</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.foods.edit', $food->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Food Item
                </a>
                
                <form id="delete-form-{{ $food->id }}" action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete({{ $food->id }}, '{{ $food->name }}')" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Food Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection