<?php

use App\Http\Controllers\API\V1\Article\ArticleController;
use App\Http\Controllers\API\V1\Article\CommentController;
use App\Http\Controllers\API\V1\Article\VoteController;
use App\Http\Controllers\API\V1\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::get('me', UserController::class);

        /** Articles. */
        Route::prefix('articles')->group(function () {
            Route::get('', [ArticleController::class, 'index'])->name('articles');
            Route::post('', [ArticleController::class, 'store'])->name('articles.store');
            Route::get('{article}', [ArticleController::class, 'show'])->name('articles.show');
            Route::post('{article}', [ArticleController::class, 'update'])->name('articles.update');
            Route::post('{article}/destroy', [ArticleController::class, 'destroy'])->name('articles.destroy');

            Route::prefix('{article}/vote')->group(function () {
                Route::post('', [VoteController::class, 'store'])->name('articles.vote.store');
                Route::post('destroy', [VoteController::class, 'store'])->name('articles.vote.destroy');
            });

            Route::prefix('{article}/comments')->group(function () {
                Route::get('', [CommentController::class, 'index'])->name('articles.comments.index');
                Route::post('', [CommentController::class, 'store'])->name('articles.comments.store');
                Route::get('{comment}', [CommentController::class, 'index'])->name('articles.comments.show');
                Route::post('{comment}', [CommentController::class, 'destroy'])->name('articles.comments.destroy');
            });
        });
    });
