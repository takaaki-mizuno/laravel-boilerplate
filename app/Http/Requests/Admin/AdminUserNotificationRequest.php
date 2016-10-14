<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\AdminUserNotificationRepositoryInterface;

class AdminUserNotificationRequest extends BaseRequest
{
    /** @var \App\Repositories\AdminUserNotificationRepositoryInterface */
    protected $adminUserNotificationRepository;

    public function __construct(AdminUserNotificationRepositoryInterface $adminUserNotificationRepository)
    {
        $this->adminUserNotificationRepository = $adminUserNotificationRepository;
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
        return $this->adminUserNotificationRepository->rules();
    }

    public function messages()
    {
        return $this->adminUserNotificationRepository->messages();
    }
}
