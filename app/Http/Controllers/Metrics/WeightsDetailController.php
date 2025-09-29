<?php

namespace App\Http\Controllers\Metrics;

use App\Http\Controllers\Controller;
use App\Models\Weight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WeightsDetailController extends Controller
{
    public function index()
    {
        $weights = Weight::where('user_id', Auth::id())
            ->orderByDesc('measured_at')
            ->paginate(20);
        return view('metrics.weights', compact('weights'));
    }

    public function create()
    {
        return view('metrics.weights.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'value_kg' => 'required|numeric|min:1|max:500',
            'measured_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        Weight::create([
            'user_id' => Auth::id(),
            'value_kg' => $request->value_kg,
            'measured_at' => $request->measured_at,
            'note' => $request->note,
        ]);

        return redirect()->route('metrics.weights')->with('success', 'Weight entry added successfully!');
    }

    public function edit(Weight $weight)
    {
        // Ensure user can only edit their own weights
        if ($weight->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('metrics.weights.edit', compact('weight'));
    }

    public function update(Request $request, Weight $weight)
    {
        // Ensure user can only update their own weights
        if ($weight->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'value_kg' => 'required|numeric|min:1|max:500',
            'measured_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $weight->update([
            'value_kg' => $request->value_kg,
            'measured_at' => $request->measured_at,
            'note' => $request->note,
        ]);

        return redirect()->route('metrics.weights')->with('success', 'Weight entry updated successfully!');
    }

    public function destroy(Weight $weight)
    {
        // Ensure user can only delete their own weights
        if ($weight->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $weight->delete();

        return redirect()->route('metrics.weights')->with('success', 'Weight entry deleted successfully!');
    }
}


