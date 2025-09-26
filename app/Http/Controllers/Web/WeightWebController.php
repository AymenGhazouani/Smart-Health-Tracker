<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Weight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeightWebController extends Controller
{
    public function index(Request $request)
    {
        $weights = Weight::where('user_id', Auth::id())
            ->orderByDesc('measured_at')
            ->paginate(15);
        return view('weights.index', compact('weights'));
    }

    public function create()
    {
        return view('weights.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'value_kg' => ['required','numeric','between:1,500'],
            'measured_at' => ['required','date'],
            'note' => ['nullable','string','max:255'],
        ]);

        Weight::create($data + ['user_id' => Auth::id()]);
        return redirect()->route('weights.index')->with('status', 'Weight added');
    }

    public function edit(Weight $weight)
    {
        abort_unless($weight->user_id === Auth::id(), 403);
        return view('weights.edit', compact('weight'));
    }

    public function update(Request $request, Weight $weight)
    {
        abort_unless($weight->user_id === Auth::id(), 403);

        $data = $request->validate([
            'value_kg' => ['required','numeric','between:1,500'],
            'measured_at' => ['required','date'],
            'note' => ['nullable','string','max:255'],
        ]);
        $weight->update($data);
        return redirect()->route('weights.index')->with('status', 'Weight updated');
    }

    public function destroy(Weight $weight)
    {
        abort_unless($weight->user_id === Auth::id(), 403);
        $weight->delete();
        return redirect()->route('weights.index')->with('status', 'Weight deleted');
    }
}


