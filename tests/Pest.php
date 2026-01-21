<?php

use App\Models\User;
use App\Services\Platform\Models\Service;
use App\Services\Platform\Models\Tenant;
use App\Services\Platform\Enums\TenantAccessLevel;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

/**
 * Create a user and return it authenticated.
 */
function actingAsUser(array $attributes = []): User
{
    $user = User::factory()->create($attributes);
    test()->actingAs($user, 'sanctum');
    return $user;
}

/**
 * Create a tenant for a given service.
 */
function createTenantForService(string $serviceSlug, User $owner, array $attributes = []): Tenant
{
    $service = Service::where('slug', $serviceSlug)->first();

    if (!$service) {
        throw new \Exception("Service '{$serviceSlug}' not found. Run seeders first.");
    }

    $tenant = Tenant::create(array_merge([
        'service_id' => $service->id,
        'owner_id' => $owner->id,
        'name' => 'Test Tenant',
        'public_slug' => 'test-' . uniqid(),
        'access_level' => TenantAccessLevel::PRIVATE ,
    ], $attributes));

    // Attach owner as admin
    $tenant->users()->attach($owner->id, ['role' => 'admin']);

    return $tenant;
}

/**
 * Add X-Tenant-ID header to the test request.
 */
function withTenantHeader(int $tenantId): \Illuminate\Testing\TestResponse
{
    return test()->withHeader('X-Tenant-ID', $tenantId);
}
