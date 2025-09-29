@extends('layouts.admin')

@section('page-title', 'Meal Details')

@section('admin-content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $meal->name }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Meal ID: #{{ $meal->id }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.meals.edit', $meal->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.meals.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Meals
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Meal Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Meal Information</h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Meal Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ $meal->name }}</dd>
                        </div>
                        @if($meal->description)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $meal->description }}</dd>
                        </div>
                        @endif
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Number of Foods</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $meal->foods->count() }} foods
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Foods in Meal -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Foods in this Meal</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">All foods included with their quantities and nutritional values</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Calories</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Protein</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carbs</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fat</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($meal->foods as $food)
                                @php
                                    $quantity = $food->pivot->quantity;
                                    $totalCalories = $food->calories * $quantity;
                                    $totalProtein = $food->protein * $quantity;
                                    $totalCarbs = $food->carbs * $quantity;
                                    $totalFat = $food->fat * $quantity;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $food->name }}</div>
                                        <div class="text-sm text-gray-500">Per 100g: {{ $food->calories }}cal</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $quantity }}x
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($totalCalories) }} cal
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($totalProtein, 1) }}g
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($totalCarbs, 1) }}g
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($totalFat, 1) }}g
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Total Nutritional Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Total Nutrition</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @php $macros = $meal->total_macros; @endphp
                    <div class="space-y-4">
                        <div class="text-center bg-yellow-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-yellow-600">{{ number_format($macros['calories']) }}</div>
                            <div class="text-sm text-gray-500">Total Calories</div>
                        </div>
                        <div class="text-center bg-blue-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($macros['protein'], 1) }}g</div>
                            <div class="text-sm text-gray-500">Total Protein</div>
                        </div>
                        <div class="text-center bg-green-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($macros['carbs'], 1) }}g</div>
                            <div class="text-sm text-gray-500">Total Carbs</div>
                        </div>
                        <div class="text-center bg-red-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-red-600">{{ number_format($macros['fat'], 1) }}g</div>
                            <div class="text-sm text-gray-500">Total Fat</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Macronutrient Breakdown -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Macro Breakdown</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @php
                        $totalMacros = $macros['protein'] + $macros['carbs'] + $macros['fat'];
                        $proteinPercent = $totalMacros > 0 ? ($macros['protein'] / $totalMacros) * 100 : 0;
                        $carbsPercent = $totalMacros > 0 ? ($macros['carbs'] / $totalMacros) * 100 : 0;
                        $fatPercent = $totalMacros > 0 ? ($macros['fat'] / $totalMacros) * 100 : 0;
                    @endphp
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-blue-600 font-medium">Protein</span>
                                <span class="text-gray-900">{{ number_format($proteinPercent, 1) }}%</span>
                            </div>
                            <div class="mt-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $proteinPercent }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-green-600 font-medium">Carbohydrates</span>
                                <span class="text-gray-900">{{ number_format($carbsPercent, 1) }}%</span>
                            </div>
                            <div class="mt-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $carbsPercent }}%"></div>
                            </div>
                        </div>

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

            <!-- Meal Metadata -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Meal Information</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <div class="font-medium">{{ $meal->created_at->format('M d, Y') }}</div>
                            <div class="text-gray-400">{{ $meal->created_at->format('g:i A') }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <div class="font-medium">{{ $meal->updated_at->format('M d, Y') }}</div>
                            <div class="text-gray-400">{{ $meal->updated_at->format('g:i A') }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Time Ago:</span>
                            <div class="font-medium">{{ $meal->updated_at->diffForHumans() }}</div>
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
                <a href="{{ route('admin.meals.edit', $meal->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Meal
                </a>
                
                <form id="delete-form-{{ $meal->id }}" action="{{ route('admin.meals.destroy', $meal->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete({{ $meal->id }}, '{{ $meal->name }}')" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Meal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection