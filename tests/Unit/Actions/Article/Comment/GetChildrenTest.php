<?php

namespace Tests\Unit\Actions\Article;

use App\Actions\Article\Comment\GetChildren;
use App\Enums\SortDirection;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetChildrenTest extends TestCase
{
    use RefreshDatabase;

    private GetChildren $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(GetChildren::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_returns_collection_instance()
    {
        $comment = Comment::factory()->create();
        $comments = Comment::factory(3)->create([
            'parent_id' => $comment->id,
            'article_id' => $comment->article->id,
        ]);

        $result = $this->action->handle($comment, 'created_at', SortDirection::DESC);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_it_returns_children()
    {
        $comment = Comment::factory()->create();
        $comments = Comment::factory(3)->create([
            'parent_id' => $comment->id,
            'article_id' => $comment->article->id,
        ]);

        $result = $this->action->handle($comment, 'created_at', SortDirection::DESC);

        $this->assertCount(3, $result);
    }

    public function test_we_can_sort_by_created_at_column_desc()
    {
        $parent = Comment::factory()->create();

        $now = now();
        $earlier = now()->carbonize()->subHour();

        Carbon::setTestNow($now);

        $newest = Comment::factory()->create([
            'parent_id' => $parent->id,
            'article_id' => $parent->article->id,
        ]);

        Carbon::setTestNow($earlier);

        $oldest = Comment::factory()->create([
            'parent_id' => $parent->id,
            'article_id' => $parent->article->id,
        ]);

        $result = $this->action->handle($parent, 'created_at', SortDirection::DESC);

        $this->assertEquals($newest->id, $result[0]->id);
    }

    public function test_we_can_sort_by_created_at_column_asc()
    {
        $parent = Comment::factory()->create();

        $now = now();
        $earlier = now()->carbonize()->subHour();

        Carbon::setTestNow($now);

        $newest = Comment::factory()->create([
            'parent_id' => $parent->id,
            'article_id' => $parent->article->id,
        ]);

        Carbon::setTestNow($earlier);

        $oldest = Comment::factory()->create([
            'parent_id' => $parent->id,
            'article_id' => $parent->article->id,
        ]);

        $result = $this->action->handle($parent, 'created_at', SortDirection::ASC);

        $this->assertEquals($newest->id, $result[1]->id);
    }
}