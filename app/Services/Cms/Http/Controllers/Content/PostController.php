<?php

namespace App\Services\Cms\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\ContentItem;

class PostController extends Controller
{
    /**
     * Display a listing of *published* posts for the current tenant.
     * The TenantScope automatically filters by tenant_id.
     */
    public function index()
    {
        return ContentItem::where('type', 'post')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(10);
    }

    /**
     * Display a single specified post.
     * TenantScope automatically filters by tenant_id.
     */
    public function show($tenant_slug, $post_slug)
    {
        $post = ContentItem::where('type', 'post')
            ->where('slug', $post_slug)
            ->where('status', 'published')
            ->firstOrFail();

        return $post->load('author:id,name', 'tags', 'comments');
    }
}