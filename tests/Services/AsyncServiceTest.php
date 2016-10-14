<?php

namespace Tests\Services;

use Tests\TestCase;

class AsyncServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\AsyncServiceInterface $service */
        $service = \App::make(\App\Services\AsyncServiceInterface::class);
        $this->assertNotNull($service);
    }
}
