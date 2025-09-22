<?php

namespace App\Http\Requests\API\ItemProperty;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateItemPropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isEditor());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<mixed>
     */
    public function rules()
    {
        return [
            'language' => 'required|string|size:2',
            'item_id' => 'nullable|integer|exists:items,id',
            'parent_id' => 'nullable|integer',
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:16777215',
            'status_id' => 'sometimes|integer',
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
            'language.required' => 'The language is required',
            'language.size' => 'The language must be exactly 2 characters',
            'item_id.exists' => 'The specified item does not exist',
            'key.required' => 'The property key is required',
            'value.required' => 'The property value is required',
        ];
    }
}
