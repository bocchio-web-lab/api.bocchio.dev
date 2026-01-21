<?php
// routes/services/manage_cms.php

use App\Services\Cms\Http\Controllers\CmsManagementController;
use Illuminate\Support\Facades\Route;

// All routes here are prefixed with /api/manage/cms
// and protected by 'auth:sanctum' and 'tenant.context:cms'

Route::get('/dashboard', [CmsManagementController::class, 'dashboard']);
Route::get('/posts', [CmsManagementController::class, 'listPosts']);
Route::get('/pages', [CmsManagementController::class, 'listPages']);

// Future routes for full CRUD operations:
// Route::apiResource('posts', PostController::class);
// Route::apiResource('pages', PageController::class);
// Route::apiResource('media', MediaController::class);