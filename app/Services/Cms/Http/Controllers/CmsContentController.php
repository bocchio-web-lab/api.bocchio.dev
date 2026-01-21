<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\ContentItem;
use App\Services\Cms\Models\Tag;
use Illuminate\Http\Request;

/**
 * CMS Content Delivery Controller
 *
 * This controller handles public/semi-public content delivery.
 * All routes using this controller should be protected by:
 * - tenant.public_access:cms middleware
 *
 * No Sanctum authentication required.
 */
class CmsContentController extends Controller
{
    /**
     * Get public tenant information
     */
    public function index()
    {
        $tenant = app('current_tenant');

        return response()->json([
            'data' => [
                'tenant' => [
                    'name' => $tenant->name,
                    'slug' => $tenant->public_slug,
                ],
            ]
        ]);
    }

    /**
     * List published posts
     */
    public function listPosts(Request $request)
    {
        $posts = ContentItem::published()
            ->ofType('post')
            ->with(['tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json($posts);
    }

    /**
     * Get a single published post by slug
     */
    public function showPost($tenant_slug, $post_slug)
    {
        $post = ContentItem::published()
            ->ofType('post')
            ->where('slug', $post_slug)
            ->with([
                'tags',
                'comments' => function ($query) {
                    $query->approved()->orderBy('created_at', 'desc');
                }
            ])
            ->firstOrFail();

        return response()->json([
            'data' => $post
        ]);
    }

    /**
     * List published pages
     */
    public function listPages()
    {
        $pages = ContentItem::published()
            ->ofType('page')
            ->orderBy('title')
            ->get();

        return response()->json([
            'data' => $pages
        ]);
    }

    /**
     * Get a single published page by slug
     */
    public function showPage($tenant_slug, $page_slug)
    {
        $page = ContentItem::published()
            ->ofType('page')
            ->where('slug', $page_slug)
            ->firstOrFail();

        return response()->json([
            'data' => $page
        ]);
    }

    /**
     * List published projects
     */
    public function listProjects()
    {
        $projects = ContentItem::published()
            ->ofType('project')
            ->with(['tags'])
            ->orderBy('published_at', 'desc')
            ->get();

        return response()->json([
            'data' => $projects
        ]);
    }

    /**
     * Get all tags with published content count
     */
    public function listTags()
    {
        $tags = Tag::whereHas('contentItems', function ($query) {
            $query->published();
        })
            ->withCount([
                'contentItems' => function ($query) {
                    $query->published();
                }
            ])
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $tags
        ]);
    }

    /**
     * Get published content for a specific tag
     */
    public function showTag($tenant_slug, $tag_slug)
    {
        $tag = Tag::where('slug', $tag_slug)->firstOrFail();

        $contentItems = $tag->contentItems()
            ->published()
            ->with(['tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json([
            'tag' => $tag,
            'content_items' => $contentItems
        ]);
    }
}
