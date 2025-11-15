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
        return Service::all();
    }
}