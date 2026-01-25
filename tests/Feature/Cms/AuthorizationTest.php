<?php

use App\Services\Cms\Models\ContentItem;

test('non-member cannot manage CMS', function () {
    $owner = actingAsUser(['email' => 'owner@test.com']);
    $tenant = createTenantForService('cms', $owner);

    $nonMember = actingAsUser(['email' => 'nonmember@test.com']);

    $response = $this->withHeader('X-Tenant-ID', $tenant->id)
        ->getJson('/api/manage/cms/content');

    $response->assertStatus(403);
});

test('member can create content', function () {
    $user = actingAsUser();
    $tenant = createTenantForService('cms', $user);

    $response = $this->withHeader('X-Tenant-ID', $tenant->id)
        ->postJson('/api/manage/cms/content', [
            'title' => 'My First Post',
            'body' => 'This is the body of my first post',
            'status' => 'draft',
        ]);

    $response->assertStatus(201);
    $response->assertJsonPath('data.title', 'My First Post');

    expect(ContentItem::count())->toBe(1);
});

test('non-author cannot delete content', function () {
    $author = actingAsUser(['email' => 'author@test.com']);
    $tenant = createTenantForService('cms', $author);

    app()->instance('current_tenant_id', $tenant->id);
    app()->instance('current_tenant', $tenant);

    $content = ContentItem::create([
        'tenant_id' => $tenant->id,
        'author_id' => $author->id,
        'title' => 'Author Post',
        'body' => 'Content',
        'status' => 'draft',
    ]);

    // Add another member to the tenant
    $otherMember = actingAsUser(['email' => 'member@test.com']);
    $tenant->users()->attach($otherMember->id, ['role' => 'member']);

    // Other member tries to delete
    $response = $this->withHeader('X-Tenant-ID', $tenant->id)
        ->deleteJson("/api/manage/cms/content/{$content->id}");

    $response->assertStatus(403);

    expect(ContentItem::count())->toBe(1);
});

test('author can delete own content', function () {
    $author = actingAsUser();
    $tenant = createTenantForService('cms', $author);

    app()->instance('current_tenant_id', $tenant->id);
    app()->instance('current_tenant', $tenant);

    $content = ContentItem::create([
        'tenant_id' => $tenant->id,
        'author_id' => $author->id,
        'title' => 'Author Post',
        'body' => 'Content',
        'status' => 'draft',
    ]);

    $response = $this->withHeader('X-Tenant-ID', $tenant->id)
        ->deleteJson("/api/manage/cms/content/{$content->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Content item deleted successfully']);
});

test('tenant owner can delete any content', function () {
    $owner = actingAsUser(['email' => 'owner@test.com']);
    $tenant = createTenantForService('cms', $owner);

    // Add another member who creates content
    $member = actingAsUser(['email' => 'member@test.com']);
    $tenant->users()->attach($member->id, ['role' => 'member']);

    app()->instance('current_tenant_id', $tenant->id);
    app()->instance('current_tenant', $tenant);

    $content = ContentItem::create([
        'tenant_id' => $tenant->id,
        'author_id' => $member->id,
        'title' => 'Member Post',
        'body' => 'Content',
        'status' => 'draft',
    ]);

    // Owner (not author) deletes
    $this->actingAs($owner, 'sanctum');

    $response = $this->withHeader('X-Tenant-ID', $tenant->id)
        ->deleteJson("/api/manage/cms/content/{$content->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Content item deleted successfully']);
});
