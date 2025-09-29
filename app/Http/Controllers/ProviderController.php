<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::with('user')->where('is_active', true)->get();
        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        $users = User::where('role', '=', 'admin')
            ->orWhereNull('role')
            ->get();
        return view('admin.providers.create', compact('users'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('providers', 'public');
        }

        Provider::create($validated);

        return redirect()->route('admin.providers.index')
            ->with('success', 'Provider created successfully.');
    }

    public function show(Provider $provider)
    {
        return view('admin.providers.show', compact('provider'));
    }

    public function edit(Provider $provider)
    {
        $users = User::where('role', '!=', 'admin')
            ->orWhereNull('role')
            ->get();
        return view('admin.providers.edit', compact('provider', 'users'));
    }

    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'hourly_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('providers', 'public');
        }

        $provider->update($validated);

        return redirect()->route('admin.providers.index')
            ->with('success', 'Provider updated successfully.');
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('admin.providers.index')
            ->with('success', 'Provider deleted successfully.');
    }
}
