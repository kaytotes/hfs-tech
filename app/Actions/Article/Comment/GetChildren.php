<?php

namespace App\Actions\Article\Comment;

use App\Enums\SortDirection;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class GetChildren
{
    public function handle(Comment $comment, string $sort, SortDirection $direction): Collection
    {
        if (! in_array($sort, Comment::getSortableColumns()))
            throw new InvalidArgumentException("Invalid Sort Type: $sort");

        $comments = Comment::orderBy($sort, $direction->value)
            ->where('parent_id', '=', $comment->id)
            ->withCount([
                'children',
            ])
            ->get();

        return $comments;
    }
}