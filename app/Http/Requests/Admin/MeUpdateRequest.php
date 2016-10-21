<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class MeUpdateRequest extends BaseRequest
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
            'email' => 'required|email',
            'password' => 'min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => trans('admin.errors.requests.me.email.required'),
            'email.email' => trans('admin.errors.requests.me.email.email'),
            'password.min' => trans('admin.errors.requests.me.password.min'),
        ];
    }
}
