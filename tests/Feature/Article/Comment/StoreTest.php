<?php

namespace Tests\Feature\Article\Coomment;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_comment_with_no_parent_and_returns()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $now = now();

        Carbon::setTestNow($now);

        $body = Str::random(32);
        $response = $this->actingAs($user)->postJson(route('articles.comments.store', [
            'article' => $article->id,
        ]), [
            'parent_id' => null,
            'body' => $body,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'user_id' => $user->id,
            'body' => $body,
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }

    public function test_it_creates_comment_with_parent_and_returns()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
        ]);

        $now = now();

        Carbon::setTestNow($now);

        $body = Str::random(32);
        $response = $this->actingAs($user)->postJson(route('articles.comments.store', [
            'article' => $article->id,
        ]), [
            'parent_id' => $comment->id,
            'body' => $body,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'parent_id' => $comment->id,
            'user_id' => $user->id,
            'body' => $body,
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }
}