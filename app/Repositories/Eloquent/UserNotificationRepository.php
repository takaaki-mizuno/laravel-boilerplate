<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserNotificationRepositoryInterface;
use App\Models\UserNotification;

class UserNotificationRepository extends NotificationRepository  implements UserNotificationRepositoryInterface
{
    public function getBlankModel()
    {
        return new UserNotification();
    }
}
