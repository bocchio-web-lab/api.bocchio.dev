<?php

namespace App\Services\Platform\Models;

use App\Models\User;
use App\Services\Platform\Enums\TenantAccessLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $connection = 'platform_db';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Platform\TenantFactory::new();
    }

    protected $fillable = [
        'service_id',
        'owner_id',
        'name',
        'public_slug',
        'access_level',
        'public_api_key',
        'settings',
    ];

    protected $casts = [
        'access_level' => TenantAccessLevel::class,
        'settings' => 'array',
    ];

    /**
     * Boot model to auto-generate api key.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            if (empty($tenant->public_api_key)) {
                $tenant->public_api_key = 'pk_' . Str::random(40);
            }
        });
    }

    /**
     * The Service this Tenant belongs to (e.g., "CMS").
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * The User who owns this Tenant.
     */
    public function owner()
    {
        // Points to the default (identity_db) connection's User model
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The users who are members of this tenant.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_user')
            ->withPivot('role')
            ->withTimestamps();
    }
}