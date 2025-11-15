<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\Platform\Http\Controllers\ServiceController;
use App\Services\Platform\Http\Controllers\TenantController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('manage')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/services', [ServiceController::class, 'index']);
        Route::apiResource('/tenants', TenantController::class);
    });

Route::prefix('manage/cms')
    ->middleware(['auth:sanctum', 'tenant.context:cms'])
    ->group(base_path('routes/services/manage_cms.php'));


Route::prefix('content/cms/{tenant_slug}')
    ->middleware(['tenant.public_access:cms'])
    ->group(base_path('routes/services/content_cms.php'));