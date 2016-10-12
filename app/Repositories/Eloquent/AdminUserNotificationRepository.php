<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AdminUserNotificationRepositoryInterface;
use App\Models\AdminUserNotification;

class AdminUserNotificationRepository extends NotificationRepository implements AdminUserNotificationRepositoryInterface
{
    public function getBlankModel()
    {
        return new AdminUserNotification();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
