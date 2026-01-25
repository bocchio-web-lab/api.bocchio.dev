<?php

namespace App\Services\Cms\Models;

use App\Models\User;
use App\Services\Platform\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContentItem extends Model
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
        return \Database\Factories\Cms\ContentItemFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'author_id',
        'type',
        'title',
        'slug',
        'excerpt',
        'body',
        'status',
        'published_at',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'published_at' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contentItem) {
            if (empty($contentItem->slug)) {
                $contentItem->slug = static::generateUniqueSlug($contentItem->title, $contentItem->tenant_id);
            }
        });
    }

    /**
     * Generate a unique slug for the content item within the tenant.
     */
    protected static function generateUniqueSlug(string $title, int $tenantId): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('tenant_id', $tenantId)->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Get the author of this content item.
     * Cross-database relationship to identity_db.users
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id')
            ->setConnection('identity_db');
    }

    /**
     * Get the comments for this content item.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the tags for this content item.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Scope to only published content.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the content item is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published'
            && $this->published_at !== null
            && $this->published_at->isPast();
    }

    /**
     * Publish the content item.
     */
    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    /**
     * Unpublish the content item (revert to draft).
     */
    public function unpublish(): void
    {
        $this->update([
            'status' => 'draft',
        ]);
    }
}
