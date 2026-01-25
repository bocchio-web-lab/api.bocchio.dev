<?php

use App\Models\User;
use App\Services\Cms\Models\Comment;
use App\Services\Cms\Models\ContentItem;
use App\Services\Cms\Models\Tag;
use App\Services\Platform\Models\Service;
use App\Services\Platform\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Platform Factories', function () {
    it('creates a service with factory', function () {
        $service = Service::factory()->create();

        expect($service)->toBeInstanceOf(Service::class)
            ->and($service->name)->not->toBeNull()
            ->and($service->slug)->not->toBeNull();
    });

    it('creates a CMS service', function () {
        $service = Service::factory()->cms()->create();

        expect($service->name)->toBe('Content Management System')
            ->and($service->slug)->toBe('cms')
            ->and($service->is_active)->toBeTrue();
    });

    it('creates a tenant with factory', function () {
        $tenant = Tenant::factory()->create();

        expect($tenant)->toBeInstanceOf(Tenant::class)
            ->and($tenant->name)->not->toBeNull()
            ->and($tenant->public_slug)->not->toBeNull()
            ->and($tenant->public_api_key)->toStartWith('pk_');
    });

    it('creates a public tenant', function () {
        $tenant = Tenant::factory()->public()->create();

        expect($tenant->access_level->value)->toBe('public');
    });

    it('creates a tenant with specific service and owner', function () {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $tenant = Tenant::factory()
            ->forService($service)
            ->ownedBy($user)
            ->create();

        expect($tenant->service_id)->toBe($service->id)
            ->and($tenant->owner_id)->toBe($user->id);
    });
});

describe('CMS Factories', function () {
    it('creates a content item with factory', function () {
        $contentItem = ContentItem::factory()->create();

        expect($contentItem)->toBeInstanceOf(ContentItem::class)
            ->and($contentItem->title)->not->toBeNull()
            ->and($contentItem->slug)->not->toBeNull()
            ->and($contentItem->body)->not->toBeNull();
    });

    it('creates a published post', function () {
        $post = ContentItem::factory()->post()->published()->create();

        expect($post->type)->toBe('post')
            ->and($post->status)->toBe('published')
            ->and($post->published_at)->not->toBeNull()
            ->and($post->isPublished())->toBeTrue();
    });

    it('creates a draft page', function () {
        $page = ContentItem::factory()->page()->draft()->create();

        expect($page->type)->toBe('page')
            ->and($page->status)->toBe('draft')
            ->and($page->published_at)->toBeNull()
            ->and($page->isPublished())->toBeFalse();
    });

    it('creates a project with metadata', function () {
        $project = ContentItem::factory()->project()->published()->create();

        expect($project->type)->toBe('project')
            ->and($project->meta)->toBeArray()
            ->and($project->meta)->toHaveKeys(['project_url', 'github_url', 'technologies']);
    });

    it('creates content for specific tenant', function () {
        $tenant = Tenant::factory()->create();
        $post = ContentItem::factory()->forTenant($tenant)->create();

        expect($post->tenant_id)->toBe($tenant->id);
    });

    it('creates a tag with factory', function () {
        $tag = Tag::factory()->create();

        expect($tag)->toBeInstanceOf(Tag::class)
            ->and($tag->name)->not->toBeNull()
            ->and($tag->slug)->not->toBeNull();
    });

    it('creates tags for specific tenant', function () {
        $tenant = Tenant::factory()->create();
        $tags = Tag::factory(5)->forTenant($tenant)->create();

        expect($tags)->toHaveCount(5)
            ->and($tags->pluck('tenant_id')->unique())->toHaveCount(1)
            ->and($tags->first()->tenant_id)->toBe($tenant->id);
    });

    it('creates a comment with factory', function () {
        $comment = Comment::factory()->create();

        expect($comment)->toBeInstanceOf(Comment::class)
            ->and($comment->body)->not->toBeNull();
    });

    it('creates an approved authenticated comment', function () {
        $comment = Comment::factory()->approved()->authenticated()->create();

        expect($comment->approved)->toBeTrue()
            ->and($comment->author_id)->not->toBeNull()
            ->and($comment->author_name)->toBeNull();
    });

    it('creates a pending guest comment', function () {
        $comment = Comment::factory()->pending()->guest()->create();

        expect($comment->approved)->toBeFalse()
            ->and($comment->author_id)->toBeNull()
            ->and($comment->author_name)->not->toBeNull();
    });

    it('creates comments for specific content', function () {
        $post = ContentItem::factory()->published()->create();
        $comments = Comment::factory(3)->forContent($post)->create();

        expect($comments)->toHaveCount(3)
            ->and($comments->pluck('content_item_id')->unique())->toHaveCount(1)
            ->and($comments->first()->content_item_id)->toBe($post->id);
    });
});

describe('Complete Blog Workflow', function () {
    it('creates a complete blog structure', function () {
        // Create owner
        $owner = User::factory()->create(['email' => 'owner@blog.com']);

        // Create CMS service
        $cms = Service::factory()->cms()->create();

        // Create tenant
        $tenant = Tenant::factory()
            ->forService($cms)
            ->ownedBy($owner)
            ->public()
            ->create(['name' => 'Test Blog']);

        // Create tags
        $tags = Tag::factory(5)->forTenant($tenant)->create();

        // Create posts
        $posts = ContentItem::factory(10)
            ->forTenant($tenant)
            ->byAuthor($owner)
            ->post()
            ->published()
            ->create();

        // Attach tags
        $posts->each(fn($post) => $post->tags()->attach($tags->random(3)));

        // Create comments
        $posts->random(5)->each(function ($post) {
            Comment::factory(3)
                ->forContent($post)
                ->approved()
                ->create();
        });

        // Verify everything was created
        expect($owner->exists())->toBeTrue()
            ->and($cms->slug)->toBe('cms')
            ->and($tenant->name)->toBe('Test Blog')
            ->and($tenant->owner_id)->toBe($owner->id)
            ->and($tags)->toHaveCount(5)
            ->and($posts)->toHaveCount(10)
            ->and($posts->first()->tags)->toHaveCount(3)
            ->and(Comment::where('tenant_id', $tenant->id)->count())->toBeGreaterThanOrEqual(15);
    });
});
