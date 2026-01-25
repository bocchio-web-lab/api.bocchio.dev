<?php

namespace App\Services\Cms\Models;

use App\Services\Platform\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, BelongsToTenant;

    /**
     * The connection name for the model.
     */
    protected $connection = 'cms_db';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Cms\TagFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Get the content items that have this tag.
     */
    public function contentItems()
    {
        return $this->belongsToMany(ContentItem::class);
    }
}
