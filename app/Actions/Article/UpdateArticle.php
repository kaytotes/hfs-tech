<?php

namespace App\Actions\Article;

use App\Models\Article;

class UpdateArticle
{
    public function handle(Article $article, string $title, string $description): Article
    {
        $article->update([
            'title' => $title,
            'description' => $description,
        ]);

        $final = $article->fresh([
            'user',
        ]);

        $final->loadCount([
            'comments',
            'votes',
        ]);

        return $final;
    }
}