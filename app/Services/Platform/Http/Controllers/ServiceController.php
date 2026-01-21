<?php

namespace App\Services\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Platform\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of all available services.
     */
    public function index()
    {
        $services = Service::active()->get();

        return response()->json([
            'data' => $services
        ]);
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        return response()->json([
            'data' => $service
        ]);
    }
}