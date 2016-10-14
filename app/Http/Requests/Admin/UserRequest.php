<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class UserRequest extends BaseRequest
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
            'email' => 'email',
            'password' => 'min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => '',
            'password.min' => '',
        ];
    }
}
