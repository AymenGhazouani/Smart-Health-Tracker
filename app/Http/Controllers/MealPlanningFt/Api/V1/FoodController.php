<?php

namespace App\Http\Controllers\MealPlanningFt\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MealPlanningFt\StoreFoodRequest;
use App\Http\Requests\MealPlanningFt\UpdateFoodRequest;
use App\Services\MealPlanningFt\FoodService;

class FoodController extends Controller
{
    private FoodService $foodService;
    
    public function __construct(FoodService $foodService)
    {
        $this->foodService = $foodService;
    }

    /**
     * Display a listing of the resource.
     * Returns JSON for API requests, Blade view for web requests
     */
    public function index(Request $request) 
    { 
        $foods = $this->foodService->getAllFood();
        
        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($foods);
        }
        
        // Return web view for admin panel
        return view('admin.foods.index', compact('foods'));
    }

    /**
     * Show the form for creating a new resource.
     * Only for web requests
     */
    public function create()
    {
        return view('admin.foods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodRequest $request)
    {
        try {
            $food = $this->foodService->createFood($request->validated());
            
            // API response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json($food, 201);
            }
            
            // Web response
            return redirect()->route('admin.foods.index')
                ->with('success', 'Food item created successfully!');
                
        } catch (\Exception $e) {
            // API error response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to create food item'], 500);
            }
            
            // Web error response
            return redirect()->back()
                ->with('error', 'Failed to create food item. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $food = $this->foodService->getFoodById($id);
            
            // API response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json($food);
            }
            
            // Web response
            return view('admin.foods.show', compact('food'));
            
        } catch (\Exception $e) {
            // API error response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Food item not found'], 404);
            }
            
            // Web error response
            return redirect()->route('admin.foods.index')
                ->with('error', 'Food item not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * Only for web requests
     */
    public function edit($id)
    {
        try {
            $food = $this->foodService->getFoodById($id);
            return view('admin.foods.edit', compact('food'));
        } catch (\Exception $e) {
            return redirect()->route('admin.foods.index')
                ->with('error', 'Food item not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodRequest $request, $id)
    {
        try {
            $food = $this->foodService->updateFood($id, $request->validated());
            
            // API response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json($food);
            }
            
            // Web response
            return redirect()->route('admin.foods.index')
                ->with('success', 'Food item updated successfully!');
                
        } catch (\Exception $e) {
            // API error response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to update food item'], 500);
            }
            
            // Web error response
            return redirect()->back()
                ->with('error', 'Failed to update food item. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->foodService->deleteFood($id);
            
            // API response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(null, 204);
            }
            
            // Web response
            return redirect()->route('admin.foods.index')
                ->with('success', 'Food item deleted successfully!');
                
        } catch (\Exception $e) {
            // API error response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to delete food item'], 500);
            }
            
            // Web error response
            return redirect()->route('admin.foods.index')
                ->with('error', 'Failed to delete food item.');
        }
    }
}
