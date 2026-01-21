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

class TenantController extends Controller
{
    /**
     * Display a listing of the authenticated user's tenants.
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
     * Create a new tenant for a specific service.
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
     * Display the specified tenant.
     * We must check if the user is a member.
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
     * Update the specified tenant (e.g., change name or access level).
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
     * Remove the specified tenant.
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