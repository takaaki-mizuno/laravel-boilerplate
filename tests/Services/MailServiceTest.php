<?php

namespace Tests\Services;

use Tests\TestCase;

class MailServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\MailServiceInterface $service */
        $service = \App::make(\App\Services\MailServiceInterface::class);
        $this->assertNotNull($service);
    }
}
