<?php

namespace Tests\Services;

use Tests\TestCase;

class GoogleAnalyticsServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\GoogleAnalyticsServiceInterface $service */
        $service = \App::make(\App\Services\GoogleAnalyticsServiceInterface::class);
        $this->assertNotNull($service);
    }
}
