<?php

namespace App\Http\Requests\API\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'id'           => 'required|integer',
            'language'     => 'required|string|size:2',
            'item_type_id' => 'nullable|integer', // 10 = item, 20 = page
            'external_id'  => 'nullable|string|max:255', // only required if data is imported from external source, to keep track of updates on existing records
            'name'         => 'nullable|string|min:3|max:255',
            'slug'         => 'nullable|string|min:3|max:255',
            'content'      => 'nullable|string|max:16777215',
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
            'name.required' => 'The name of the item is required',
            'name.string' => 'The name must be a string',
        ];
    }
}
