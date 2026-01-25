<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\ContentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group CMS - Management
 *
 * Manage content items within a CMS tenant.
 * All endpoints require authentication and the `X-Tenant-ID` header.
 * Content is isolated by tenant - you can only manage content in tenants you own or are a member of.
 */
class ContentItemController extends Controller
{
    /**
     * List content items
     *
     * Returns a paginated list of content items in the current tenant.
     * You can filter by type (custom string) and status (draft, published, archived).
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @queryParam type string Filter by content type: post, page, or project. Example: post
     * @queryParam status string Filter by status: draft, published, or archived. Example: published
     *
     * @response 200 {
     *   "current_page": 1,
     *   "data": [
     *     {
     *       "id": 1,
     *       "tenant_id": 1,
     *       "type": "post",
     *       "title": "My First Blog Post",
     *       "slug": "my-first-blog-post",
     *       "excerpt": "This is a short excerpt",
     *       "body": "Full content goes here...",
     *       "status": "published",
     *       "author_id": 1,
     *       "published_at": "2025-01-22T10:00:00.000000Z",
     *       "meta": {},
     *       "created_at": "2025-01-22T09:00:00.000000Z",
     *       "updated_at": "2025-01-22T10:00:00.000000Z",
     *       "author": {
     *         "id": 1,
     *         "name": "John Doe",
     *         "email": "john@example.com"
     *       },
     *       "tags": [
     *         {
     *           "id": 1,
     *           "name": "Laravel",
     *           "slug": "laravel"
     *         }
     *       ]
     *     }
     *   ],
     *   "per_page": 15,
     *   "total": 25
     * }
     */
    public function index(Request $request)
    {
        $query = ContentItem::with(['tags'])
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->withStatus($request->status);
        }

        $contentItems = $query->paginate(15);

        // Manually add authors from identity_db for each item
        $authorIds = $contentItems->pluck('author_id')->unique()->filter();
        $authors = \App\Models\User::on('identity_db')
            ->whereIn('id', $authorIds)
            ->get()
            ->keyBy('id');

        // Transform the collection to add author data
        $contentItems->getCollection()->transform(function ($item) use ($authors) {
            $itemArray = $item->toArray();
            $itemArray['author'] = $authors->get($item->author_id);
            return $itemArray;
        });

        return response()->json($contentItems);
    }

    /**
     * Create content item
     *
     * Creates a new content item. The authenticated user
     * becomes the author. You can optionally attach tags and set the status.
     * If no slug is provided, one will be auto-generated from the title.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @bodyParam type string Custom content type. Example: post
     * @bodyParam title string required The title of the content. Example: Getting Started with Laravel
     * @bodyParam slug string Optional URL-friendly slug. Auto-generated if not provided. Example: getting-started-with-laravel
     * @bodyParam excerpt string Short summary or excerpt. Example: Learn the basics of Laravel
     * @bodyParam body string required The full content body (HTML/Markdown). Example: # Getting Started\n\nLaravel is amazing...
     * @bodyParam status string Status: draft, published, or archived. Default is draft. Example: published
     * @bodyParam published_at string Publish date in ISO format. Example: 2025-01-22T10:00:00Z
     * @bodyParam meta object Custom metadata as key-value pairs. Example: {"featured": true}
     * @bodyParam tags integer[] Array of tag IDs to attach. Example: [1, 2, 3]
     *
     * @response 201 {
     *   "data": {
     *     "id": 5,
     *     "tenant_id": 1,
     *     "type": "post",
     *     "title": "Getting Started with Laravel",
     *     "slug": "getting-started-with-laravel",
     *     "excerpt": "Learn the basics of Laravel",
     *     "body": "# Getting Started...",
     *     "status": "published",
     *     "author_id": 1,
     *     "published_at": "2025-01-22T10:00:00.000000Z",
     *     "meta": {"featured": true},
     *     "created_at": "2025-01-22T09:30:00.000000Z",
     *     "updated_at": "2025-01-22T09:30:00.000000Z",
     *     "author": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com"
     *     },
     *     "tags": [
     *       {"id": 1, "name": "Laravel", "slug": "laravel"},
     *       {"id": 2, "name": "PHP", "slug": "php"}
     *     ]
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'slug' => 'sometimes|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'status' => 'sometimes|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta' => 'nullable|array',
            'tags' => 'sometimes|array',
            'tags.*' => 'integer|exists:cms_db.tags,id',
        ]);

        $validated['author_id'] = Auth::id();

        $contentItem = ContentItem::create($validated);

        // Attach tags if provided
        if (isset($validated['tags'])) {
            $contentItem->tags()->sync($validated['tags']);
        }

        // Refresh and load relationships
        $contentItem->refresh();
        $contentItem->loadMissing(['tags']);

        // Manually add author from identity_db
        $author = \App\Models\User::on('identity_db')->find($contentItem->author_id);
        $contentItemArray = $contentItem->toArray();
        $contentItemArray['author'] = $author;

        return response()->json([
            'data' => $contentItemArray
        ], 201);
    }

    /**
     * Get content item
     *
     * Returns detailed information about a specific content item, including
     * author details, tags, and comments.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam contentItem integer required The ID of the content item. Example: 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "tenant_id": 1,
     *     "type": "post",
     *     "title": "My First Blog Post",
     *     "slug": "my-first-blog-post",
     *     "excerpt": "This is a short excerpt",
     *     "body": "Full content goes here...",
     *     "status": "published",
     *     "author_id": 1,
     *     "published_at": "2025-01-22T10:00:00.000000Z",
     *     "meta": {},
     *     "created_at": "2025-01-22T09:00:00.000000Z",
     *     "updated_at": "2025-01-22T10:00:00.000000Z",
     *     "author": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com"
     *     },
     *     "tags": [],
     *     "comments": []
     *   }
     * }
     */
    public function show($id)
    {
        // Fetch manually to ensure the BelongsToTenant scope and cms_db connection are applied
        $contentItem = ContentItem::with(['tags', 'comments'])->findOrFail($id);

        // Manually add author from identity_db
        $author = \App\Models\User::on('identity_db')->find($contentItem->author_id);

        $data = $contentItem->toArray();
        $data['author'] = $author;

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Update content item
     *
     * Updates an existing content item. Only the author or tenant owner can update content.
     * You can update any field including title, body, status, and tags.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam contentItem integer required The ID of the content item. Example: 1
     *
     * @bodyParam title string The title. Example: Updated Title
     * @bodyParam slug string URL-friendly slug. Example: updated-title
     * @bodyParam excerpt string Short summary. Example: Updated excerpt
     * @bodyParam body string The content body. Example: Updated content...
     * @bodyParam status string Status: draft, published, or archived. Example: published
     * @bodyParam published_at string Publish date. Example: 2025-01-22T10:00:00Z
     * @bodyParam meta object Custom metadata. Example: {"featured": false}
     * @bodyParam tags integer[] Tag IDs to attach. Example: [1, 3]
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Updated Title",
     *     "slug": "updated-title",
     *     "status": "published",
     *     "updated_at": "2025-01-22T11:00:00.000000Z"
     *   }
     * }
     *
     * @response 403 {
     *   "message": "You do not have permission to update this content."
     * }
     */
    public function update(Request $request, $id)
    {
        // Fetch manually to ensure the BelongsToTenant scope and cms_db connection are applied
        $contentItem = ContentItem::findOrFail($id);

        // Authorization: only author or admins can update
        $user = Auth::user();
        $tenant = app('current_tenant');

        $isOwner = $tenant->owner_id === $user->id;
        $isAuthor = $contentItem->author_id === $user->id;

        if (!$isOwner && !$isAuthor) {
            return response()->json([
                'message' => 'You do not have permission to update this content.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'sometimes|string',
            'status' => 'sometimes|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta' => 'nullable|array',
            'tags' => 'sometimes|array',
            'tags.*' => 'integer|exists:cms_db.tags,id',
        ]);

        $contentItem->update($validated);

        // Update tags if provided
        if (isset($validated['tags'])) {
            $contentItem->tags()->sync($validated['tags']);
        }

        // Manually add author from identity_db
        $contentItem->refresh();
        $contentItem->loadMissing(['tags']);
        $author = \App\Models\User::on('identity_db')->find($contentItem->author_id);

        $data = $contentItem->toArray();
        $data['author'] = $author;

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Delete content item
     *
     * Permanently deletes a content item. Only the author or tenant owner can delete content.
     * This action cannot be undone.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam contentItem integer required The ID of the content item. Example: 1
     *
     * @response 200 {
     *   "message": "Content item deleted successfully"
     * }
     *
     * @response 403 {
     *   "message": "You do not have permission to delete this content."
     * }
     */
    public function destroy($id)
    {
        // Fetch manually to ensure the BelongsToTenant scope and cms_db connection are applied
        $contentItem = ContentItem::findOrFail($id);

        // Authorization: only author or admins can delete
        $user = Auth::user();
        $tenant = app('current_tenant');

        $isOwner = $tenant->owner_id === $user->id;
        $isAuthor = $contentItem->author_id === $user->id;

        if (!$isOwner && !$isAuthor) {
            return response()->json([
                'message' => 'You do not have permission to delete this content.'
            ], 403);
        }

        $contentItem->delete();

        return response()->json([
            'message' => 'Content item deleted successfully'
        ], 200);
    }
}
