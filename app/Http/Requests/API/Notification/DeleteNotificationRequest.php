<?php

namespace App\Http\Requests\API\Notification;

use Illuminate\Foundation\Http\FormRequest;

use Auth;

class DeleteNotificationRequest extends FormRequest
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
            'id'             => 'required|uuid',
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
            'id.required' => 'The uuid of the notification is required',
            'read_at.required' => 'The read_at date is required and must be in the format: Y-m-d H:i:s',
            'read_at.date_format' => 'The date must be in the format: Y-m-d H:i:s',
        ];
    }
}
