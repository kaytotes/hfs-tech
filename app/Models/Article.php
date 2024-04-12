<?php

namespace App\Models;

use App\Interfaces\Sortable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model implements Sortable
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
        'title',
        'description',
    ];

    /**
     * Get the columns that a sortable entity can be sorted by.
     */
    public static function getSortableColumns(): array
    {
        return [
            'created_at',
            'votes_count',
        ];
    }

    /**
     * Get the User that owns the Article.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get Votes for the Article.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get Comments for the Article.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
