<?php

namespace Tests\Unit\Actions\Article;

use App\Actions\Article\DeleteArticle;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteArticleTest extends TestCase
{
    use RefreshDatabase;

    private DeleteArticle $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(DeleteArticle::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_deletes_articles()
    {
        $article = Article::factory()->create();

        $this->action->handle($article);

        $this->assertSoftDeleted('articles', [
            'id' => $article->id,
        ]);
    }
}
