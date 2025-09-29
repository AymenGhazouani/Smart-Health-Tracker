<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Weight;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WeightController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Weight::where('user_id', $userId)->orderByDesc('measured_at');

        if ($request->filled('from')) {
            $query->where('measured_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->where('measured_at', '<=', $request->date('to'));
        }

        return $query->paginate(50);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'value_kg' => ['required', 'numeric', 'between:1,500'],
            'measured_at' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $weight = Weight::create([
            'user_id' => Auth::id(),
            'value_kg' => $data['value_kg'],
            'measured_at' => $data['measured_at'],
            'note' => $data['note'] ?? null,
        ]);

        return response()->json($weight, Response::HTTP_CREATED);
    }

    public function show(Weight $weight)
    {
        $this->authorizeOwnership($weight);
        return $weight;
    }

    public function update(Request $request, Weight $weight)
    {
        $this->authorizeOwnership($weight);

        $data = $request->validate([
            'value_kg' => ['sometimes', 'numeric', 'between:1,500'],
            'measured_at' => ['sometimes', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $weight->update($data);
        return $weight;
    }

    public function destroy(Weight $weight)
    {
        $this->authorizeOwnership($weight);
        $weight->delete();
        return response()->noContent();
    }

    private function authorizeOwnership(Weight $weight): void
    {
        abort_unless($weight->user_id === Auth::id(), Response::HTTP_FORBIDDEN);
    }
}


