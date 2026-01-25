<?php

use App\Services\Platform\Enums\TenantAccessLevel;
use App\Services\Platform\Models\Service;
use App\Models\User;
use function Pest\Laravel\{getJson, withHeader};

test('public tenant works', function () {
    // Create user and tenant (authenticated just for setup)
    $user = User::factory()->create();
    $tenant = $user->tenants()->create([
        'name' => 'Public CMS',
        'service_id' => Service::where('slug', 'cms')->first()->id,
        'owner_id' => $user->id,
        'public_slug' => 'public-tenant-' . uniqid(),
        'access_level' => TenantAccessLevel::PUBLIC ,
    ]);

    // Make request as unauthenticated user
    getJson("/api/content/cms/{$tenant->public_slug}")->assertStatus(200);
});

test('private tenant blocks public access', function () {
    // Create user and tenant
    $user = User::factory()->create();
    $tenant = $user->tenants()->create([
        'name' => 'Private CMS',
        'service_id' => Service::where('slug', 'cms')->first()->id,
        'owner_id' => $user->id,
        'public_slug' => 'private-tenant-' . uniqid(),
        'access_level' => TenantAccessLevel::PRIVATE ,
    ]);

    // Make request as unauthenticated user - should be blocked
    getJson("/api/content/cms/{$tenant->public_slug}")->assertStatus(403);
});

test('token protected tenant requires token', function () {
    // Create user and tenant
    $user = User::factory()->create();
    $tenant = $user->tenants()->create([
        'name' => 'Token Protected CMS',
        'service_id' => Service::where('slug', 'cms')->first()->id,
        'owner_id' => $user->id,
        'public_slug' => 'token-tenant-' . uniqid(),
        'access_level' => TenantAccessLevel::TOKEN_PROTECTED,
    ]);

    // Without token - should be blocked
    getJson("/api/content/cms/{$tenant->public_slug}")->assertStatus(401);

    // With token - should work
    withHeader('Authorization', "Bearer {$tenant->public_api_key}")
        ->getJson("/api/content/cms/{$tenant->public_slug}")
        ->assertStatus(200);
});
