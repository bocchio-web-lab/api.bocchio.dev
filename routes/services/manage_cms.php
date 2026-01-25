<?php
// routes/services/manage_cms.php

use App\Services\Cms\Http\Controllers\ContentItemController;
use App\Services\Cms\Http\Controllers\CommentModerationController;
use App\Services\Cms\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// All routes here are prefixed with /api/manage/cms
// and protected by 'auth:sanctum' and 'tenant.context:cms'

// Content Items
Route::apiResource('content', ContentItemController::class);

// Comment Moderation
Route::prefix('comments')->group(function () {
    Route::get('/', [CommentModerationController::class, 'index']);
    Route::post('/{comment}/approve', [CommentModerationController::class, 'approve']);
    Route::post('/{comment}/reject', [CommentModerationController::class, 'reject']);
    Route::delete('/{comment}', [CommentModerationController::class, 'destroy']);
});

// Tags
Route::apiResource('tags', TagController::class);

// Test route for middleware validation (used by tests)
Route::get('/middleware-test', function () {
    return response()->json([
        'message' => 'CMS route success!',
        'tenant_id' => app('current_tenant_id'),
    ]);
});