<?php

namespace App\Actions\Article\Vote;

use App\Models\Article;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class AddVote
{
    /**
     * @param User $user
     * @param Article $article
     * @param boolean $direction True == Upvote, False == Downvote
     */
    public function handle(User $user, Article $article, bool $direction)
    {
        $existing = Vote::where('user_id', '=', $user->id)
            ->where('article_id', '=', $article->id)
            ->first();

        DB::transaction(function () use ($user, $article, $direction, $existing) {
            if ($existing)
                $existing->delete();

            Vote::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
                'direction' => $direction,
            ]);
        });
    }
}