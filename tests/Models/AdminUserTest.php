<?php

namespace Tests\Models;

use App\Models\AdminUser;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\AdminUser $adminUser */
        $adminUser = new AdminUser();
        $this->assertNotNull($adminUser);
    }

    public function testStoreNew()
    {
        /* @var  \App\Models\AdminUser $adminUser */
        $adminUserModel = new AdminUser();

        $adminUserData = factory(AdminUser::class)->make();
        foreach ($adminUserData->toFillableArray() as $key => $value) {
            $adminUserModel->$key = $value;
        }
        $adminUserModel->save();

        $this->assertNotNull(AdminUser::find($adminUserModel->id));
    }
}
