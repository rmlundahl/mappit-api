<?php

namespace App\Http\Requests\API\Item;

use Illuminate\Foundation\Http\FormRequest;

class DeleteItemRequest extends FormRequest
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
            'id' => 'required|integer',
            'language' => 'required|string|size:2'
        ];
    }

}
