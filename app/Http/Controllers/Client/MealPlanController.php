<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\MealPlanningFt\MealService;
use App\Services\MealPlanningFt\FoodService;
use App\Http\Requests\MealPlanningFt\StoreMealRequest;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    protected $mealService;
    protected $foodService;

    public function __construct(MealService $mealService, FoodService $foodService)
    {
        $this->mealService = $mealService;
        $this->foodService = $foodService;
    }

    /**
     * Display available foods for meal creation
     */
    public function foods()
    {
        $foods = $this->foodService->getAllFood();
        return view('client.meals.foods', compact('foods'));
    }

    /**
     * Show the form for creating a new meal
     */
    public function create()
    {
        $foods = $this->foodService->getAllFood();
        return view('client.meals.create', compact('foods'));
    }

    /**
     * Display user's meals
     */
    public function index()
    {
        $meals = $this->mealService->getAllMeals();
        return view('client.meals.index', compact('meals'));
    }

    /**
     * Store a newly created meal
     */
    public function store(StoreMealRequest $request)
    {
        try {
            $this->mealService->createMeal($request->validated());
            return redirect()->route('client.meals.index')
                ->with('success', 'Meal plan created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create meal plan. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified meal
     */
    public function show($id)
    {
        try {
            $meal = $this->mealService->getMealById($id);
            return view('client.meals.show', compact('meal'));
        } catch (\Exception $e) {
            return redirect()->route('client.meals.index')
                ->with('error', 'Meal not found.');
        }
    }
}