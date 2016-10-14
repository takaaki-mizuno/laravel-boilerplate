<?php

namespace App\Services;

interface NotificationServiceInterface extends BaseServiceInterface
{
    /**
     * @param string $type
     * @param string $locale
     * @param string $content
     *
     * @return \App\Models\Notification|null
     */
    public function broadcastSystemMessage($type, $locale, $content);

    /**
     * @param int    $userId
     * @param string $categoryType
     * @param string $type
     * @param string $locale
     * @param string $content
     * @param array  $data
     *
     * @return \App\Models\Notification|null
     */
    public function sendNotification($userId, $categoryType, $type, $locale, $content, $data);

    /**
     * @param \App\Models\AuthenticatableBase $user
     * @param \App\Models\Notification        $notification
     *
     * @return bool
     */
    public function readUntil($user, $notification);

    /**
     * @param \App\Models\AuthenticatableBase $user
     *
     * @return int
     */
    public function getUnreadNotificationCount($user);

    /**
     * @param \App\Models\AuthenticatableBase $user
     * @param int                             $offset
     * @param int                             $limit
     *
     * @return \App\Models\Notification[]
     */
    public function getNotifications($user, $offset, $limit);

    /**
     * @param \App\Models\AuthenticatableBase $user
     *
     * @return int
     */
    public function countNotifications($user);
}
