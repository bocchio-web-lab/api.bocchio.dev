<?php

namespace App\Services\Platform\Http\Middleware;

use App\Services\Platform\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class EnsurePublicTenantAccess
{
    /**
     * Handle an incoming request.
     *
     * @param string $serviceSlug The service slug (e.g., 'cms')
     */
    public function handle(Request $request, Closure $next, string $serviceSlug): Response
    {
        $tenantSlug = $request->route('tenant_slug');

        if (!$tenantSlug) {
            return response()->json(['message' => 'Tenant identifier missing.'], 404);
        }

        $tenant = Tenant::query()
            ->where('public_slug', $tenantSlug)
            ->whereHas('service', fn($q) => $q->where('slug', $serviceSlug))
            ->first();

        if (!$tenant) {
            return response()->json(['message' => 'Not Found.'], 404);
        }

        // Debug: check tenant access_level
        // \Log::info('Tenant access check', ['tenant_id' => $tenant->id, 'access_level' => $tenant->access_level, 'access_level_value' => $tenant->access_level->value ?? $tenant->access_level]);

        // Check the tenant's access level
        $accessLevel = $tenant->access_level instanceof \BackedEnum ? $tenant->access_level->value : $tenant->access_level;

        switch ($accessLevel) {
            case 'private':
                return response()->json(['message' => 'Access to this resource is private.'], 403);

            case 'token_protected':
                $token = $request->bearerToken();
                if (!$token || $token !== $tenant->public_api_key) {
                    return response()->json(['message' => 'Unauthorized.'], 401);
                }
                // Token is valid, fall through to grant access.
                break;

            case 'public':
                // Access is granted
                break;
        }

        // Grant access for public and validated token_protected
        App::instance('current_tenant_id', $tenant->id);
        App::instance('current_tenant', $tenant);
        return $next($request);
    }
}