<?php namespace App\Services\Production;

use App\Models\Notification;
use App\Repositories\NotificationRepositoryInterface;
use \App\Services\UserNotificationServiceInterface;

class NotificationService extends BaseService implements UserNotificationServiceInterface
{

    /** @var \App\Repositories\NotificationRepositoryInterface */
    protected $notificationRepository;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository
    )
    {
        $this->notificationRepository = $notificationRepository;
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
                return null;
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
        ]);

        return $notification;
    }

}
