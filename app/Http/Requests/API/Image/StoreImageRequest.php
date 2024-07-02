<?php

namespace App\Http\Requests\API\Image;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
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
            'item_id' => 'required|integer',
            'file'    => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ];
    }
}
