<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::with('user')->where('is_active', true)->get();
        return response()->json($providers);
    }

    public function show(Provider $provider)
    {
        return response()->json($provider);
    }
}
