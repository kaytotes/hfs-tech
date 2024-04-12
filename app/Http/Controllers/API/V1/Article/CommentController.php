<?php

namespace App\Http\Controllers\API\V1\Article;

use App\Actions\Article\Comment\CreateNewComment;
use App\Actions\Article\Comment\DeleteComment;
use App\Actions\Article\Comment\GetArticleComments;
use App\Actions\Article\Comment\GetChildren;
use App\Enums\SortDirection;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Article\Comment\GetCommentsRequest;
use App\Http\Requests\API\V1\Article\Comment\StoreCommentRequest;
use App\Http\Resources\API\V1\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct(
        private GetArticleComments $getArticleCommentsAction,
        private CreateNewComment $createNewCommentAction,
        private DeleteComment $deleteCommentAction,
        private GetChildren $getChildrenAction,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetCommentsRequest $request, Article $article): AnonymousResourceCollection
    {
        $comments = $this->getArticleCommentsAction->handle(
            $article,
            $request->get('sort'),
            SortDirection::from($request->get('direction')),
        );

        return CommentResource::collection($comments);
    }

    /**
     * Get the child comments of a comment.
     *
     * @param Article $article
     * @param Comment $comment
     */
    public function show(GetCommentsRequest $request, Article $article, Comment $comment): AnonymousResourceCollection
    {
        $children = $this->getChildrenAction->handle(
            $comment,
            $request->get('sort'),
            SortDirection::from($request->get('direction')),
        );

        return CommentResource::collection($children);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Article $article): CommentResource
    {
        $comment = $this->createNewCommentAction->handle(
            auth()->user(),
            $article,
            $request->get('parent_id'),
            $request->get('body')
        );

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article, Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $this->deleteCommentAction->handle($comment);

        return response()->json([], Response::HTTP_OK);
    }
}
