<?php

namespace App\Presenters;

class AdminUserNotificationPresenter extends BasePresenter
{
    public function userName()
    {
        if ($this->entity->user_id == 0) {
            return 'Broadcast';
        }

        $user = $this->entity->adminUser;
        if (empty($user)) {
            return 'Unknown';
        }

        return $user->name;
    }
}
