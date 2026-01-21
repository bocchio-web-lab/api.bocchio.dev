<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\Tag;
use Illuminate\Http\Request;

/**
 * @group CMS - Management
 *
 * Manage tags for organizing content within a CMS tenant.
 * Tags can be attached to content items for categorization and filtering.
 */
class TagController extends Controller
{
    /**
     * List tags
     *
     * Returns all tags in the current tenant with content item counts.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "tenant_id": 1,
     *       "name": "Laravel",
     *       "slug": "laravel",
     *       "created_at": "2025-01-20T10:00:00.000000Z",
     *       "updated_at": "2025-01-20T10:00:00.000000Z",
     *       "content_items_count": 12
     *     },
     *     {
     *       "id": 2,
     *       "tenant_id": 1,
     *       "name": "PHP",
     *       "slug": "php",
     *       "created_at": "2025-01-20T10:00:00.000000Z",
     *       "updated_at": "2025-01-20T10:00:00.000000Z",
     *       "content_items_count": 8
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $tags = Tag::withCount('contentItems')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $tags
        ]);
    }

    /**
     * Create tag
     *
     * Creates a new tag. If no slug is provided, one will be auto-generated from the name.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @bodyParam name string required The tag name. Example: Vue.js
     * @bodyParam slug string Optional URL-friendly slug. Example: vuejs
     *
     * @response 201 {
     *   "data": {
     *     "id": 3,
     *     "tenant_id": 1,
     *     "name": "Vue.js",
     *     "slug": "vuejs",
     *     "created_at": "2025-01-22T10:00:00.000000Z",
     *     "updated_at": "2025-01-22T10:00:00.000000Z"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'sometimes|string|max:255',
        ]);

        $tag = Tag::create($validated);

        return response()->json([
            'data' => $tag
        ], 201);
    }

    /**
     * Get tag with content
     *
     * Returns a specific tag with all associated content items.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam tag integer required The ID of the tag. Example: 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "tenant_id": 1,
     *     "name": "Laravel",
     *     "slug": "laravel",
     *     "created_at": "2025-01-20T10:00:00.000000Z",
     *     "updated_at": "2025-01-20T10:00:00.000000Z",
     *     "content_items": [
     *       {
     *         "id": 1,
     *         "title": "Getting Started with Laravel",
     *         "slug": "getting-started-with-laravel",
     *         "type": "post",
     *         "status": "published"
     *       }
     *     ]
     *   }
     * }
     */
    public function show(Tag $tag)
    {
        return response()->json([
            'data' => $tag->load('contentItems')
        ]);
    }

    /**
     * Update tag
     *
     * Updates a tag's name or slug.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam tag integer required The ID of the tag. Example: 1
     *
     * @bodyParam name string The tag name. Example: Laravel 11
     * @bodyParam slug string URL-friendly slug. Example: laravel-11
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "Laravel 11",
     *     "slug": "laravel-11",
     *     "updated_at": "2025-01-22T11:00:00.000000Z"
     *   }
     * }
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255',
        ]);

        $tag->update($validated);

        return response()->json([
            'data' => $tag->fresh()
        ]);
    }

    /**
     * Delete tag
     *
     * Permanently deletes a tag. Content items associated with this tag will remain
     * but will no longer be tagged with it.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam tag integer required The ID of the tag. Example: 1
     *
     * @response 200 {
     *   "message": "Tag deleted successfully"
     * }
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully'
        ], 200);
    }
}
