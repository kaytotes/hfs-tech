<?php

namespace Tests\Unit\Actions\Article;

use App\Actions\Article\Comment\CreateNewComment;
use App\Actions\Article\Comment\DeleteComment;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateNewCommentTest extends TestCase
{
    use RefreshDatabase;

    private CreateNewComment $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = resolve(CreateNewComment::class);
    }

    protected function tearDown(): void
    {
        unset($this->action);

        parent::tearDown();
    }

    public function test_it_returns_comment()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $result = $this->action->handle($user, $article, null, 'Comment Here');

        $this->assertInstanceOf(Comment::class, $result);
    }

    public function test_if_no_parent_it_saves()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $this->action->handle($user, $article, null, 'Comment Here');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'article_id' => $article->id,
            'parent_id' => null,
            'body' => 'Comment Here',
        ]);
    }

    public function test_it_fails_if_invalid_parent()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $this->expectException(ModelNotFoundException::class);

        $this->action->handle($user, $article, 'abc123', 'Comment Here');
    }

    public function test_if_id_provided_it_saves()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
        ]);

        $this->action->handle($user, $article, $comment->id, 'Comment Here');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'article_id' => $article->id,
            'parent_id' => $comment->id,
            'body' => 'Comment Here',
        ]);
    }

    public function test_if_comment_model_provided_it_saves()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
        ]);

        $this->action->handle($user, $article, $comment, 'Comment Here');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'article_id' => $article->id,
            'parent_id' => $comment->id,
            'body' => 'Comment Here',
        ]);
    }
}
