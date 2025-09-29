<?php

namespace App\Http\Controllers\MealPlanningFt\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MealPlanningFt\MealService;
use App\Services\MealPlanningFt\FoodService;
use App\Http\Requests\MealPlanningFt\StoreMealRequest;

class MealController extends Controller
{
    protected $mealService;
    protected $foodService;

    public function __construct(MealService $mealService, FoodService $foodService)
    {
        $this->mealService = $mealService;
        $this->foodService = $foodService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $meals = $this->mealService->getAllMeals();
        
        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($meals, 200);
        }
        
        // Return web view for admin panel
        return view('admin.meals.index', compact('meals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $foods = $this->foodService->getAllFood();
        return view('admin.meals.create', compact('foods'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $meal = $this->mealService->getMealById($id);
            
            // API response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json($meal, 200);
            }
            
            // Web response
            return view('admin.meals.show', compact('meal'));
            
        } catch (\Exception $e) {
            // API error response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Meal not found'], 404);
            }
            
            // Web error response
            return redirect()->route('admin.meals.index')
                ->with('error', 'Meal not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $meal = $this->mealService->getMealById($id);
            $foods = $this->foodService->getAllFood();
            return view('admin.meals.edit', compact('meal', 'foods'));
        } catch (\Exception $e) {
            return redirect()->route('admin.meals.index')
                ->with('error', 'Meal not found.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMealRequest $request)
    {
        try {
            $meal = $this->mealService->createMeal($request->validated());
            
            // API response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json($meal, 201);
            }
            
            // Web response
            return redirect()->route('admin.meals.index')
                ->with('success', 'Meal created successfully!');
                
        } catch (\Exception $e) {
            // API error response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to create meal'], 500);
            }
            
            // Web error response
            return redirect()->back()
                ->with('error', 'Failed to create meal. Please try again.')
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreMealRequest $request, $id)
    {
        try {
            $meal = $this->mealService->updateMeal($id, $request->validated());
            
            // API response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json($meal, 200);
            }
            
            // Web response
            return redirect()->route('admin.meals.index')
                ->with('success', 'Meal updated successfully!');
                
        } catch (\Exception $e) {
            // API error response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to update meal'], 500);
            }
            
            // Web error response
            return redirect()->back()
                ->with('error', 'Failed to update meal. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->mealService->deleteMeal($id);
            
            // API response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(null, 204);
            }
            
            // Web response
            return redirect()->route('admin.meals.index')
                ->with('success', 'Meal deleted successfully!');
                
        } catch (\Exception $e) {
            // API error response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to delete meal'], 500);
            }
            
            // Web error response
            return redirect()->route('admin.meals.index')
                ->with('error', 'Failed to delete meal.');
        }
    }
}
