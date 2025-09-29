@extends('layouts.admin')

@section('page-title', 'Add New Food')

@section('admin-content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Food Item</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Enter the nutritional information for the new food item</p>
                </div>
                <a href="{{ route('admin.foods.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('admin.foods.store') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Food Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Food Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Enter food name (e.g., Chicken Breast, Apple, Rice)"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Calories -->
                <div>
                    <label for="calories" class="block text-sm font-medium text-gray-700 mb-2">
                        Calories (per 100g) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="calories" 
                           id="calories" 
                           value="{{ old('calories') }}"
                           min="0"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('calories') border-red-500 @enderror"
                           placeholder="Enter calories (e.g., 165)"
                           required>
                    @error('calories')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Macronutrients Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Protein -->
                    <div>
                        <label for="protein" class="block text-sm font-medium text-gray-700 mb-2">
                            Protein (g)
                        </label>
                        <input type="number" 
                               name="protein" 
                               id="protein" 
                               value="{{ old('protein', 0) }}"
                               min="0"
                               step="0.1"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('protein') border-red-500 @enderror"
                               placeholder="0.0">
                        @error('protein')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Carbohydrates -->
                    <div>
                        <label for="carbs" class="block text-sm font-medium text-gray-700 mb-2">
                            Carbohydrates (g)
                        </label>
                        <input type="number" 
                               name="carbs" 
                               id="carbs" 
                               value="{{ old('carbs', 0) }}"
                               min="0"
                               step="0.1"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('carbs') border-red-500 @enderror"
                               placeholder="0.0">
                        @error('carbs')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fat -->
                    <div>
                        <label for="fat" class="block text-sm font-medium text-gray-700 mb-2">
                            Fat (g)
                        </label>
                        <input type="number" 
                               name="fat" 
                               id="fat" 
                               value="{{ old('fat', 0) }}"
                               min="0"
                               step="0.1"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('fat') border-red-500 @enderror"
                               placeholder="0.0">
                        @error('fat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nutritional Info Card -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Nutritional Information Preview</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div class="text-center">
                            <div class="font-semibold text-yellow-600" id="preview-calories">0</div>
                            <div class="text-gray-500">Calories</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-blue-600" id="preview-protein">0.0g</div>
                            <div class="text-gray-500">Protein</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-green-600" id="preview-carbs">0.0g</div>
                            <div class="text-gray-500">Carbs</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-red-600" id="preview-fat">0.0g</div>
                            <div class="text-gray-500">Fat</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.foods.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Food Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const caloriesInput = document.getElementById('calories');
    const proteinInput = document.getElementById('protein');
    const carbsInput = document.getElementById('carbs');
    const fatInput = document.getElementById('fat');

    function updatePreview() {
        document.getElementById('preview-calories').textContent = caloriesInput.value || '0';
        document.getElementById('preview-protein').textContent = (proteinInput.value || '0') + 'g';
        document.getElementById('preview-carbs').textContent = (carbsInput.value || '0') + 'g';
        document.getElementById('preview-fat').textContent = (fatInput.value || '0') + 'g';
    }

    caloriesInput.addEventListener('input', updatePreview);
    proteinInput.addEventListener('input', updatePreview);
    carbsInput.addEventListener('input', updatePreview);
    fatInput.addEventListener('input', updatePreview);

    // Initial preview update
    updatePreview();
});
</script>
@endsection