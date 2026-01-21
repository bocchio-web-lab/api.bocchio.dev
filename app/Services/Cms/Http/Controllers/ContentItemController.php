<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\ContentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentItemController extends Controller
{
    /**
     * Display a listing of content items.
     */
    public function index(Request $request)
    {
        $query = ContentItem::with(['author', 'tags'])
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

        return response()->json($contentItems);
    }

    /**
     * Store a newly created content item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:post,page,project',
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
     * Display the specified content item.
     */
    public function show(ContentItem $contentItem)
    {
        $contentItem->loadMissing(['tags', 'comments']);

        // Manually add author from identity_db
        $author = \App\Models\User::on('identity_db')->find($contentItem->author_id);
        $data = $contentItem->toArray();
        $data['author'] = $author;

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Update the specified content item.
     */
    public function update(Request $request, ContentItem $contentItem)
    {
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

        return response()->json([
            'data' => $contentItem->fresh(['author', 'tags'])
        ]);
    }

    /**
     * Remove the specified content item.
     */
    public function destroy(ContentItem $contentItem)
    {
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

        // Force delete by ID to bypass any issues with model instance
        ContentItem::withoutGlobalScopes()->where('id', $contentItem->id)->delete();

        return response()->json([
            'message' => 'Content item deleted successfully'
        ], 200);
    }
}
