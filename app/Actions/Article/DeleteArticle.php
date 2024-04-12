<?php

namespace App\Actions\Article;

use App\Models\Article;

class DeleteArticle
{
    public function handle(Article $article)
    {
        $article->delete();
    }
}