<?php

namespace App\Http\Requests\API\ItemProperty;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BulkUpdateItemPropertiesRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'key' => 'required|string',
            'old_value' => 'required|string',
            'new_value' => 'required|string',
            'language' => 'sometimes|string|max:2',
            'status_id' => 'sometimes|integer',
        ];
    }
}