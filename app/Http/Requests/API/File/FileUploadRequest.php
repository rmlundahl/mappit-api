<?php

namespace App\Http\Requests\API\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class FileUploadRequest extends FormRequest
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
            'file' => [
                'file',
                'required',
                'mimes:xls,xlsx',
                'max:2048'
            ]
        ];
    }

    public function messages()
    {
        return [
            'file' => [
                'mimes' => __('user.file.mimes'),
                'max' => __('user.file.max'),
            ]
        ];
    }

}
