<?php

namespace Tests\Unit\Actions\Article;

use App\Actions\Article\Comment\DeleteComment;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase;

    private DeleteComment $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(DeleteComment::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_deletes_articles()
    {
        $comment = Comment::factory()->create();

        $this->action->handle($comment);

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
        ]);
    }
}
