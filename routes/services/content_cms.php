<?php
// routes/services/content_cms.php

use App\Services\Cms\Http\Controllers\Content\PostController;
use Illuminate\Support\Facades\Route;

// All routes here are prefixed with /api/content/cms/{tenant_slug}
// and protected by 'tenant.public_access:cms'

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post_slug}', [PostController::class, 'show']);