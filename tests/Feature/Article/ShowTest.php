<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_we_can_get_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.show', [
            'article' => $article,
        ]));

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $article->id,
            'title' => $article->title,
            'description' => $article->description,
            'description_shortened' => Str::limit($article->description, 90),
            'link' => route('articles.show', ['article' => $article->id]),
            'created_at' => $article->created_at->diffForHumans(),
            'updated_at' => $article->updated_at->diffForHumans(),
            'author' => [
                'id' => $article->user->id,
                'name' => $article->user->name,
                'email' => $article->user->email,
                'email_verified_at' => $article->user->email_verified_at,
                'created_at' => $article->user->created_at,
                'updated_at' => $article->user->updated_at,
            ],
            'comments_count' => 0,
        ]);
    }

    public function test_it_fails_if_no_article_found()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.show', [
            'article' => 'abc123',
        ]));

        $response->assertNotFound();
    }
}