<?php

namespace Tests\Feature\Article\Comment;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_destroy_test_self_owned()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('articles.comments.destroy', [
            'article' => $article->id,
            'comment' => $comment->id,
        ]));

        $response->assertOk();
    }

    public function test_comment_destroy_test_not_self_owned()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
        ]);

        $response = $this->actingAs($user)->post(route('articles.comments.destroy', [
            'article' => $article->id,
            'comment' => $comment->id,
        ]));

        $response->assertForbidden();
    }
}