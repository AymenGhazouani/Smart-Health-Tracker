@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Meal Plans</h1>
                        <p class="text-gray-600 mt-2">Manage and track your personalized meal plans</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('client.meals.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Meal
                        </a>
                        <a href="{{ route('client.meals.foods') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Browse Foods
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workout Q&A Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">💪 Quick Workout Q&A</h2>
                <p class="text-gray-600 mb-6">Get instant answers to common workout questions</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="qa-grid">
                    <!-- Questions will be loaded here -->
                </div>
                
                <!-- Answer Display -->
                <div id="answer-section" class="hidden mt-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <h3 id="answer-question" class="font-semibold text-gray-900 mb-2"></h3>
                    <p id="answer-text" class="text-gray-700"></p>
                    <button onclick="hideAnswer()" class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">
                        ← Back to questions
                    </button>
                </div>
            </div>
        </div>

        @if($meals->count() > 0)
            <!-- Meals Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($meals as $meal)
                    @php
                        $macros = $meal->total_macros;
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200">
                        <!-- Meal Header -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 truncate">{{ $meal->name }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $meal->foods->count() }} foods
                                </span>
                            </div>
                            
                            @if($meal->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $meal->description }}</p>
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

                            <!-- Foods Preview -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Foods in this meal:</h4>
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

                            <!-- Action Button -->
                            <a href="{{ route('client.meals.show', $meal->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-150 ease-in-out block">
                                View Details
                            </a>
                        </div>

                        <!-- Meal Footer -->
                        <div class="bg-gray-50 px-6 py-3 text-xs text-gray-500 rounded-b-lg">
                            Created {{ $meal->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
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

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
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
                                        {{ $meals->count() > 0 ? number_format($meals->avg(function($meal) { return $meal->total_macros['calories']; })) : 0 }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
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

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
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
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No meal plans yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first personalized meal plan.</p>
                <div class="mt-6 flex justify-center space-x-4">
                    <a href="{{ route('client.meals.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Your First Meal
                    </a>
                    <a href="{{ route('client.meals.foods') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Browse Foods First
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Workout Q&A Data
    const workoutQA = [
        {
            question: "How many times should I workout per week?",
            answer: "For beginners, 3-4 times per week is ideal. This allows adequate recovery time between sessions while building consistency. Advanced individuals can workout 5-6 times per week with proper programming."
        },
        {
            question: "Should I do cardio before or after weights?",
            answer: "Do weights first if your primary goal is building muscle and strength. Do cardio first if your main goal is cardiovascular fitness. For fat loss, either order works - consistency matters more."
        },
        {
            question: "How long should my workouts be?",
            answer: "Effective workouts can be 30-60 minutes. Quality over quantity - a focused 30-minute session is better than a distracted 90-minute workout. Include warm-up and cool-down in your time."
        },
        {
            question: "What should I eat before working out?",
            answer: "Eat a light meal 2-3 hours before, or a small snack 30-60 minutes before. Good options: banana with peanut butter, oatmeal with berries, or a protein smoothie. Avoid heavy, fatty foods."
        },
        {
            question: "How much water should I drink during exercise?",
            answer: "Drink 7-10 oz every 10-20 minutes during exercise. Start hydrating 2-3 hours before your workout. If exercising over an hour, consider a sports drink to replace electrolytes."
        },
        {
            question: "Is it normal to feel sore after workouts?",
            answer: "Yes, mild muscle soreness 24-48 hours after exercise is normal, especially when starting or increasing intensity. Sharp pain during exercise is not normal - stop and assess if this occurs."
        },
        {
            question: "Can I workout every day?",
            answer: "You can be active daily, but intense workouts need rest days. Alternate between high-intensity days and light activities like walking, yoga, or stretching. Listen to your body's signals."
        },
        {
            question: "What's the best time to workout?",
            answer: "The best time is when you can be consistent. Morning workouts can boost energy for the day. Evening workouts can relieve stress. Choose what fits your schedule and energy levels."
        },
        {
            question: "How do I know if I'm lifting enough weight?",
            answer: "You should be able to complete all reps with good form, but the last 2-3 reps should feel challenging. If you can easily do more reps than planned, increase the weight by 5-10%."
        },
        {
            question: "Should I stretch before or after workouts?",
            answer: "Do dynamic stretching (moving stretches) before workouts to warm up. Do static stretching (holding stretches) after workouts when muscles are warm to improve flexibility."
        }
    ];

    // Randomly select 6 questions
    function getRandomQuestions() {
        const shuffled = workoutQA.sort(() => 0.5 - Math.random());
        return shuffled.slice(0, 6);
    }

    // Display questions
    function displayQuestions() {
        const qaGrid = document.getElementById('qa-grid');
        const randomQuestions = getRandomQuestions();
        
        qaGrid.innerHTML = '';
        
        randomQuestions.forEach((qa, index) => {
            const questionButton = document.createElement('button');
            questionButton.className = 'text-left p-4 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-lg border border-blue-200 transition-all duration-200 transform hover:scale-105';
            questionButton.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">${qa.question}</p>
                        <p class="text-xs text-gray-500 mt-1">Click to see answer</p>
                    </div>
                </div>
            `;
            
            questionButton.addEventListener('click', () => showAnswer(qa.question, qa.answer));
            qaGrid.appendChild(questionButton);
        });
    }

    // Show answer
    window.showAnswer = function(question, answer) {
        document.getElementById('answer-question').textContent = question;
        document.getElementById('answer-text').textContent = answer;
        document.getElementById('answer-section').classList.remove('hidden');
        document.getElementById('qa-grid').classList.add('hidden');
    };

    // Hide answer
    window.hideAnswer = function() {
        document.getElementById('answer-section').classList.add('hidden');
        document.getElementById('qa-grid').classList.remove('hidden');
    };

    // Initialize
    displayQuestions();
});
</script>

@endsection