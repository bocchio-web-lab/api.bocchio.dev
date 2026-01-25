<?php

use App\Services\Cms\Models\ContentItem;

test('same slug allowed across tenants', function () {
    $userA = actingAsUser(['email' => 'userA@test.com']);
    $tenantA = createTenantForService('cms', $userA);

    $userB = actingAsUser(['email' => 'userB@test.com']);
    $tenantB = createTenantForService('cms', $userB);

    // Create content with same slug in tenant A
    app()->instance('current_tenant_id', $tenantA->id);
    app()->instance('current_tenant', $tenantA);

    $contentA = ContentItem::create([
        'tenant_id' => $tenantA->id,
        'author_id' => $userA->id,
        'title' => 'Hello World',
        'slug' => 'hello-world',
        'body' => 'Content A',
        'status' => 'published',
        'published_at' => now(),
    ]);

    // Create content with same slug in tenant B (should work)
    app()->instance('current_tenant_id', $tenantB->id);
    app()->instance('current_tenant', $tenantB);

    $contentB = ContentItem::create([
        'tenant_id' => $tenantB->id,
        'author_id' => $userB->id,
        'title' => 'Hello World',
        'slug' => 'hello-world',
        'body' => 'Content B',
        'status' => 'published',
        'published_at' => now(),
    ]);

    expect($contentA->slug)->toBe('hello-world');
    expect($contentB->slug)->toBe('hello-world');
    expect($contentA->tenant_id)->not->toBe($contentB->tenant_id);
});

test('slug is unique within same tenant', function () {
    $user = actingAsUser();
    $tenant = createTenantForService('cms', $user);

    app()->instance('current_tenant_id', $tenant->id);
    app()->instance('current_tenant', $tenant);

    // Create first content
    $content1 = ContentItem::create([
        'tenant_id' => $tenant->id,
        'author_id' => $user->id,
        'title' => 'Hello World',
        'body' => 'Content 1',
        'status' => 'published',
        'published_at' => now(),
    ]);

    expect($content1->slug)->toBe('hello-world');

    // Create second content with same title (slug should auto-increment)
    $content2 = ContentItem::create([
        'tenant_id' => $tenant->id,
        'author_id' => $user->id,
        'title' => 'Hello World',
        'body' => 'Content 2',
        'status' => 'published',
        'published_at' => now(),
    ]);

    expect($content2->slug)->toBe('hello-world-1');
});

test('auto-generated slug is unique', function () {
    $user = actingAsUser();
    $tenant = createTenantForService('cms', $user);

    app()->instance('current_tenant_id', $tenant->id);
    app()->instance('current_tenant', $tenant);

    // Create multiple contents with same title
    for ($i = 0; $i < 3; $i++) {
        ContentItem::create([
            'tenant_id' => $tenant->id,
            'author_id' => $user->id,
            'title' => 'Test Post',
            'body' => "Content $i",
            'status' => 'draft',
        ]);
    }

    $contents = ContentItem::withoutTenantScope()->where('tenant_id', $tenant->id)->get();

    expect($contents)->toHaveCount(3);
    expect($contents->pluck('slug')->toArray())->toBe([
        'test-post',
        'test-post-1',
        'test-post-2',
    ]);
});
