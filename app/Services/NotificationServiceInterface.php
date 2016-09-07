<?php namespace App\Services;

interface NotificationServiceInterface extends BaseServiceInterface
{

    /**
     * @param  string $type
     * @param  string $locale
     * @param  string $content
     * @return \App\Models\Notification|null
     */
    public function broadcastSystemMessage($type, $locale, $content);

    /**
     * @param int $userId
     * @param string $categoryType
     * @param string $type
     * @param string $locale
     * @param string $content
     * @param array $data
     * @return \App\Models\Notification|null
     */
    public function sendNotification( $userId, $categoryType, $type, $locale, $content, $data );
}