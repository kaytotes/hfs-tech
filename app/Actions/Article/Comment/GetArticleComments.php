<?php

namespace App\Actions\Article\Comment;

use App\Enums\SortDirection;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class GetArticleComments
{
    public function handle(Article $article, string $sort, SortDirection $direction): Collection
    {
        if (! in_array($sort, Comment::getSortableColumns()))
            throw new InvalidArgumentException("Invalid Sort Type: $sort");

        $comments = $article
            ->comments()
            ->orderBy($sort, $direction->value)
            ->withCount([
                'children',
            ])
            ->get();

        return $comments;
    }
}