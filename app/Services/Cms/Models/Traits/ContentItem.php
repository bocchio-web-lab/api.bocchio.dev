<?php

namespace App\Services\Cms\Models;

use App\Models\User;
use App\Services\Cms\Models\Traits\TenantScope;
use App\Services\Platform\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentItem extends Model
{
    use HasFactory, TenantScope; // <-- ADD THE TRAIT

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'cms_db';

    protected $fillable = [
        'author_id',
        'type',
        'title',
        'slug',
        'body',
        'excerpt',
        'meta',
        'status',
        'published_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Get the tenant this item belongs to (from platform_db).
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    /**
     * Get the author of this item (from identity_db).
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the comments for this item.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the tags for this item.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_item_tag');
    }
}