<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\ContentItem;
use App\Services\Cms\Models\Tag;
use Illuminate\Http\Request;

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
     * List published posts
     *
     * Returns a paginated list of published blog posts, ordered by publish date (newest first).
     * Only published content is returned; drafts and archived posts are excluded.
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     *
     * @response 200 {
     *   "current_page": 1,
     *   "data": [
     *     {
     *       "id": 1,
     *       "type": "post",
     *       "title": "Getting Started with Laravel",
     *       "slug": "getting-started-with-laravel",
     *       "excerpt": "Learn the basics of Laravel",
     *       "body": "# Getting Started...",
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
     * Get a published post
     *
     * Returns a single published post by its slug, including tags and approved comments.
     * Comments are ordered by creation date (newest first).
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     * @urlParam post_slug string required The slug of the post. Example: getting-started-with-laravel
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "type": "post",
     *     "title": "Getting Started with Laravel",
     *     "slug": "getting-started-with-laravel",
     *     "excerpt": "Learn the basics of Laravel",
     *     "body": "# Getting Started\n\nLaravel is...",
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
     *
     * Returns all published pages, ordered alphabetically by title.
     * Pages are typically static content like "About" or "Contact".
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 2,
     *       "type": "page",
     *       "title": "About",
     *       "slug": "about",
     *       "body": "About us...",
     *       "status": "published",
     *       "published_at": "2025-01-20T10:00:00.000000Z"
     *     },
     *     {
     *       "id": 3,
     *       "type": "page",
     *       "title": "Contact",
     *       "slug": "contact",
     *       "body": "Contact information...",
     *       "status": "published",
     *       "published_at": "2025-01-20T10:00:00.000000Z"
     *     }
     *   ]
     * }
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
     * Get a published page
     *
     * Returns a single published page by its slug.
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     * @urlParam page_slug string required The slug of the page. Example: about
     *
     * @response 200 {
     *   "data": {
     *     "id": 2,
     *     "type": "page",
     *     "title": "About",
     *     "slug": "about",
     *     "body": "About us content...",
     *     "status": "published",
     *     "published_at": "2025-01-20T10:00:00.000000Z",
     *     "meta": {},
     *     "created_at": "2025-01-20T09:00:00.000000Z",
     *     "updated_at": "2025-01-20T10:00:00.000000Z"
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [ContentItem]."
     * }
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
     *
     * Returns all published projects with tags, ordered by publish date (newest first).
     * Projects are portfolio items or case studies.
     *
     * @unauthenticated
     *
     * @urlParam tenant_slug string required The public slug of the tenant. Example: my-blog-abc123
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 4,
     *       "type": "project",
     *       "title": "E-Commerce Platform",
     *       "slug": "ecommerce-platform",
     *       "excerpt": "A full-featured online store",
     *       "body": "Project details...",
     *       "status": "published",
     *       "published_at": "2025-01-21T10:00:00.000000Z",
     *       "tags": [
     *         {"id": 2, "name": "Laravel", "slug": "laravel"},
     *         {"id": 3, "name": "Vue.js", "slug": "vuejs"}
     *       ]
     *     }
     *   ]
     * }
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
     *         "type": "post",
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
