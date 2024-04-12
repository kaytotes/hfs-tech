<?php

namespace Tests\Feature\Article\Comment;

use App\Enums\SortDirection;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_comments_index_returns_comments()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comments = Comment::factory(3)->create([
            'article_id' => $article->id,
        ]);

        $response = $this->actingAs($user)->get(route('articles.comments.index', [
            'article' => $article,
            'sort' => 'created_at',
            'direction' => SortDirection::DESC->value,
        ]));

        $response->assertOk();

        $response->assertJsonFragment([
            'id' => $comments[0]->id,
            'article_id' => $comments[0]->article_id,
            'parent_id' => $comments[0]->parent_id,
            'user_id' => $comments[0]->user_id,
            'body' => $comments[0]->body,
            'created_at' => $comments[0]->created_at,
        ]);
    }
}