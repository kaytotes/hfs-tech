<?php

namespace App\Http\Requests\API\V1\Article;

use App\Enums\SortDirection;
use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sort' => [
                'required',
                Rule::in(Article::getSortableColumns()),
            ],
            'direction' => [
                'required',
                Rule::in([
                    SortDirection::ASC->value,
                    SortDirection::DESC->value,
                ]),
            ],
        ];
    }
}
