<?php

namespace App\Services\Production;

use App\Repositories\AdminUserNotificationRepositoryInterface;
use App\Repositories\AdminUserRepositoryInterface;
use App\Services\AdminUserNotificationServiceInterface;

class AdminUserNotificationService extends NotificationService implements AdminUserNotificationServiceInterface
{
    public function __construct(
        AdminUserNotificationRepositoryInterface $notificationRepository,
        AdminUserRepositoryInterface $adminUserRepository
    ) {
        parent::__construct($notificationRepository, $adminUserRepository);
    }
}
