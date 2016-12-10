<?php

namespace Tests\Models;

use App\Models\AdminUserNotification;
use Tests\TestCase;

class AdminUserNotificationTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\AdminUserNotification $adminUserNotification */
        $adminUserNotification = new AdminUserNotification();
        $this->assertNotNull($adminUserNotification);
    }

    public function testStoreNew()
    {
        /* @var  \App\Models\AdminUserNotification $adminUserNotification */
        $adminUserNotificationModel = new AdminUserNotification();

        $adminUserNotificationData = factory(AdminUserNotification::class)->make();
        foreach ($adminUserNotificationData->toFillableArray() as $key => $value) {
            $adminUserNotificationModel->$key = $value;
        }
        $adminUserNotificationModel->save();

        $this->assertNotNull(AdminUserNotification::find($adminUserNotificationModel->id));
    }
}
