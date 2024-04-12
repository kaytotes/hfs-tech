<?php

namespace Tests\Unit\Actions\Article;

use App\Actions\Article\CreateNewArticle;
use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateNewArticleTest extends TestCase
{
    use RefreshDatabase;

    private CreateNewArticle $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(CreateNewArticle::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_returns_article()
    {
        $user = User::factory()->create();

        $result = $this->action->handle($user, 'Title', 'Description');

        $this->assertInstanceOf(Article::class, $result);
    }

    public function test_it_stores_to_db()
    {
        $user = User::factory()->create();

        $now = now();

        Carbon::setTestNow($now);

        $this->action->handle($user, 'Title', 'Description');

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }

    public function test_it_eager_loads_user()
    {
        $user = User::factory()->create();

        $result = $this->action->handle($user, 'Title', 'Description');

        $this->assertTrue($result->relationLoaded('user'));
    }

    public function test_it_counts_comments()
    {
        $user = User::factory()->create();

        $result = $this->action->handle($user, 'Title', 'Description');

        $this->assertEquals(0, $result->comments_count);
    }
}