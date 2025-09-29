@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create Your Meal Plan</h1>
                        <p class="text-gray-600 mt-2">Design a personalized meal with your favorite foods</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('client.meals.foods') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Browse Foods
                        </a>
                        <a href="{{ route('client.meals.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            My Meals
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('client.meals.store') }}" method="POST" id="meal-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Meal Details & Summary -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Meal Information -->
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Meal Information</h3>
                            
                            <!-- Meal Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meal Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror"
                                       placeholder="e.g., Breakfast, Post-Workout Meal"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Meal Description -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description (Optional)
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="3"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror"
                                          placeholder="Describe your meal...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nutritional Summary -->
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Nutritional Summary</h3>
                            <div class="space-y-4">
                                <div class="text-center bg-yellow-50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-yellow-600" id="total-calories">0</div>
                                    <div class="text-sm text-gray-500">Total Calories</div>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="text-center bg-blue-50 rounded-lg p-3">
                                        <div class="font-semibold text-blue-600" id="total-protein">0g</div>
                                        <div class="text-xs text-gray-500">Protein</div>
                                    </div>
                                    <div class="text-center bg-green-50 rounded-lg p-3">
                                        <div class="font-semibold text-green-600" id="total-carbs">0g</div>
                                        <div class="text-xs text-gray-500">Carbs</div>
                                    </div>
                                    <div class="text-center bg-red-50 rounded-lg p-3">
                                        <div class="font-semibold text-red-600" id="total-fat">0g</div>
                                        <div class="text-xs text-gray-500">Fat</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Foods Summary -->
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Selected Foods</h3>
                            <div id="selected-foods-summary" class="space-y-2">
                                <p class="text-sm text-gray-500">No foods selected yet</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Food Selection -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Select Foods <span class="text-red-500">*</span>
                                </h3>
                                <button type="button" id="load-from-session" class="text-sm text-blue-600 hover:text-blue-800">
                                    Load from Browse
                                </button>
                            </div>
                            
                            <!-- Search Foods -->
                            <div class="mb-4">
                                <input type="text" 
                                       id="food-search" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500"
                                       placeholder="Search foods...">
                            </div>

                            <!-- Available Foods -->
                            <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-md">
                                @foreach($foods as $food)
                                    <div class="food-item p-4 border-b border-gray-100 hover:bg-gray-50" 
                                         data-food-id="{{ $food->id }}" 
                                         data-food-name="{{ $food->name }}" 
                                         data-calories="{{ $food->calories }}" 
                                         data-protein="{{ $food->protein }}" 
                                         data-carbs="{{ $food->carbs }}" 
                                         data-fat="{{ $food->fat }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           class="food-checkbox h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                                           data-food-id="{{ $food->id }}">
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $food->name }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $food->calories }}cal • {{ number_format($food->protein, 1) }}g protein • {{ number_format($food->carbs, 1) }}g carbs • {{ number_format($food->fat, 1) }}g fat
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-4 food-quantity-container flex items-center space-x-2" style="display: none;">
                                                <label class="text-sm text-gray-600">Qty:</label>
                                                <input type="number" 
                                                       class="food-quantity w-16 px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                                       min="1" 
                                                       value="1"
                                                       data-food-id="{{ $food->id }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('foods')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs for selected foods -->
            <div id="hidden-foods-inputs"></div>

            <!-- Form Actions -->
            <div class="mt-8 flex items-center justify-center space-x-4">
                <a href="{{ route('client.meals.index') }}" class="bg-white py-3 px-6 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create My Meal Plan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedFoods = new Map();
    
    // Load foods from session storage if available
    document.getElementById('load-from-session').addEventListener('click', function() {
        const sessionFoods = JSON.parse(sessionStorage.getItem('selectedFoods') || '[]');
        sessionFoods.forEach(sessionFood => {
            const checkbox = document.querySelector(`input[data-food-id="${sessionFood.id}"]`);
            if (checkbox) {
                checkbox.checked = true;
                const foodItem = checkbox.closest('.food-item');
                const quantityContainer = foodItem.querySelector('.food-quantity-container');
                const quantityInput = foodItem.querySelector('.food-quantity');
                
                quantityContainer.style.display = 'flex';
                quantityInput.value = sessionFood.quantity;
                
                addFoodToSelection(sessionFood.id, foodItem, sessionFood.quantity);
            }
        });
        // Clear session storage after loading
        sessionStorage.removeItem('selectedFoods');
    });
    
    // Food search functionality
    const searchInput = document.getElementById('food-search');
    const foodItems = document.querySelectorAll('.food-item');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        foodItems.forEach(item => {
            const foodName = item.dataset.foodName.toLowerCase();
            if (foodName.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Food selection functionality
    document.querySelectorAll('.food-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const foodId = this.dataset.foodId;
            const foodItem = this.closest('.food-item');
            const quantityContainer = foodItem.querySelector('.food-quantity-container');
            const quantityInput = foodItem.querySelector('.food-quantity');
            
            if (this.checked) {
                quantityContainer.style.display = 'flex';
                addFoodToSelection(foodId, foodItem, parseInt(quantityInput.value));
            } else {
                quantityContainer.style.display = 'none';
                removeFoodFromSelection(foodId);
            }
        });
    });
    
    // Quantity change functionality
    document.querySelectorAll('.food-quantity').forEach(input => {
        input.addEventListener('input', function() {
            const foodId = this.dataset.foodId;
            if (selectedFoods.has(foodId)) {
                updateFoodQuantity(foodId, parseInt(this.value) || 1);
            }
        });
    });
    
    function addFoodToSelection(foodId, foodItem, quantity = 1) {
        const foodData = {
            id: foodId,
            name: foodItem.dataset.foodName,
            calories: parseFloat(foodItem.dataset.calories),
            protein: parseFloat(foodItem.dataset.protein),
            carbs: parseFloat(foodItem.dataset.carbs),
            fat: parseFloat(foodItem.dataset.fat),
            quantity: quantity
        };
        
        selectedFoods.set(foodId, foodData);
        updateSelectedFoodsSummary();
        updateNutritionalSummary();
        updateHiddenInputs();
    }
    
    function removeFoodFromSelection(foodId) {
        selectedFoods.delete(foodId);
        updateSelectedFoodsSummary();
        updateNutritionalSummary();
        updateHiddenInputs();
    }
    
    function updateFoodQuantity(foodId, quantity) {
        if (selectedFoods.has(foodId)) {
            selectedFoods.get(foodId).quantity = quantity;
            updateSelectedFoodsSummary();
            updateNutritionalSummary();
            updateHiddenInputs();
        }
    }
    
    function updateSelectedFoodsSummary() {
        const container = document.getElementById('selected-foods-summary');
        
        if (selectedFoods.size === 0) {
            container.innerHTML = '<p class="text-sm text-gray-500">No foods selected yet</p>';
            return;
        }
        
        let html = '';
        selectedFoods.forEach(food => {
            html += `
                <div class="flex justify-between items-center text-sm py-1">
                    <span class="text-gray-700">${food.name}</span>
                    <span class="text-gray-500">${food.quantity}x</span>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    function updateNutritionalSummary() {
        let totalCalories = 0;
        let totalProtein = 0;
        let totalCarbs = 0;
        let totalFat = 0;
        
        selectedFoods.forEach(food => {
            totalCalories += food.calories * food.quantity;
            totalProtein += food.protein * food.quantity;
            totalCarbs += food.carbs * food.quantity;
            totalFat += food.fat * food.quantity;
        });
        
        document.getElementById('total-calories').textContent = Math.round(totalCalories);
        document.getElementById('total-protein').textContent = totalProtein.toFixed(1) + 'g';
        document.getElementById('total-carbs').textContent = totalCarbs.toFixed(1) + 'g';
        document.getElementById('total-fat').textContent = totalFat.toFixed(1) + 'g';
    }
    
    function updateHiddenInputs() {
        const container = document.getElementById('hidden-foods-inputs');
        container.innerHTML = '';
        
        selectedFoods.forEach(food => {
            container.innerHTML += `
                <input type="hidden" name="foods[${food.id}][id]" value="${food.id}">
                <input type="hidden" name="foods[${food.id}][quantity]" value="${food.quantity}">
            `;
        });
    }
});
</script>
@endsection