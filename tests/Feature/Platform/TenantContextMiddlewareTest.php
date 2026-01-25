<?php

use App\Models\User;
use App\Services\Platform\Models\Service;
use App\Services\Platform\Models\Tenant;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

// use RefreshDatabase;

// --- Setup ---
beforeEach(function () {
    // Create User
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);

    // Get seeded services (already created by ServiceSeeder in TestCase)
    $this->cmsService = Service::where('slug', 'cms')->first();
    $this->gameService = Service::where('slug', 'game')->first();

    // Create a CMS tenant for the user
    $this->cmsTenant = $this->user->tenants()->create([
        'name' => 'My CMS Tenant',
        'service_id' => $this->cmsService->id,
        'owner_id' => $this->user->id,
        'public_slug' => 'cms-tenant-' . $this->user->id . '-' . time(),
    ]);

    // Create a Game tenant for the user
    $this->gameTenant = $this->user->tenants()->create([
        'name' => 'My Game Tenant',
        'service_id' => $this->gameService->id,
        'owner_id' => $this->user->id,
        'public_slug' => 'game-tenant-' . $this->user->id . '-' . time(),
    ]);
});

// --- Tests ---

test('it grants access with the correct tenant and service', function () {
    // Act: Access the 'cms' route with the 'cms' tenant ID
    $response = $this->withHeaders([
        'X-Tenant-ID' => $this->cmsTenant->id
    ])->getJson('/api/manage/cms/middleware-test');

    // Assert
    $response->assertOk()
        ->assertJsonFragment([
            'message' => 'CMS route success!',
            'tenant_id' => $this->cmsTenant->id
        ]);
});

test('it forbids access if the X-Tenant-ID header is missing', function () {
    // Act: Access without the header
    $response = $this->getJson('/api/manage/cms/middleware-test');

    // Assert
    $response->assertBadRequest() // 400
        ->assertJsonFragment(['message' => 'X-Tenant-ID header is missing.']);
});

test('it forbids access if the tenant ID is for the wrong service', function () {
    // Act: Access the 'cms' route with the 'game' tenant ID
    $response = $this->withHeaders([
        'X-Tenant-ID' => $this->gameTenant->id
    ])->getJson('/api/manage/cms/middleware-test');

    // Assert
    $response->assertForbidden() // 403
        ->assertJsonFragment(['message' => 'Forbidden. You do not have access to this tenant or service.']);
});

test('it forbids access if the user does not belong to the tenant', function () {
    // Arrange: Create a tenant owned by someone else
    $otherUser = User::factory()->create();
    $otherTenant = $otherUser->tenants()->create([
        'name' => 'Another User Tenant',
        'service_id' => $this->cmsService->id,
        'owner_id' => $otherUser->id,
        'public_slug' => 'other-tenant-' . time(),
    ]);

    // Act: Try to access the 'cms' route with *their* tenant ID
    $response = $this->withHeaders([
        'X-Tenant-ID' => $otherTenant->id
    ])->getJson('/api/manage/cms/middleware-test');

    // Assert
    $response->assertForbidden() // 403
        ->assertJsonFragment(['message' => 'Forbidden. You do not have access to this tenant or service.']);
});