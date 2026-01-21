<?php
// routes/services/content_cms.php

use App\Services\Cms\Http\Controllers\CmsContentController;
use Illuminate\Support\Facades\Route;

// All routes here are prefixed with /api/content/cms/{tenant_slug}
// and protected by 'tenant.public_access:cms'

Route::get('/', [CmsContentController::class, 'index']);

// Posts
Route::get('/posts', [CmsContentController::class, 'listPosts']);
Route::get('/posts/{post_slug}', [CmsContentController::class, 'showPost']);

// Pages
Route::get('/pages', [CmsContentController::class, 'listPages']);
Route::get('/pages/{page_slug}', [CmsContentController::class, 'showPage']);

// Projects
Route::get('/projects', [CmsContentController::class, 'listProjects']);

// Tags
Route::get('/tags', [CmsContentController::class, 'listTags']);
Route::get('/tags/{tag_slug}', [CmsContentController::class, 'showTag']);