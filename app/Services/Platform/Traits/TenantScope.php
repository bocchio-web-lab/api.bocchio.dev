<?php

namespace App\Services\Platform\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Global Scope for Tenant Isolation
 *
 * This scope automatically filters all queries by the current tenant_id
 * stored in the application container.
 */
class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->bound('current_tenant_id')) {
            $tenantId = app('current_tenant_id');
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }
}
