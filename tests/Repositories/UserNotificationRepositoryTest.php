<?php

namespace Tests\Repositories;

use App\Models\UserNotification;
use Tests\TestCase;

class UserNotificationRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\UserNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\UserNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $userNotifications = factory(UserNotification::class, 3)->create();
        $userNotificationIds = $userNotifications->pluck('id')->toArray();

        /** @var  \App\Repositories\UserNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\UserNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $userNotificationsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(UserNotification::class, $userNotificationsCheck[0]);

        $userNotificationsCheck = $repository->getByIds($userNotificationIds);
        $this->assertEquals(3, count($userNotificationsCheck));
    }

    public function testFind()
    {
        $userNotifications = factory(UserNotification::class, 3)->create();
        $userNotificationIds = $userNotifications->pluck('id')->toArray();

        /** @var  \App\Repositories\UserNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\UserNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $userNotificationCheck = $repository->find($userNotificationIds[0]);
        $this->assertEquals($userNotificationIds[0], $userNotificationCheck->id);
    }

    public function testCreate()
    {
        $userNotificationData = factory(UserNotification::class)->make();

        /** @var  \App\Repositories\UserNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\UserNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $userNotificationCheck = $repository->create($userNotificationData->toFillableArray());
        $this->assertNotNull($userNotificationCheck);
    }

    public function testUpdate()
    {
        $userNotificationData = factory(UserNotification::class)->create();

        /** @var  \App\Repositories\UserNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\UserNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $userNotificationCheck = $repository->update($userNotificationData, $userNotificationData->toFillableArray());
        $this->assertNotNull($userNotificationCheck);
    }

    public function testDelete()
    {
        $userNotificationData = factory(UserNotification::class)->create();

        /** @var  \App\Repositories\UserNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\UserNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($userNotificationData);

        $userNotificationCheck = $repository->find($userNotificationData->id);
        $this->assertNull($userNotificationCheck);
    }
}
