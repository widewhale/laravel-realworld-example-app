<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => 'array',
            'user.username' => 'max:25',
            'user.email' => 'email|max:255',
            'user.password' => 'string',
            'user.bio' => 'max:255',
            'user.image' => 'max:255',
        ];
    }
}
