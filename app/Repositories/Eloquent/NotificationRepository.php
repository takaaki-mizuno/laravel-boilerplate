<?php

namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Repositories\NotificationRepositoryInterface;

class NotificationRepository extends SingleKeyModelRepository implements NotificationRepositoryInterface
{
    protected $userIdColumnName = 'user_id';

    /**
     * @return Notification
     */
    public function getBlankModel()
    {
        return new Notification();
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function getByUserId($userId, $order = 'id', $direction = 'desc', $offset, $limit)
    {
        $model = $this->getBlankModel();

        return $model->where('user_id', '=', $userId)->orWhere(function ($query) {
            $query->where('user_id', '=', Notification::BROADCAST_USER_ID)->where('locale', '=', \App::getLocale());
        })->orderBy($order, $direction)->offset($offset)->limit($limit)->get();
    }

    public function countByUserId($userId)
    {
        $model = $this->getBlankModel();

        return $model->where('user_id', '=', $userId)->orWhere(function ($query) {
            $query->where('user_id', '=', Notification::BROADCAST_USER_ID)->where('locale', '=', \App::getLocale());
        })->count();
    }

    public function getByCategoryTypeAndUserId(
        $categoryType,
        $userId,
        $order = 'id',
        $direction = 'desc',
        $offset,
        $limit
    ) {
        $model = $this->getBlankModel();

        return $model->whereCategoryType($this)->where('user_id', '=', $userId)->orWhere(function ($query) {
            $query->where('user_id', '=', Notification::BROADCAST_USER_ID)->where('locale', '=', \App::getLocale());
        })->orderBy($order, $direction)->offset($offset)->limit($limit)->get();
    }

    public function countUnreadByUserId($userId, $lastId)
    {
        $model = $this->getBlankModel();

        return $model->where('id', '>', $lastId)->where(function ($query) use ($userId) {
            $query->where('user_id', '=', $userId)->orWhere(function ($query) {
                $query->where('user_id', '=', Notification::BROADCAST_USER_ID)->where('locale', '=', \App::getLocale());
            });
        })->count();
    }

    public function updateReadByUserId($userId, $lastId)
    {
        $model = $this->getBlankModel();
        $model->where('id', '<=', $lastId)->whereUserId($userId)->update(['read' => true]);

        return true;
    }
}
