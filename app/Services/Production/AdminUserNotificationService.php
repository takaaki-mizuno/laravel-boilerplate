<?php namespace App\Services\Production;

use App\Repositories\AdminUserNotificationRepositoryInterface;
use \App\Services\AdminUserNotificationServiceInterface;

class AdminUserNotificationService extends NotificationService implements AdminUserNotificationServiceInterface
{
    public function __construct(
        AdminUserNotificationRepositoryInterface $notificationRepository
    )
    {
        parent::__construct($notificationRepository);
    }
}
