<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\UserNotificationRepositoryInterface;

class UserNotificationRequest extends BaseRequest
{
    /** @var \App\Repositories\UserNotificationRepositoryInterface */
    protected $userNotificationRepository;

    public function __construct(UserNotificationRepositoryInterface $userNotificationRepository)
    {
        $this->userNotificationRepository = $userNotificationRepository;
    }

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
        return $this->userNotificationRepository->rules();
    }

    public function messages()
    {
        return $this->userNotificationRepository->messages();
    }
}
