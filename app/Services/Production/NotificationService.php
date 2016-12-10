<?php

namespace App\Services\Production;

use App\Models\Notification;
use App\Repositories\AuthenticatableRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Services\UserNotificationServiceInterface;

class NotificationService extends BaseService implements UserNotificationServiceInterface
{
    /** @var \App\Repositories\NotificationRepositoryInterface */
    protected $notificationRepository;

    /** @var AuthenticatableRepositoryInterface $authenticatableRepository */
    protected $authenticatableRepository;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        AuthenticatableRepositoryInterface $authenticatableRepository
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->authenticatableRepository = $authenticatableRepository;
    }

    public function broadcastSystemMessage($type, $locale, $content)
    {
        return $this->sendNotification(Notification::BROADCAST_USER_ID, Notification::CATEGORY_TYPE_SYSTEM_MESSAGE,
            $type, $locale, $content, []);
    }

    public function sendNotification($userId, $categoryType, $type, $locale, $content, $data)
    {
        if ($userId == Notification::BROADCAST_USER_ID) {
            if (empty($locale)) {
                return '';
            }
        } else {
            $locale = '';
        }
        $notification = $this->notificationRepository->create([
            'user_id'       => $userId,
            'category_type' => $categoryType,
            'type'          => $type,
            'locale'        => $locale,
            'content'       => $content,
            'data'          => $data,
            'sent_at'       => \DateTimeHelper::now(),
        ]);

        return $notification;
    }

    public function readUntil($user, $notification)
    {
        if ($notification->user_id != Notification::BROADCAST_USER_ID && $notification->user_id != $user->id) {
            return false;
        }

        $user->last_notification_id = $notification->id;
        $this->authenticatableRepository->save($user);

        return $this->notificationRepository->updateReadByUserId($user->id, $notification->id);
    }

    public function getUnreadNotificationCount($user)
    {
        return $this->notificationRepository->countUnreadByUserId($user->id, $user->last_notification_id);
    }

    public function getNotifications($user, $offset, $limit)
    {
        return $this->notificationRepository->getByUserId($user->id, 'id', 'desc', $offset, $limit);
    }

    public function countNotifications($user)
    {
        return $this->notificationRepository->countByUserId($user->id);
    }
}
