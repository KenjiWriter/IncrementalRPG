<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of all locations.
     */
    public function index(): JsonResponse
    {
        $locations = Location::all();

        return response()->json([
            'status' => 'success',
            'data' => $locations,
        ]);
    }
}
