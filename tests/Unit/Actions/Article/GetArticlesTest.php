<?php

namespace Tests\Unit\Actions\Article;

use App\Actions\Article\GetArticles;
use App\Enums\SortDirection;
use App\Models\Article;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\Paginator;
use InvalidArgumentException;
use Str;
use Tests\TestCase;

class GetArticlesTest extends TestCase
{
    use RefreshDatabase;

    private GetArticles $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(GetArticles::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_can_return_full_collection()
    {
        $result = $this->action->handle('created_at', SortDirection::DESC, false);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_it_can_return_pagination()
    {
        $result = $this->action->handle('created_at', SortDirection::DESC, true);

        $this->assertInstanceOf(Paginator::class, $result);
    }
    
    public function test_it_returns_articles()
    {
        $article = Article::factory()->create();

        $result = $this->action->handle('created_at', SortDirection::DESC, false);

        $this->assertEquals($article->id, $result[0]->id);
        $this->assertEquals($article->title, $result[0]->title);
    }

    public function test_it_eager_loads_user()
    {
        Article::factory()->create();

        $result = $this->action->handle('created_at', SortDirection::DESC, false)[0];

        $this->assertTrue($result->relationLoaded('user'));
    }

    public function test_it_counts_comments()
    {
        $article = Article::factory()->create();
        Comment::factory(3)->create([
            'article_id' => $article->id,
        ]);

        $result = $this->action->handle('created_at', SortDirection::DESC, false)[0];

        $this->assertEquals(3, $result->comments_count);
    }

    public function test_we_can_sort_by_created_at_column_desc()
    {
        $now = now();
        $earlier = now()->carbonize()->subHour();

        Carbon::setTestNow($now);

        $newest = Article::factory()->create();

        Carbon::setTestNow($earlier);

        $oldest = Article::factory()->create();

        $result = $this->action->handle('created_at', SortDirection::DESC, false);

        $this->assertEquals($newest->id, $result[0]->id);
    }

    public function test_we_can_sort_by_created_at_column_asc()
    {
        $now = now();
        $earlier = now()->carbonize()->subHour();

        Carbon::setTestNow($now);

        $newest = Article::factory()->create();

        Carbon::setTestNow($earlier);

        $oldest = Article::factory()->create();

        $result = $this->action->handle('created_at', SortDirection::ASC, false);

        $this->assertEquals($newest->id, $result[1]->id);
    }

    public function test_it_throws_if_invalid_column()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->action->handle(Str::random(16), SortDirection::DESC, false);
    }
}
