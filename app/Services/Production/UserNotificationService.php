<?php

namespace App\Services\Production;

use App\Repositories\UserNotificationRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserNotificationServiceInterface;

class UserNotificationService extends NotificationService implements UserNotificationServiceInterface
{
    public function __construct(
        UserNotificationRepositoryInterface $notificationRepository,
        UserRepositoryInterface $userRepository
    ) {
        parent::__construct($notificationRepository, $userRepository);
    }
}
