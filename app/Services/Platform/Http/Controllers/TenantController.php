<?php

namespace App\Services\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Platform\Models\Service;
use App\Services\Platform\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\Platform\Enums\TenantAccessLevel;

class TenantController extends Controller
{
    /**
     * Display a listing of the authenticated user's tenants.
     */
    public function index()
    {
        return Auth::user()->tenants()->with('service')->get();
    }

    /**
     * Create a new tenant for a specific service.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_id' => 'required|exists:platform_db.services,id',
        ]);

        $service = Service::find($validated['service_id']);
        $user = Auth::user();

        // Create the tenant
        $tenant = Tenant::create([
            'name' => $validated['name'],
            'service_id' => $service->id,
            'owner_id' => $user->id,
            'public_slug' => str_slug($validated['name'] . '-' . strtolower(Str::random(6))) // Simple slug
        ]);

        // Attach the owner as an 'admin'
        $tenant->users()->attach($user->id, ['role' => 'admin']);

        return response()->json($tenant, 201);
    }

    /**
     * Display the specified tenant.
     * We must check if the user is a member.
     */
    public function show(Tenant $tenant)
    {
        // Uses implicit route model binding, but we need to check access
        if (Auth::user()->tenants()->where('tenants.id', $tenant->id)->doesntExist()) {
            abort(403, 'You do not have access to this tenant.');
        }

        return $tenant->load('service', 'users');
    }

    /**
     * Update the specified tenant (e.g., change name or access level).
     */
    public function update(Request $request, Tenant $tenant)
    {
        // Only the owner or an admin can update.
        // A more robust check might use the 'role' on the pivot table.
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
        ]);

        $tenant->update($validated);

        // Logic to re-generate API key if requested
        if ($request->input('regenerate_api_key') === true) {
            $tenant->public_api_key = 'pk_' . Str::random(40);
            $tenant->save();
        }

        return $tenant;
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

        // Pivot records are deleted automatically by cascade (if set up)
        // or should be detached manually.
        $tenant->users()->detach();
        $tenant->delete();

        return response()->noContent();
    }
}