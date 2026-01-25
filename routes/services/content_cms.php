<?php
// routes/services/content_cms.php

use App\Services\Cms\Http\Controllers\CmsContentController;
use Illuminate\Support\Facades\Route;

// All routes here are prefixed with /api/content/cms/{tenant_slug}
// and protected by 'tenant.public_access:cms'

// Tenant info
Route::get('/', [CmsContentController::class, 'index']);

// Dynamic content routes - handles any type (posts, pages, projects, etc.)
// Examples: /posts, /pages, /projects, /books, /galleries
Route::get('/{type}', [CmsContentController::class, 'listByType']);
Route::get('/{type}/{item_slug}', [CmsContentController::class, 'showByType']);

// Tags - separate endpoints as they use a different model
Route::get('/tags', [CmsContentController::class, 'listTags']);
Route::get('/tags/{tag_slug}', [CmsContentController::class, 'showTag']);