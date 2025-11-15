<?php

namespace App\Services\Cms\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

trait TenantScope
{
    /**
     * The "booted" method of the model.
     * This will add our global scope and a 'creating' event.
     */
    protected static function bootTenantScope(): void
    {
        // 1. Add a global scope to filter all SELECT queries
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (App::has('current_tenant_id')) {
                $builder->where('tenant_id', App::get('current_tenant_id'));
            }
        });

        // 2. Add a 'creating' event to automatically set the tenant_id on INSERT
        static::creating(function ($model) {
            if (App::has('current_tenant_id')) {
                $model->tenant_id = App::get('current_tenant_id');
            }
        });
    }
}