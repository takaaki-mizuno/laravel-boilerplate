<?php

namespace Tests\Services;

use Tests\TestCase;

class UserServiceAuthenticationServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\UserServiceAuthenticationServiceInterface $service */
        $service = \App::make(\App\Services\UserServiceAuthenticationServiceInterface::class);
        $this->assertNotNull($service);
    }
}
