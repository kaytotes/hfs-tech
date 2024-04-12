<?php

namespace App\Http\Controllers\API\V1\Article;

use App\Actions\Article\Vote\AddVote;
use App\Actions\Article\Vote\RemoveVote;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Article\Vote\StoreVoteRequest;
use App\Models\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VoteController extends Controller
{
    public function __construct(
        private AddVote $addVoteAction,
        private RemoveVote $removeVoteAction,
    ) {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoteRequest $request, Article $article): JsonResponse
    {
        $this->addVoteAction->handle(
            auth()->user(),
            $article,
            $request->get('direction'),
        );

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            $this->removeVoteAction->handle(
                auth()->user(),
                $article,
            );
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => '',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([], Response::HTTP_OK);
    }
}
