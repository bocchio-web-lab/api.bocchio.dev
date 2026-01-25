<?php

use App\Services\Cms\Models\ContentItem;
use App\Services\Platform\Enums\TenantAccessLevel;

test('tenant A cannot see tenant B content', function () {
    $userA = actingAsUser(['email' => 'userA@test.com']);
    $tenantA = createTenantForService('cms', $userA);

    $userB = actingAsUser(['email' => 'userB@test.com']);
    $tenantB = createTenantForService('cms', $userB);

    // Create content for tenant A
    app()->instance('current_tenant_id', $tenantA->id);
    app()->instance('current_tenant', $tenantA);

    $contentA = ContentItem::create([
        'tenant_id' => $tenantA->id,
        'author_id' => $userA->id,
        'title' => 'Tenant A Post',
        'body' => 'Content for tenant A',
        'status' => 'published',
        'published_at' => now(),
    ]);

    // Switch to tenant B context
    app()->instance('current_tenant_id', $tenantB->id);
    app()->instance('current_tenant', $tenantB);

    // Tenant B should not see tenant A's content
    $tenantBContent = ContentItem::all();
    expect($tenantBContent)->toHaveCount(0);

    // Create content for tenant B
    $contentB = ContentItem::create([
        'tenant_id' => $tenantB->id,
        'author_id' => $userB->id,
        'title' => 'Tenant B Post',
        'body' => 'Content for tenant B',
        'status' => 'published',
        'published_at' => now(),
    ]);

    // Tenant B should only see their own content
    $tenantBContent = ContentItem::all();
    expect($tenantBContent)->toHaveCount(1);
    expect($tenantBContent->first()->id)->toBe($contentB->id);
});

test('draft is invisible to public', function () {
    $user = actingAsUser();
    $tenant = createTenantForService('cms', $user, [
        'access_level' => TenantAccessLevel::PUBLIC ,
    ]);

    app()->instance('current_tenant_id', $tenant->id);
    app()->instance('current_tenant', $tenant);

    // Create draft content
    $draft = ContentItem::create([
        'tenant_id' => $tenant->id,
        'author_id' => $user->id,
        'title' => 'Draft Post',
        'body' => 'This is a draft',
        'status' => 'draft',
    ]);

    // Public API should not return drafts
    $response = $this->getJson("/api/content/cms/{$tenant->public_slug}/posts");

    $response->assertStatus(200);
    $response->assertJsonCount(0, 'data');
});

test('published content is visible', function () {
    $user = actingAsUser();
    $tenant = createTenantForService('cms', $user, [
        'access_level' => TenantAccessLevel::PUBLIC ,
    ]);

    app()->instance('current_tenant_id', $tenant->id);
    app()->instance('current_tenant', $tenant);

    // Create published content
    $published = ContentItem::create([
        'tenant_id' => $tenant->id,
        'author_id' => $user->id,
        'title' => 'Published Post',
        'body' => 'This is published',
        'status' => 'published',
        'published_at' => now()->subHour(),
    ]);

    // Public API should return published content
    $response = $this->getJson("/api/content/cms/{$tenant->public_slug}/posts");

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.title', 'Published Post');
});
