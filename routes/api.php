<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import the new Platform controllers
use App\Services\Platform\Http\Controllers\ServiceController;
use App\Services\Platform\Http\Controllers\TenantController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Fortify/Sanctum auth routes (/login, /register, etc.) are usually
// registered elsewhere, often in web.php or by Fortify itself.

// --- Platform Management Routes ---
// These are for managing the tenants themselves.
Route::prefix('manage')
    ->middleware('auth:sanctum')
    ->group(function () {

        // List available services (GET /api/manage/services)
        Route::get('/services', [ServiceController::class, 'index']);

        // Manage your tenants (CRUD for /api/manage/tenants)
        Route::apiResource('/tenants', TenantController::class);

        // You can add routes for inviting users to a tenant here
        // e.g., POST /api/manage/tenants/{tenant}/invite
    });


// --- Service-Specific Management Routes ---
// These are for *using* the services.
// We'll add the files in the next step, but let's register them.

Route::prefix('manage/cms')
    ->middleware(['auth:sanctum', 'tenant.context:cms']) // <-- Our new middleware!
    ->group(base_path('routes/services/manage_cms.php'));

// Example for your future game service
// Route::prefix('manage/game')
//     ->middleware(['auth:sanctum', 'tenant.context:game'])
//     ->group(base_path('routes/services/manage_game.php'));