@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Browse Foods</h1>
                        <p class="text-gray-600 mt-2">Explore our food database to create your perfect meal plan</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('client.meals.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Create Meal Plan
                        </a>
                        <a href="{{ route('client.meals.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            My Meals
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" 
                               id="food-search" 
                               class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500"
                               placeholder="Search foods by name...">
                    </div>
                    <div>
                        <select id="category-filter" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <option value="">All Categories</option>
                            <option value="high-protein">High Protein (>15g)</option>
                            <option value="low-carb">Low Carb (<10g)</option>
                            <option value="low-fat">Low Fat (<5g)</option>
                            <option value="high-calorie">High Calorie (>200cal)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foods Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="foods-grid">
            @foreach($foods as $food)
                <div class="food-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200" 
                     data-name="{{ strtolower($food->name) }}" 
                     data-protein="{{ $food->protein }}" 
                     data-carbs="{{ $food->carbs }}" 
                     data-fat="{{ $food->fat }}" 
                     data-calories="{{ $food->calories }}">
                    <div class="p-6">
                        <!-- Food Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $food->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $food->calories }} cal
                            </span>
                        </div>

                        <!-- Nutritional Info -->
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="text-center bg-blue-50 rounded-lg p-2">
                                <div class="text-sm font-bold text-blue-600">{{ number_format($food->protein, 1) }}g</div>
                                <div class="text-xs text-gray-500">Protein</div>
                            </div>
                            <div class="text-center bg-green-50 rounded-lg p-2">
                                <div class="text-sm font-bold text-green-600">{{ number_format($food->carbs, 1) }}g</div>
                                <div class="text-xs text-gray-500">Carbs</div>
                            </div>
                            <div class="text-center bg-red-50 rounded-lg p-2">
                                <div class="text-sm font-bold text-red-600">{{ number_format($food->fat, 1) }}g</div>
                                <div class="text-xs text-gray-500">Fat</div>
                            </div>
                        </div>

                        <!-- Nutritional Tags -->
                        <div class="flex flex-wrap gap-1 mb-4">
                            @if($food->protein > 15)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    High Protein
                                </span>
                            @endif
                            @if($food->carbs < 10)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Low Carb
                                </span>
                            @endif
                            @if($food->fat < 5)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Low Fat
                                </span>
                            @endif
                        </div>

                        <!-- Add to Meal Button -->
                        <button onclick="addToMealPlan({{ $food->id }}, '{{ $food->name }}')" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Add to Meal Plan
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($foods->count() == 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No foods available</h3>
                <p class="mt-1 text-sm text-gray-500">Contact your administrator to add foods to the database.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('food-search');
    const categoryFilter = document.getElementById('category-filter');
    const foodCards = document.querySelectorAll('.food-card');

    function filterFoods() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;

        foodCards.forEach(card => {
            const name = card.dataset.name;
            const protein = parseFloat(card.dataset.protein);
            const carbs = parseFloat(card.dataset.carbs);
            const fat = parseFloat(card.dataset.fat);
            const calories = parseFloat(card.dataset.calories);

            let matchesSearch = name.includes(searchTerm);
            let matchesCategory = true;

            if (selectedCategory) {
                switch (selectedCategory) {
                    case 'high-protein':
                        matchesCategory = protein > 15;
                        break;
                    case 'low-carb':
                        matchesCategory = carbs < 10;
                        break;
                    case 'low-fat':
                        matchesCategory = fat < 5;
                        break;
                    case 'high-calorie':
                        matchesCategory = calories > 200;
                        break;
                }
            }

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterFoods);
    categoryFilter.addEventListener('change', filterFoods);
});

function addToMealPlan(foodId, foodName) {
    // Store selected food in session storage for meal creation
    let selectedFoods = JSON.parse(sessionStorage.getItem('selectedFoods') || '[]');
    
    // Check if food is already selected
    const existingFood = selectedFoods.find(food => food.id === foodId);
    if (existingFood) {
        existingFood.quantity += 1;
    } else {
        selectedFoods.push({
            id: foodId,
            name: foodName,
            quantity: 1
        });
    }
    
    sessionStorage.setItem('selectedFoods', JSON.stringify(selectedFoods));
    
    // Show success message
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Added!';
    button.classList.remove('bg-green-600', 'hover:bg-green-700');
    button.classList.add('bg-green-800');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-800');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
    }, 1000);
}
</script>
@endsection