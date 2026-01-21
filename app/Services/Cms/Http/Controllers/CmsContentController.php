<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
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
                'message' => 'CMS Content API - Ready for implementation'
            ]
        ]);
    }

    /**
     * Placeholder for listing published posts
     */
    public function listPosts()
    {
        return response()->json([
            'data' => [],
            'message' => 'Published posts endpoint - Ready for implementation'
        ]);
    }

    /**
     * Placeholder for getting a single post by slug
     */
    public function showPost($tenant_slug, $post_slug)
    {
        return response()->json([
            'data' => null,
            'message' => 'Post detail endpoint - Ready for implementation'
        ]);
    }

    /**
     * Placeholder for listing published pages
     */
    public function listPages()
    {
        return response()->json([
            'data' => [],
            'message' => 'Published pages endpoint - Ready for implementation'
        ]);
    }

    /**
     * Placeholder for getting a single page by slug
     */
    public function showPage($tenant_slug, $page_slug)
    {
        return response()->json([
            'data' => null,
            'message' => 'Page detail endpoint - Ready for implementation'
        ]);
    }
}
