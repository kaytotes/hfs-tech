<?php

namespace App\Models;

use App\Interfaces\Sortable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model implements Sortable
{
    use HasFactory,
        HasUuids,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'article_id',
        'parent_id',
        'body',
    ];

    /**
     * Get the columns that a sortable entity can be sorted by.
     */
    public static function getSortableColumns(): array
    {
        return [
            'created_at',
        ];
    }

    /**
     * Get the Article for this Comment.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * The User who wrote the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Parent for this Comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Get all of the Children of this Comment.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }
}
