<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateArticleRequest extends BaseArticleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'article.title' => 'required',
            'article.description' => 'required',
            'article.body' => 'required',
            'article.tagList' => 'array',
            'article.tagList.*' => 'string|max:255',
        ]);
    }
}
