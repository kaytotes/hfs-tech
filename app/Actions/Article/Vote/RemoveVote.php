<?php

namespace App\Actions\Article\Vote;

use App\Models\Article;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RemoveVote
{
    /**
     * @param User $user
     * @param Article $article
     * @throws ModelNotFoundException
     */
    public function handle(User $user, Article $article)
    {
        $existing = Vote::where('user_id', '=', $user->id)
            ->where('article_id', '=', $article->id)
            ->firstOrFail();

        $existing->delete();
    }
}