<?php

namespace Tests\Models;

use App\Models\UserNotification;
use Tests\TestCase;

class UserNotificationTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\UserNotification $userNotification */
        $userNotification = new UserNotification();
        $this->assertNotNull($userNotification);
    }

    public function testStoreNew()
    {
        /* @var  \App\Models\UserNotification $userNotification */
        $userNotificationModel = new UserNotification();

        $userNotificationData = factory(UserNotification::class)->make();
        foreach ($userNotificationData->toFillableArray() as $key => $value) {
            $userNotificationModel->$key = $value;
        }
        $userNotificationModel->save();

        $this->assertNotNull(UserNotification::find($userNotificationModel->id));
    }
}
