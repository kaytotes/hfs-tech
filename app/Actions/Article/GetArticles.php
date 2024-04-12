<?php

namespace App\Actions\Article;

use App\Enums\SortDirection;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use InvalidArgumentException;

class GetArticles
{
    /**
     * @param string $sort Column to sort by.
     * @param SortDirection $direction asc or desc
     * @param boolean $paginate Whether to paginate.
     * @throws InvalidArgumentException
     * @return Collection|Paginator
     */
    public function handle(string $sort, SortDirection $direction, bool $paginate): Collection|Paginator
    {
        if (! in_array($sort, Article::getSortableColumns()))
            throw new InvalidArgumentException("Invalid Sort Type: $sort");

        $query = Article::orderBy($sort, $direction->value)
            ->with([
                'user',
            ])->withCount([
                'comments',
                'votes',
            ]);

        if ($paginate)
            $articles = $query->simplePaginate(16);
        else
            $articles = $query->get();

        return $articles;
    }
}