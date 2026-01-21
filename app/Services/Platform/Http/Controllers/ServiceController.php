<?php

namespace App\Services\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Platform\Models\Service;

/**
 * @group Platform Management
 *
 * APIs for managing platform services and tenants. These endpoints require authentication
 * and allow users to list available services and manage their tenants.
 */
class ServiceController extends Controller
{
    /**
     * List all services
     *
     * Returns a list of all active services available on the platform.
     * Services are logical groupings of functionality (e.g., CMS).
     * You'll need a service ID when creating a tenant.
     *
     * @authenticated
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "CMS",
     *       "slug": "cms",
     *       "description": "Content Management System",
     *       "is_active": true,
     *       "created_at": "2025-01-20T10:00:00.000000Z",
     *       "updated_at": "2025-01-20T10:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $services = Service::active()->get();

        return response()->json([
            'data' => $services
        ]);
    }

    /**
     * Get a specific service
     *
     * Returns details about a specific service.
     *
     * @authenticated
     *
     * @urlParam service integer required The ID of the service. Example: 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "CMS",
     *     "slug": "cms",
     *     "description": "Content Management System",
     *     "is_active": true,
     *     "created_at": "2025-01-20T10:00:00.000000Z",
     *     "updated_at": "2025-01-20T10:00:00.000000Z"
     *   }
     * }
     */
    public function show(Service $service)
    {
        return response()->json([
            'data' => $service
        ]);
    }
}