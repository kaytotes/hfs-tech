<?php

namespace Tests\Unit\Actions\Article\Vote;

use App\Actions\Article\Vote\RemoveVote;
use App\Models\Article;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemoveVoteTest extends TestCase
{
    use RefreshDatabase;

    private RemoveVote $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(RemoveVote::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_throws_if_no_vote()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $this->expectException(ModelNotFoundException::class);

        $this->action->handle($user, $article);
    }

    public function test_it_deletes_if_found()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();
        $vote = Vote::factory()->create([
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);

        $this->action->handle($user, $article);

        $this->assertDatabaseMissing('votes', [
            'id' => $vote->id,
        ]);
    }
}