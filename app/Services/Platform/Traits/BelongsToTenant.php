<?php

namespace App\Services\Platform\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait for Models that should be scoped by Tenant
 *
 * Usage:
 * class Post extends Model
 * {
 *     use BelongsToTenant;
 * }
 *
 * This will automatically:
 * - Apply tenant_id filtering to all queries
 * - Set tenant_id on create
 */
trait BelongsToTenant
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToTenant(): void
    {
        // Add global scope
        static::addGlobalScope(new TenantScope());

        // Automatically set tenant_id on create
        static::creating(function (Model $model) {
            if (!$model->getAttribute('tenant_id') && app()->bound('current_tenant_id')) {
                $tenantId = app('current_tenant_id');
                $model->setAttribute('tenant_id', $tenantId);
            }
        });
    }

    /**
     * Get the tenant this model belongs to.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Services\Platform\Models\Tenant::class);
    }

    /**
     * Scope a query to ignore the tenant scope (use with caution).
     */
    public function scopeWithoutTenantScope($query)
    {
        return $query->withoutGlobalScope(TenantScope::class);
    }
}
