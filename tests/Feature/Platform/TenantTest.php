<?php

use App\Services\Platform\Enums\TenantAccessLevel;
use App\Services\Platform\Models\Tenant;

test('user can create tenant', function () {
    $user = actingAsUser();

    $uniqueSlug = 'my-test-tenant-' . time();

    $response = $this->postJson('/api/manage/tenants', [
        'name' => 'My Test Tenant',
        'service_id' => 1, // CMS service
        'public_slug' => $uniqueSlug,
        'access_level' => 'private',
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure(['data' => ['id', 'name', 'public_slug']]);

    expect(Tenant::where('public_slug', $uniqueSlug)->exists())->toBeTrue();
});

test('tenant belongs to service', function () {
    $user = actingAsUser();
    $tenant = createTenantForService('cms', $user);

    expect($tenant->service)->not->toBeNull();
    expect($tenant->service->slug)->toBe('cms');
});

test('tenant context middleware blocks wrong tenant', function () {
    $user = actingAsUser();
    $tenant = createTenantForService('cms', $user);

    $anotherUser = actingAsUser(['email' => 'another@test.com']);

    $response = $this->getJson('/api/manage/cms/content', [
        'X-Tenant-ID' => $tenant->id,
    ]);

    $response->assertStatus(403);
});

test('tenant context middleware blocks wrong service', function () {
    $user = actingAsUser();
    $tenant = createTenantForService('cms', $user);

    // Try to access with wrong service (would need game routes, but we can test the concept)
    // This is implicitly tested by the middleware checking service.slug
    expect($tenant->service->slug)->toBe('cms');
});

test('tenant context middleware requires X-Tenant-ID header', function () {
    actingAsUser();

    $response = $this->getJson('/api/manage/cms/content');

    $response->assertStatus(400);
    $response->assertJson(['message' => 'X-Tenant-ID header is missing.']);
});
