<?php
// routes/services/manage_cms.php

use App\Services\Cms\Http\Controllers\Manage\PostController;
use Illuminate\Support\Facades\Route;

// All routes here are prefixed with /api/manage/cms
// and protected by 'auth:sanctum' and 'tenant.context:cms'

Route::apiResource('posts', PostController::class);