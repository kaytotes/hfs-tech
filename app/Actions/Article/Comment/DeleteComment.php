<?php

namespace App\Actions\Article\Comment;

use App\Models\Comment;

class DeleteComment
{
    public function handle(Comment $comment)
    {
        $comment->delete();
    }
}