<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\ContentItem;
use App\Services\Cms\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group CMS - Content Delivery
 *
 * Public content delivery API for consuming published CMS content.
 * Access is controlled by tenant access level (public, private, or token_protected).
 * No authentication required for public tenants. Use the tenant's public slug in the URL.
 *
 * ## Tenant Access Levels
 *
 * - **public**: Anyone can access content, no authentication needed
 * - **private**: Content is completely inaccessible via this API
 * - **token_protected**: Requires `Authorization: Bearer {tenant_public_api_key}` header
 *
 * ## URL Structure
 *
 * All endpoints use the pattern: `/api/content/cms/{tenant_slug}/...`
 * where `{tenant_slug}` is the tenant's public slug (e.g., "my-blog-abc123").
 */
class CmsContentController extends Controller
{
    /**
     * Get tenant info
     *
     * Returns basic information about the tenant. Useful for verifying tenant
     * slug and checking access.
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     *
     * @response 200 {
     *   "data": {
     *     "tenant": {
     *       "name": "My Blog",
     *       "slug": "my-blog-abc123"
     *     }
     *   }
     * }
     *
     * @response 403 {
     *   "message": "Access to this resource is private."
     * }
     *
     * @response 401 {
     *   "message": "Unauthorized."
     * }
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
     * List published content items of a specific type
     *
     * Returns a paginated list of published content items for any type (posts, pages, projects, etc.),
     * ordered by publish date (newest first). Only published content is returned.
     *
     * The type parameter accepts plural forms (posts, pages, projects) and automatically
     * converts to singular for database queries. This allows you to add new content types
     * without modifying code.
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     * @urlParam type string required The type of content (plural). Example: posts
     * @queryParam per_page int The number of items per page. Defaults to 15. Example: 10
     *
     * @response 200 {
     *   "current_page": 1,
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Getting Started with Laravel",
     *       "slug": "getting-started-with-laravel",
     *       "excerpt": "Learn the basics of Laravel",
     *       "body": "# Getting Started...",
     *       "type": "post",
     *       "status": "published",
     *       "published_at": "2025-01-22T10:00:00.000000Z",
     *       "created_at": "2025-01-22T09:00:00.000000Z",
     *       "updated_at": "2025-01-22T10:00:00.000000Z",
     *       "tags": [
     *         {"id": 1, "name": "Laravel", "slug": "laravel"}
     *       ]
     *     }
     *   ],
     *   "per_page": 15,
     *   "total": 42
     * }
     */
    public function listByType(Request $request, $tenant_slug, $type)
    {
        // Convert plural URL parameter (posts) to singular for DB query (post)
        $singularType = Str::singular($type);

        $items = ContentItem::published()
            ->ofType($singularType)
            ->with(['tags'])
            ->orderBy('published_at', 'desc')
            ->paginate($request->query('per_page', 15));

        return response()->json($items);
    }

    /**
     * Get a specific published content item
     *
     * Returns a single published content item by its type and slug, including tags
     * and approved comments (if applicable). Comments are ordered by creation date (newest first).
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     * @urlParam type string required The type of content (plural). Example: posts
     * @urlParam item_slug string required The slug of the content item. Example: getting-started-with-laravel
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Getting Started with Laravel",
     *     "slug": "getting-started-with-laravel",
     *     "excerpt": "Learn the basics of Laravel",
     *     "body": "# Getting Started\n\nLaravel is...",
     *     "type": "post",
     *     "status": "published",
     *     "published_at": "2025-01-22T10:00:00.000000Z",
     *     "meta": {},
     *     "created_at": "2025-01-22T09:00:00.000000Z",
     *     "updated_at": "2025-01-22T10:00:00.000000Z",
     *     "tags": [
     *       {"id": 1, "name": "Laravel", "slug": "laravel"}
     *     ],
     *     "comments": [
     *       {
     *         "id": 1,
     *         "body": "Great article!",
     *         "approved": true,
     *         "created_at": "2025-01-22T11:00:00.000000Z"
     *       }
     *     ]
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [ContentItem]."
     * }
     */
    public function showByType($tenant_slug, $type, $item_slug)
    {
        // Convert plural URL parameter (posts) to singular for DB query (post)
        $singularType = Str::singular($type);

        $item = ContentItem::published()
            ->ofType($singularType)
            ->where('slug', $item_slug)
            ->with([
                'tags',
                'comments' => function ($query) {
                    $query->approved()->orderBy('created_at', 'desc');
                }
            ])
            ->firstOrFail();

        return response()->json([
            'data' => $item
        ]);
    }

    /**
     * List tags with published content
     *
     * Returns all tags that have at least one published content item,
     * with a count of published items per tag. Ordered alphabetically.
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Laravel",
     *       "slug": "laravel",
     *       "created_at": "2025-01-20T10:00:00.000000Z",
     *       "updated_at": "2025-01-20T10:00:00.000000Z",
     *       "content_items_count": 8
     *     },
     *     {
     *       "id": 2,
     *       "name": "PHP",
     *       "slug": "php",
     *       "created_at": "2025-01-20T10:00:00.000000Z",
     *       "updated_at": "2025-01-20T10:00:00.000000Z",
     *       "content_items_count": 5
     *     }
     *   ]
     * }
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
     * Get content by tag
     *
     * Returns published content items for a specific tag, with pagination.
     * Ordered by publish date (newest first).
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     * @urlParam tag_slug string required The slug of the tag. Example: laravel
     *
     * @response 200 {
     *   "tag": {
     *     "id": 1,
     *     "name": "Laravel",
     *     "slug": "laravel",
     *     "created_at": "2025-01-20T10:00:00.000000Z",
     *     "updated_at": "2025-01-20T10:00:00.000000Z"
     *   },
     *   "content_items": {
     *     "current_page": 1,
     *     "data": [
     *       {
     *         "id": 1,
     *         "title": "Getting Started with Laravel",
     *         "slug": "getting-started-with-laravel",
     *         "excerpt": "Learn the basics",
     *         "status": "published",
     *         "published_at": "2025-01-22T10:00:00.000000Z",
     *         "tags": [
     *           {"id": 1, "name": "Laravel", "slug": "laravel"}
     *         ]
     *       }
     *     ],
     *     "per_page": 15,
     *     "total": 8
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [Tag]."
     * }
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
