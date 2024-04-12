<?php

namespace Tests\Unit\Actions\Article;

use App\Actions\Article\Comment\GetArticleComments;
use App\Enums\SortDirection;
use App\Models\Article;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetArticleCommentsTest extends TestCase
{
    use RefreshDatabase;

    private GetArticleComments $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(GetArticleComments::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_returns_collection_instance()
    {
        $article = Article::factory()->create();

        $comments = Comment::factory(3)->create([
            'article_id' => $article->id,
        ]);

        $result = $this->action->handle($article, 'created_at', SortDirection::DESC);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_we_can_sort_by_created_at_column_desc()
    {
        $article = Article::factory()->create();

        $now = now();
        $earlier = now()->carbonize()->subHour();

        Carbon::setTestNow($now);

        $newest = Comment::factory()->create([
            'article_id' => $article->id,
        ]);

        Carbon::setTestNow($earlier);

        $oldest = Comment::factory()->create([
            'article_id' => $article->id,
        ]);

        $result = $this->action->handle($article, 'created_at', SortDirection::DESC);

        $this->assertEquals($newest->id, $result[0]->id);
    }

    public function test_we_can_sort_by_created_at_column_asc()
    {
        $article = Article::factory()->create();

        $now = now();
        $earlier = now()->carbonize()->subHour();

        Carbon::setTestNow($now);

        $newest = Comment::factory()->create([
            'article_id' => $article->id,
        ]);

        Carbon::setTestNow($earlier);

        $oldest = Comment::factory()->create([
            'article_id' => $article->id,
        ]);

        $result = $this->action->handle($article, 'created_at', SortDirection::ASC);

        $this->assertEquals($newest->id, $result[1]->id);
    }
}