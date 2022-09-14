<?php

namespace App\Http\Requests\API\User;

use Illuminate\Foundation\Http\FormRequest;

use Auth;

class UpdateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'id'             => 'required|integer',
            'name'           => 'required|string|min:1|max:255',
            'email'          => 'required|unique:users,email,'.request()->id.'|email|max:255',
            'password'       => 'sometimes|required|string|min:8|max:255|confirmed',
            'group_id'       => 'nullable|numeric',
            'is_group_admin' => 'nullable|boolean',
            'role'           => 'nullable|string|max:64',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'name.required' => 'The name of the user is required',
            'name.string' => 'The name must be a string',
        ];
    }
}
