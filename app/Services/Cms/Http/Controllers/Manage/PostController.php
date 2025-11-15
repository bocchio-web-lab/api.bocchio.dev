<?php

namespace App\Services\Cms\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\ContentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of posts for the current tenant.
     * The TenantScope automatically filters this.
     */
    public function index()
    {
        // Only returns posts for the 'current_tenant_id'
        return ContentItem::where('type', 'post')->latest()->paginate(20);
    }

    /**
     * Store a new post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'alpha_dash',
                // Slug must be unique *for this tenant*
                Rule::unique('cms_db.content_items')->where(
                    'tenant_id',
                    App::get('current_tenant_id')
                )
            ],
            'body' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        // The TenantScope trait automatically sets 'tenant_id'
        $post = ContentItem::create([
            'author_id' => Auth::id(),
            'type' => 'post',
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'body' => $validated['body'] ?? '',
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return response()->json($post, 201);
    }

    /**
     * Display the specified post.
     * TenantScope automatically adds "where tenant_id = ?".
     * If it's not this tenant's post, it will 404.
     */
    public function show(ContentItem $post)
    {
        // We must ensure the model binder only found a 'post'
        if ($post->type !== 'post') {
            abort(404);
        }
        return $post->load('author:id,name', 'tags');
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, ContentItem $post)
    {
        if ($post->type !== 'post') {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'required',
                'string',
                'alpha_dash',
                Rule::unique('cms_db.content_items')->where(
                    'tenant_id',
                    App::get('current_tenant_id')
                )->ignore($post->id)
            ],
            'body' => 'nullable|string',
            'status' => 'sometimes|required|in:draft,published',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);
        return $post;
    }

    /**
     * Remove the specified post.
     */
    public function destroy(ContentItem $post)
    {
        if ($post->type !== 'post') {
            abort(404);
        }

        $post->delete();
        return response()->noContent();
    }
}