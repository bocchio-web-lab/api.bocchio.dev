<?php

namespace App\Services\Platform\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param string $serviceSlug The service slug (e.g., 'cms') passed from the route.
     */
    public function handle(Request $request, Closure $next, string $serviceSlug): Response
    {
        $tenantId = $request->header('X-Tenant-ID');
        $user = $request->user();

        if (!$tenantId) {
            return response()->json(['message' => 'X-Tenant-ID header is missing.'], 400);
        }

        // Check if the user is a member of this tenant
        // AND this tenant belongs to the specified service.
        $tenant = $user->tenants()
            ->where('tenants.id', $tenantId)
            ->whereHas('service', function ($query) use ($serviceSlug) {
                $query->where('slug', $serviceSlug);
            })
            ->first();

        // If not found as member, check if user is the owner
        if (!$tenant) {
            $tenant = $user->ownedTenants()
                ->where('id', $tenantId)
                ->whereHas('service', function ($query) use ($serviceSlug) {
                    $query->where('slug', $serviceSlug);
                })
                ->first();
        }

        if (!$tenant) {
            return response()->json(['message' => 'Forbidden. You do not have access to this tenant or service.'], 403);
        }

        // Make the current tenant ID and tenant globally available for this request.
        // This is what our Global Scopes will use.
        App::instance('current_tenant_id', $tenant->id);
        App::instance('current_tenant', $tenant);

        return $next($request);
    }
}