<?php

namespace Tests\Unit\Actions\Article;

use App\Actions\Article\UpdateArticle;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    private UpdateArticle $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(UpdateArticle::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_updates_title()
    {
        $article = Article::factory()->create();

        $result = $this->action->handle($article, 'New Title', 'New Desc');

        $this->assertEquals('New Title', $result->title);
    }

    public function test_it_updates_desc()
    {
        $article = Article::factory()->create();

        $result = $this->action->handle($article, 'New Title', 'New Desc');

        $this->assertEquals('New Desc', $result->description);
    }

    public function test_it_eager_loads_user()
    {
        $article = Article::factory()->create();

        $result = $this->action->handle($article, 'New Title', 'New Desc');

        $this->assertTrue($result->relationLoaded('user'));
    }

    public function test_it_counts_comments()
    {
        $article = Article::factory()->create();

        Comment::factory(3)->create([
            'article_id' => $article->id,
        ]);

        $result = $this->action->handle($article, 'New Title', 'New Desc');

        $this->assertEquals(3, $result->comments_count);
    }
}
