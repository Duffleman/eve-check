<?php

namespace App\Http\Requests;

class UpdateNotificationSettings extends Request
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
            'frequency' => 'required|in:-1,1,10,20,30,60',
        ];
    }
}
