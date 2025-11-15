<?php

namespace App\Services\Platform\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'platform_db';

    protected $fillable = ['name', 'slug', 'description'];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}