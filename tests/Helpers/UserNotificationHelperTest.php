<?php

namespace Tests\Helpers;

use Tests\TestCase;

class UserNotificationHelperTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Helpers\UserNotificationHelperInterface $helper */
        $helper = \App::make(\App\Helpers\UserNotificationHelperInterface::class);
        $this->assertNotNull($helper);
    }
}
