<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BaseArticleRequest extends FormRequest
{
    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $input = $this->input();

        if ($this->has('article.title')) {
            \Arr::set($input, 'article.slug', \Str::slug($this->input('article.title')));
        }

        $this->merge($input);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'article' => 'array',
            'article.slug' => 'string|max:255|unique:articles,slug',
            'article.title' => 'string|max:255',
            'article.description' => 'string',
            'article.body' => 'string',
        ];
    }
}
