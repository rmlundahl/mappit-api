<?php

namespace App\Http\Requests\API\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<mixed>
     */
    public function rules()
    {
        return [
            'name'           => 'required|string|min:1|max:255',
            'email'          => 'required|unique:users,email|email|max:255',
            'password'       => 'required|string|min:8|max:255',
            'group_id'       => 'nullable|numeric',
            'is_group_admin' => 'nullable|boolean',
            'role'           => 'nullable|string|max:64',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array<mixed>
    */
    public function messages()
    {
        return [
            'name.required' => 'The name of the user is required',
            'name.string' => 'The name must be a string',
        ];
    }
}
