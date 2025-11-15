<?php
// routes/services/manage_cms.php

use Illuminate\Support\Facades\Route;

// Dummy route for testing middleware
Route::get('/middleware-test', function () {
    return response()->json([
        'message' => 'CMS route success!',
        'tenant_id' => app('current_tenant_id')
    ]);
});