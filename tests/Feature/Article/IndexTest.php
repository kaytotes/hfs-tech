<?php

namespace Tests\Feature\Article;

use App\Enums\SortDirection;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_articles_index_returns_articles()
    {
        $user = User::factory()->create();
        $articles = Article::factory(3)->create();

        $response = $this->actingAs($user)->get(route('articles', [
            'sort' => 'created_at',
            'direction' => SortDirection::DESC->value,
        ]));

        $response->assertOk();

        $response->assertJsonFragment([
            'id' => $articles[0]->id,
            'title' => $articles[0]->title,
            'description' => $articles[0]->description,
            'description_shortened' => Str::limit($articles[0]->description, 90),
            'link' => route('articles.show', ['article' => $articles[0]->id]),
            'created_at' => $articles[0]->created_at->diffForHumans(),
            'updated_at' => $articles[0]->updated_at->diffForHumans(),
            'author' => [
                'id' => $articles[0]->user->id,
                'name' => $articles[0]->user->name,
                'email' => $articles[0]->user->email,
                'email_verified_at' => $articles[0]->user->email_verified_at,
                'created_at' => $articles[0]->user->created_at,
                'updated_at' => $articles[0]->user->updated_at,
            ],
            'comments_count' => 0,
        ]);
    }
}