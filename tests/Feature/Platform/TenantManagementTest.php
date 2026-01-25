<?php

use App\Models\User;
use App\Services\Platform\Models\Service;
// use Illuminate\Foundation\Testing\RefreshDatabase; // This is key!
use Laravel\Sanctum\Sanctum;

// We use RefreshDatabase. Because of our phpunit.xml setup,
// this will create all our tables in memory for each test.
// use RefreshDatabase;

// --- Setup ---
// This 'beforeEach' runs before every test in this file
beforeEach(function () {
    // 1. Get the seeded 'cms' service (already created by ServiceSeeder in TestCase)
    $this->cmsService = Service::where('slug', 'cms')->first();

    // 2. Create a user in the identity DB
    $this->user = User::factory()->create();

    // 3. Authenticate this user for all subsequent API requests
    Sanctum::actingAs($this->user);
});

// --- Tests ---

test('a user can list available services', function () {
    // Act
    $response = $this->getJson('/api/manage/services');

    // Assert
    $response->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['slug' => 'cms']);
});

test('a user can create a new tenant', function () {
    // Arrange
    $tenantData = [
        'name' => 'My Test Blog',
        'service_id' => $this->cmsService->id,
    ];

    // Act
    $response = $this->postJson('/api/manage/tenants', $tenantData);

    // Assert
    $response->assertCreated()
        ->assertJsonFragment(['name' => 'My Test Blog']);

    // Assert the tenant was created in the 'platform_db'
    $this->assertDatabaseHas('tenants', [
        'name' => 'My Test Blog',
        'owner_id' => $this->user->id,
        'service_id' => $this->cmsService->id
    ], 'platform_db');

    // Assert the pivot record was created in 'platform_db'
    $this->assertDatabaseHas('tenant_user', [
        'user_id' => $this->user->id,
        'role' => 'admin'
    ], 'platform_db');
});

test('a user can list their own tenants', function () {
    // Arrange: Create a tenant for the user
    $tenant = $this->user->tenants()->create([
        'name' => 'My Only Tenant',
        'service_id' => $this->cmsService->id,
        'owner_id' => $this->user->id,
        'public_slug' => 'my-only-tenant-' . time(),
    ]);

    // Act
    $response = $this->getJson('/api/manage/tenants');

    // Assert
    $response->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['name' => 'My Only Tenant']);
});

test('a user can update their own tenant', function () {
    // Arrange: Create a tenant
    $tenant = $this->user->tenants()->create([
        'name' => 'Old Name',
        'service_id' => $this->cmsService->id,
        'owner_id' => $this->user->id,
        'public_slug' => 'old-name-' . time(),
    ]);

    // Act
    $response = $this->putJson("/api/manage/tenants/{$tenant->id}", [
        'name' => 'New Name',

        'access_level' => 'public'
    ]);

    // Assert
    $response->assertOk()
        ->assertJsonFragment(['name' => 'New Name', 'access_level' => 'public']);

    $this->assertDatabaseHas('tenants', [
        'id' => $tenant->id,
        'name' => 'New Name'
    ], 'platform_db');
});

test('a user cannot update a tenant they do not own', function () {
    // Arrange: Create a tenant owned by a *different* user
    $otherUser = User::factory()->create();
    $otherTenant = $otherUser->tenants()->create([
        'name' => 'Other Person Tenant',
        'service_id' => $this->cmsService->id,
        'owner_id' => $otherUser->id,
        'public_slug' => 'other-person-' . time(),
    ]);

    // Act: Try to update it as $this->user
    $response = $this->putJson("/api/manage/tenants/{$otherTenant->id}", [
        'name' => 'Hacked Name'
    ]);

    // Assert: We get a 403 Forbidden
    $response->assertForbidden();
});

test('an unauthenticated user cannot access any routes', function () {
    // Force a fresh request without inheriting authentication
    $this->app->forgetInstance('auth');
    $this->withoutMiddleware(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);

    $this->getJson('/api/manage/services')->assertUnauthorized();
});