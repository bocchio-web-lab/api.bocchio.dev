<?php

namespace App\Services\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Platform\Models\Service;
use App\Services\Platform\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Services\Platform\Enums\TenantAccessLevel;

/**
 * @group Platform Management
 *
 * Manage tenants within the platform. A tenant is an isolated instance of a service
 * that you own and control. All content and data are scoped to tenants.
 */
class TenantController extends Controller
{
    /**
     * List your tenants
     *
     * Returns all tenants that the authenticated user owns or is a member of.
     * This includes both tenants you created and tenants you've been invited to.
     *
     * @authenticated
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "My CMS",
     *       "service_id": 1,
     *       "owner_id": 1,
     *       "public_slug": "my-cms-abc123",
     *       "access_level": "public",
     *       "public_api_key": "pk_1234567890abcdef",
     *       "settings": {},
     *       "created_at": "2025-01-20T10:00:00.000000Z",
     *       "updated_at": "2025-01-20T10:00:00.000000Z",
     *       "service": {
     *         "id": 1,
     *         "name": "CMS",
     *         "slug": "cms",
     *         "description": "Content Management System",
     *         "is_active": true
     *       }
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $user = Auth::user();

        // Get both owned and member tenants
        $ownedTenants = $user->ownedTenants()->with('service')->get();
        $memberTenants = $user->tenants()->with('service')->get();

        // Merge and remove duplicates
        $allTenants = $ownedTenants->merge($memberTenants)->unique('id');

        return response()->json([
            'data' => $allTenants->values()
        ]);
    }

    /**
     * Create a tenant
     *
     * Creates a new tenant for a specific service. You become the owner and are
     * automatically added as an admin member. The tenant will have a unique public
     * slug used for public content access.
     *
     * @authenticated
     *
     * @bodyParam name string required The display name of the tenant. Example: My Blog
     * @bodyParam service_id integer required The ID of the service (get from /api/manage/services). Example: 1
     * @bodyParam public_slug string Optional custom public slug (must be unique). If not provided, one will be auto-generated. Example: my-blog
     * @bodyParam access_level string Access level for public content API. One of: public, private, token_protected. Default is private. Example: public
     *
     * @response 201 {
     *   "data": {
     *     "id": 2,
     *     "name": "My Blog",
     *     "service_id": 1,
     *     "owner_id": 1,
     *     "public_slug": "my-blog",
     *     "access_level": "public",
     *     "public_api_key": "pk_abcdefghijklmnopqrstuvwxyz123456789",
     *     "settings": {},
     *     "created_at": "2025-01-22T10:00:00.000000Z",
     *     "updated_at": "2025-01-22T10:00:00.000000Z",
     *     "service": {
     *       "id": 1,
     *       "name": "CMS",
     *       "slug": "cms"
     *     }
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_id' => 'required|exists:platform_db.services,id',
            'public_slug' => 'sometimes|string|alpha_dash|unique:platform_db.tenants,public_slug',
            'access_level' => ['sometimes', Rule::enum(TenantAccessLevel::class)],
        ]);

        $service = Service::find($validated['service_id']);
        $user = Auth::user();

        // Generate public_slug if not provided
        if (!isset($validated['public_slug'])) {
            $validated['public_slug'] = Str::slug($validated['name']) . '-' . Str::lower(Str::random(6));
        }

        // Create the tenant
        $tenant = Tenant::create([
            'name' => $validated['name'],
            'service_id' => $service->id,
            'owner_id' => $user->id,
            'public_slug' => $validated['public_slug'],
            'access_level' => $validated['access_level'] ?? TenantAccessLevel::PRIVATE ,
        ]);

        // Attach the owner as an 'admin'
        $tenant->users()->attach($user->id, ['role' => 'admin']);

        return response()->json([
            'data' => $tenant->load('service')
        ], 201);
    }

    /**
     * Get tenant details
     *
     * Returns detailed information about a specific tenant, including its members.
     * You must be the owner or a member to access this endpoint.
     *
     * @authenticated
     *
     * @urlParam tenant integer required The ID of the tenant. Example: 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "My CMS",
     *     "service_id": 1,
     *     "owner_id": 1,
     *     "public_slug": "my-cms-abc123",
     *     "access_level": "public",
     *     "public_api_key": "pk_1234567890abcdef",
     *     "settings": {},
     *     "created_at": "2025-01-20T10:00:00.000000Z",
     *     "updated_at": "2025-01-20T10:00:00.000000Z",
     *     "service": {
     *       "id": 1,
     *       "name": "CMS",
     *       "slug": "cms"
     *     },
     *     "users": [
     *       {
     *         "id": 1,
     *         "name": "John Doe",
     *         "email": "john@example.com",
     *         "pivot": {
     *           "role": "admin"
     *         }
     *       }
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "message": "You do not have access to this tenant."
     * }
     */
    public function show(Tenant $tenant)
    {
        $user = Auth::user();

        // Check if user is owner or member
        $isOwner = $tenant->owner_id === $user->id;
        $isMember = $user->tenants()->where('tenants.id', $tenant->id)->exists();

        if (!$isOwner && !$isMember) {
            abort(403, 'You do not have access to this tenant.');
        }

        return response()->json([
            'data' => $tenant->load('service', 'users')
        ]);
    }

    /**
     * Update a tenant
     *
     * Updates tenant information such as name, public slug, or access level.
     * Only the tenant owner can perform updates. You can also regenerate the
     * public API key used for token-protected content access.
     *
     * @authenticated
     *
     * @urlParam tenant integer required The ID of the tenant. Example: 1
     *
     * @bodyParam name string The display name. Example: Updated Blog Name
     * @bodyParam public_slug string Custom public slug (must be unique). Example: updated-blog
     * @bodyParam access_level string Access level: public, private, or token_protected. Example: token_protected
     * @bodyParam regenerate_api_key boolean Set to true to generate a new public API key. Example: false
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "Updated Blog Name",
     *     "service_id": 1,
     *     "owner_id": 1,
     *     "public_slug": "updated-blog",
     *     "access_level": "token_protected",
     *     "public_api_key": "pk_newgeneratedkey1234567890",
     *     "settings": {},
     *     "created_at": "2025-01-20T10:00:00.000000Z",
     *     "updated_at": "2025-01-22T11:00:00.000000Z"
     *   }
     * }
     *
     * @response 403 {
     *   "message": "Only the tenant owner can perform this action."
     * }
     */
    public function update(Request $request, Tenant $tenant)
    {
        // Only the owner or an admin can update.
        if ($tenant->owner_id !== Auth::id()) {
            abort(403, 'Only the tenant owner can perform this action.');
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'public_slug' => [
                'sometimes',
                'required',
                'string',
                'alpha_dash',
                Rule::unique('platform_db.tenants')->ignore($tenant->id),
            ],
            'access_level' => ['sometimes', 'required', Rule::enum(TenantAccessLevel::class)],
            'regenerate_api_key' => 'sometimes|boolean',
        ]);

        // Update basic fields
        $tenant->update(array_filter($validated, fn($key) => $key !== 'regenerate_api_key', ARRAY_FILTER_USE_KEY));

        // Logic to re-generate API key if requested
        if ($request->input('regenerate_api_key') === true) {
            $tenant->public_api_key = 'pk_' . Str::random(40);
            $tenant->save();
        }

        return response()->json([
            'data' => $tenant->fresh()
        ]);
    }

    /**
     * Delete a tenant
     *
     * Permanently deletes a tenant and all associated data. This action cannot be undone.
     * Only the tenant owner can delete a tenant.
     *
     * @authenticated
     *
     * @urlParam tenant integer required The ID of the tenant. Example: 1
     *
     * @response 200 {
     *   "message": "Tenant deleted successfully"
     * }
     *
     * @response 403 {
     *   "message": "Only the tenant owner can delete this tenant."
     * }
     */
    public function destroy(Tenant $tenant)
    {
        // Only the owner can delete.
        if ($tenant->owner_id !== Auth::id()) {
            abort(403, 'Only the tenant owner can delete this tenant.');
        }

        // Pivot records are deleted automatically by cascade
        $tenant->users()->detach();
        $tenant->delete();

        return response()->json([
            'message' => 'Tenant deleted successfully'
        ], 200);
    }
}