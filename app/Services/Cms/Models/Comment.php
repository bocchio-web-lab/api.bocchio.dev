<?php

namespace App\Services\Cms\Models;

use App\Models\User;
use App\Services\Platform\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
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
        return \Database\Factories\Cms\CommentFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'content_item_id',
        'author_id',
        'author_name',
        'body',
        'approved',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'approved' => 'boolean',
    ];

    /**
     * Get the content item this comment belongs to.
     */
    public function contentItem()
    {
        return $this->belongsTo(ContentItem::class);
    }

    /**
     * Get the author of this comment (if authenticated).
     * Cross-database relationship to identity_db.users
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id')
            ->setConnection('identity_db');
    }

    /**
     * Scope to only approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope to only pending comments.
     */
    public function scopePending($query)
    {
        return $query->where('approved', false);
    }

    /**
     * Approve the comment.
     */
    public function approve(): void
    {
        $this->update(['approved' => true]);
    }

    /**
     * Reject the comment (set approved to false).
     */
    public function reject(): void
    {
        $this->update(['approved' => false]);
    }
}
