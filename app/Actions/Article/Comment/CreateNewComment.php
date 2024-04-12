<?php

namespace App\Actions\Article\Comment;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

class CreateNewComment
{
    /**
     * @param User $user
     * @param Article $article
     * @param Comment|string|null $parent UUID or Model Instance.
     * @param string $body
     * @throws ModelNotFoundException
     * @throws InvalidArgumentException
     */
    public function handle(User $user, Article $article, Comment|string|null $parent, string $body): Comment
    {
        $_parent = null;

        if (! $parent == null && ! $parent instanceof Comment)
            $_parent = Comment::findOrFail($parent)->id;

        if (! $parent == null && $parent instanceof Comment)
            $_parent = $parent->id;

        if ($parent instanceof Comment && $article->id !== $parent->article_id)
            throw new InvalidArgumentException("Provided Parent is not related to provided Article.");

        return Comment::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'parent_id' => $_parent,
            'body' => $body,
        ]);
    }
}