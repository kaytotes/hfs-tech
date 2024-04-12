<?php

namespace Tests\Unit\Actions\Article\Vote;

use App\Actions\Article\Vote\AddVote;
use App\Models\Article;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddVoteTest extends TestCase
{
    use RefreshDatabase;

    private AddVote $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(AddVote::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_creates_vote_for_article()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $this->action->handle($user, $article, true);

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'article_id' => $article->id,
            'direction' => true,
        ]);
    }

    public function test_it_cleans_up_existing_votes()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $vote = Vote::factory()->create([
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);

        $this->action->handle($user, $article, true);

        $this->assertDatabaseMissing('votes', [
            'id' => $vote->id,
        ]);

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'article_id' => $article->id,
            'direction' => true,
        ]);
    }
}