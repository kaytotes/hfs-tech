<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_article_and_returns()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $now = now();

        Carbon::setTestNow($now);

        $response = $this->actingAs($user)->postJson(route('articles.update', [
            'article' => $article,
        ]), [
            'title' => 'Title',
            'description' => 'Description',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => $article->created_at,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }

    public function test_title_validation_required()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('articles.update', [
            'article' => $article,
        ]), [
            'description' => 'Description',
        ]);

        $response->assertInvalid([
            'title' => 'The title field is required.',
        ]);
    }

    public function test_title_validation_min_length()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('articles.update', [
            'article' => $article,
        ]), [
            'title' => 'as',
            'description' => 'Description',
        ]);

        $response->assertInvalid([
            'title' => 'The title field must be at least 3 characters.',
        ]);
    }

    public function test_title_validation_max_length()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('articles.update', [
            'article' => $article,
        ]), [
            'title' => Str::random(256),
            'description' => 'Description',
        ]);

        $response->assertInvalid([
            'title' => 'The title field must not be greater than 255 characters.',
        ]);
    }

    public function test_description_validation_required()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('articles.update', [
            'article' => $article,
        ]), [
            'title' => 'Title',
        ]);

        $response->assertInvalid([
            'description' => 'The description field is required.',
        ]);
    }

    public function test_description_validation_min_length()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('articles.update', [
            'article' => $article,
        ]), [
            'title' => 'Title',
            'description' => 'as',
        ]);

        $response->assertInvalid([
            'description' => 'The description field must be at least 3 characters.',
        ]);
    }

    public function test_description_validation_max_length()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('articles.update', [
            'article' => $article,
        ]), [
            'title' => 'Title',
            'description' => Str::random(65536),
        ]);

        $response->assertInvalid([
            'description' => 'The description field must not be greater than 65535 characters.',
        ]);
    }

    public function test_we_cant_update_another_users_articles()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->postJson(route('articles.update', [
            'article' => $article,
        ]), [
            'title' => 'Title',
            'description' => 'Description',
        ]);

        $response->assertForbidden();
    }
}