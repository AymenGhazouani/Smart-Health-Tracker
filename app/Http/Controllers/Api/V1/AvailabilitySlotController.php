<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AvailabilitySlot;
use Illuminate\Http\Request;

class AvailabilitySlotController extends Controller
{
    public function index(Request $request)
    {
        $provider_id = $request->query('provider_id');
        $slots = AvailabilitySlot::when($provider_id, function($query) use ($provider_id) {
            return $query->where('provider_id', $provider_id);
        })
            ->where('is_booked', false)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->get();

        return response()->json($slots);
    }
}
