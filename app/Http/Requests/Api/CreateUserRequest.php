<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'user.username' => 'required|max:25',
            'user.email' => 'required|email|max:255',
            'user.password' => 'required',
        ];
    }
}
