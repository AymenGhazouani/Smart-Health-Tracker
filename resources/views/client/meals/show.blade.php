@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $meal->name }}</h1>
                        <p class="text-gray-600 mt-2">{{ $meal->description ?? 'Your personalized meal plan' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('client.meals.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Create New Meal
                        </a>
                        <a href="{{ route('client.meals.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Back to My Meals
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Foods in Meal -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Foods in Your Meal</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $meal->foods->count() }} foods selected</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($meal->foods as $food)
                            @php
                                $quantity = $food->pivot->quantity;
                                $totalCalories = $food->calories * $quantity;
                                $totalProtein = $food->protein * $quantity;
                                $totalCarbs = $food->carbs * $quantity;
                                $totalFat = $food->fat * $quantity;
                            @endphp
                            <div class="p-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $food->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">Quantity: {{ $quantity }}x ({{ $quantity * 100 }}g)</p>
                                        
                                        <!-- Nutritional breakdown -->
                                        <div class="mt-3 grid grid-cols-4 gap-4">
                                            <div class="text-center bg-yellow-50 rounded-lg p-3">
                                                <div class="text-sm font-bold text-yellow-600">{{ number_format($totalCalories) }}</div>
                                                <div class="text-xs text-gray-500">Calories</div>
                                            </div>
                                            <div class="text-center bg-blue-50 rounded-lg p-3">
                                                <div class="text-sm font-bold text-blue-600">{{ number_format($totalProtein, 1) }}g</div>
                                                <div class="text-xs text-gray-500">Protein</div>
                                            </div>
                                            <div class="text-center bg-green-50 rounded-lg p-3">
                                                <div class="text-sm font-bold text-green-600">{{ number_format($totalCarbs, 1) }}g</div>
                                                <div class="text-xs text-gray-500">Carbs</div>
                                            </div>
                                            <div class="text-center bg-red-50 rounded-lg p-3">
                                                <div class="text-sm font-bold text-red-600">{{ number_format($totalFat, 1) }}g</div>
                                                <div class="text-xs text-gray-500">Fat</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Meal Tips -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Meal Tips</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Consume this meal within 2 hours of preparation for best nutritional value</li>
                                    <li>Drink plenty of water with your meal to aid digestion</li>
                                    <li>Consider the timing of this meal based on your workout schedule</li>
                                    @if($meal->total_macros['protein'] > 25)
                                        <li>This is a high-protein meal - great for post-workout recovery</li>
                                    @endif
                                    @if($meal->total_macros['carbs'] > 30)
                                        <li>High in carbohydrates - ideal for pre-workout energy</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Total Nutritional Information -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Total Nutrition</h3>
                    </div>
                    <div class="p-6">
                        @php $macros = $meal->total_macros; @endphp
                        <div class="space-y-4">
                            <div class="text-center bg-yellow-50 rounded-lg p-4">
                                <div class="text-3xl font-bold text-yellow-600">{{ number_format($macros['calories']) }}</div>
                                <div class="text-sm text-gray-500">Total Calories</div>
                            </div>
                            <div class="grid grid-cols-1 gap-3">
                                <div class="text-center bg-blue-50 rounded-lg p-3">
                                    <div class="text-xl font-bold text-blue-600">{{ number_format($macros['protein'], 1) }}g</div>
                                    <div class="text-xs text-gray-500">Protein</div>
                                </div>
                                <div class="text-center bg-green-50 rounded-lg p-3">
                                    <div class="text-xl font-bold text-green-600">{{ number_format($macros['carbs'], 1) }}g</div>
                                    <div class="text-xs text-gray-500">Carbohydrates</div>
                                </div>
                                <div class="text-center bg-red-50 rounded-lg p-3">
                                    <div class="text-xl font-bold text-red-600">{{ number_format($macros['fat'], 1) }}g</div>
                                    <div class="text-xs text-gray-500">Fat</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Macronutrient Distribution -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Macro Distribution</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $totalMacros = $macros['protein'] + $macros['carbs'] + $macros['fat'];
                            $proteinPercent = $totalMacros > 0 ? ($macros['protein'] / $totalMacros) * 100 : 0;
                            $carbsPercent = $totalMacros > 0 ? ($macros['carbs'] / $totalMacros) * 100 : 0;
                            $fatPercent = $totalMacros > 0 ? ($macros['fat'] / $totalMacros) * 100 : 0;
                        @endphp
                        
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-blue-600 font-medium">Protein</span>
                                    <span class="text-gray-900">{{ number_format($proteinPercent, 1) }}%</span>
                                </div>
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $proteinPercent }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-green-600 font-medium">Carbohydrates</span>
                                    <span class="text-gray-900">{{ number_format($carbsPercent, 1) }}%</span>
                                </div>
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $carbsPercent }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-red-600 font-medium">Fat</span>
                                    <span class="text-gray-900">{{ number_format($fatPercent, 1) }}%</span>
                                </div>
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $fatPercent }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meal Information -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Meal Info</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-gray-500">Created:</span>
                                <div class="font-medium">{{ $meal->created_at->format('M d, Y') }}</div>
                                <div class="text-gray-400">{{ $meal->created_at->format('g:i A') }}</div>
                            </div>
                            <div>
                                <span class="text-gray-500">Last Updated:</span>
                                <div class="font-medium">{{ $meal->updated_at->diffForHumans() }}</div>
                            </div>
                            <div>
                                <span class="text-gray-500">Foods Count:</span>
                                <div class="font-medium">{{ $meal->foods->count() }} items</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button onclick="window.print()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Print Meal Plan
                        </button>
                        <a href="{{ route('client.meals.create') }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block text-center">
                            Create Similar Meal
                        </a>
                        <a href="{{ route('client.meals.foods') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block text-center">
                            Browse More Foods
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection