<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormJsonRequest;

class RegisterRequest extends FormJsonRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'displayName' => 'required|string|min:8|max:50',
            'email'       => 'required|email|max:320|unique:users,email',
            'password'    => 'required|min:6|max:100',
            'image'       => 'nullable|string',
        ];
    }
}
