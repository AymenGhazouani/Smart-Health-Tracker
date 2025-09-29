<?php

namespace App\Http\Controllers\Admin\Metrics;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Weight;
use Illuminate\Http\Request;

class AdminWeightsController extends Controller
{
    public function index(User $user)
    {
        $weights = Weight::where('user_id', $user->id)->orderByDesc('measured_at')->paginate(20);
        return view('admin.metrics.weights.index', compact('user', 'weights'));
    }

    public function create(User $user)
    {
        return view('admin.metrics.weights.create', compact('user'));
    }

    public function store(Request $request, User $user)
    {
        $data = $request->validate([
            'value_kg' => ['required','numeric','between:1,500'],
            'measured_at' => ['required','date'],
            'note' => ['nullable','string','max:255'],
        ]);
        Weight::create($data + ['user_id' => $user->id]);
        return redirect()->route('admin.metrics.weights.index', $user)->with('success', 'Weight added');
    }

    public function edit(User $user, Weight $weight)
    {
        abort_unless($weight->user_id === $user->id, 404);
        return view('admin.metrics.weights.edit', compact('user','weight'));
    }

    public function update(Request $request, User $user, Weight $weight)
    {
        abort_unless($weight->user_id === $user->id, 404);
        $data = $request->validate([
            'value_kg' => ['required','numeric','between:1,500'],
            'measured_at' => ['required','date'],
            'note' => ['nullable','string','max:255'],
        ]);
        $weight->update($data);
        return redirect()->route('admin.metrics.weights.index', $user)->with('success', 'Weight updated');
    }

    public function destroy(User $user, Weight $weight)
    {
        abort_unless($weight->user_id === $user->id, 404);
        $weight->delete();
        return redirect()->route('admin.metrics.weights.index', $user)->with('success', 'Weight deleted');
    }
}


