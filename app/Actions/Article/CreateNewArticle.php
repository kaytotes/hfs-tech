<?php

namespace App\Actions\Article;

use App\Models\Article;
use App\Models\User;

class CreateNewArticle
{
    public function handle(User $user, string $title, string $description): Article
    {
        $result = Article::create([
            'user_id' => $user->id,
            'title' => $title,
            'description' => $description,
        ]);

        return $result->fresh([
            'user',
        ])->loadCount([
            'comments',
            'votes',
        ]);
    }
}