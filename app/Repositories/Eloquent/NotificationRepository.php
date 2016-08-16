<?php namespace App\Repositories\Eloquent;

use \App\Repositories\NotificationRepositoryInterface;
use \App\Models\Notification;

class NotificationRepository extends SingleKeyModelRepository implements NotificationRepositoryInterface
{

    public function getBlankModel()
    {
        return new Notification();
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
