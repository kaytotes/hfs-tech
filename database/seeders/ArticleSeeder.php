<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            $articles = Article::factory(5)->create([
                'user_id' => $user->id,
            ]);

            $articles->each(function (Article $article) {
                Vote::factory()->create([
                    'article_id' => $article->id,
                ]);

                $comments = Comment::factory(rand(1, 3))->create([
                    'article_id' => $article->id,
                ]);

                $comments->each(function (Comment $comment) use ($article) {
                    if (rand(0, 1))
                        return;

                    Comment::factory(rand(1, 3))->create([
                        'article_id' => $article->id,
                        'parent_id' => $comment->id,
                    ]);
                });
            });
        });
    }
}
