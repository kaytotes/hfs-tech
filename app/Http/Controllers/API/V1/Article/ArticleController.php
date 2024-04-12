<?php

namespace App\Http\Controllers\API\V1\Article;

use App\Actions\Article\CreateNewArticle;
use App\Actions\Article\DeleteArticle;
use App\Actions\Article\GetArticles;
use App\Actions\Article\UpdateArticle;
use App\Enums\SortDirection;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Article\IndexArticleRequest;
use App\Http\Requests\API\V1\Article\StoreArticleRequest;
use App\Http\Requests\API\V1\Article\UpdateArticleRequest;
use App\Http\Resources\API\V1\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    public function __construct(
        private GetArticles $getArticlesAction,
        private CreateNewArticle $createArticleAction,
        private UpdateArticle $updateArticleAction,
        private DeleteArticle $deleteArticleAction,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexArticleRequest $request): AnonymousResourceCollection
    {
        $articles = $this->getArticlesAction->handle(
            $request->get('sort'),
            SortDirection::from($request->get('direction')),
            true,
        );

        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request): ArticleResource
    {
        $article = $this->createArticleAction->handle(
            auth()->user(),
            $request->get('title'),
            $request->get('description'),
        );

        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): ArticleResource
    {
        $article->loadMissing('user');
        $article->loadCount('comments');

        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): ArticleResource
    {
        $this->authorize('update', $article);

        $article = $this->updateArticleAction->handle(
            $article,
            $request->get('title'),
            $request->get('description'),
        );

        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): JsonResponse
    {
        $this->authorize('delete', $article);

        $this->deleteArticleAction->handle($article);

        return response()->json([], Response::HTTP_OK);
    }
}
