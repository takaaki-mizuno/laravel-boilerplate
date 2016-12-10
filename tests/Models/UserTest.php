<?php

namespace Tests\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\User $user */
        $user = new User();
        $this->assertNotNull($user);
    }

    public function testStoreNew()
    {
        /* @var  \App\Models\User $user */
        $userModel = new User();

        $userData = factory(User::class)->make();
        foreach ($userData->toFillableArray() as $key => $value) {
            $userModel->$key = $value;
        }
        $userModel->save();

        $this->assertNotNull(User::find($userModel->id));
    }
}
