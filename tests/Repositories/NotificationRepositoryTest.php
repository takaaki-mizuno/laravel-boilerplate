<?php namespace Tests\Repositories;

use App\Models\Notification;
use Tests\TestCase;

class NotificationRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\NotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\NotificationRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $notifications = factory(Notification::class, 3)->create();
        $notificationIds = $notifications->pluck('id')->toArray();

        /** @var  \App\Repositories\NotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\NotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $notificationsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Notification::class, $notificationsCheck[0]);

        $notificationsCheck = $repository->getByIds($notificationIds);
        $this->assertEquals(3, count($notificationsCheck));
    }

    public function testFind()
    {
        $notifications = factory(Notification::class, 3)->create();
        $notificationIds = $notifications->pluck('id')->toArray();

        /** @var  \App\Repositories\NotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\NotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $notificationCheck = $repository->find($notificationIds[0]);
        $this->assertEquals($notificationIds[0], $notificationCheck->id);
    }

    public function testCreate()
    {
        $notificationData = factory(Notification::class)->make();

        /** @var  \App\Repositories\NotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\NotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $notificationCheck = $repository->create($notificationData->toArray());
        $this->assertNotNull($notificationCheck);
    }

    public function testUpdate()
    {
        $notificationData = factory(Notification::class)->create();

        /** @var  \App\Repositories\NotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\NotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $notificationCheck = $repository->update($notificationData, $notificationData->toArray());
        $this->assertNotNull($notificationCheck);
    }

    public function testDelete()
    {
        $notificationData = factory(Notification::class)->create();

        /** @var  \App\Repositories\NotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\NotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($notificationData);

        $notificationCheck = $repository->find($notificationData->id);
        $this->assertNull($notificationCheck);
    }

}
