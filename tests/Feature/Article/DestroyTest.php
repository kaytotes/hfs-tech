<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_destroy_test_self_owned()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('articles.destroy', [
            'article' => $article->id,
        ]));

        $response->assertOk();
    }

    public function test_article_destroy_test_not_self_owned()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->post(route('articles.destroy', [
            'article' => $article->id,
        ]));

        $response->assertForbidden();
    }
}