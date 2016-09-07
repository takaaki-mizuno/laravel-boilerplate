<?php namespace Tests\Services;

use Tests\TestCase;

class AdminUserNotificationServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\AdminUserNotificationServiceInterface $service */
        $service = \App::make(\App\Services\AdminUserNotificationServiceInterface::class);
        $this->assertNotNull($service);
    }

}
