<?php

namespace Tests\Services;

use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\ImageServiceInterface $service */
        $service = \App::make(\App\Services\ImageServiceInterface::class);
        $this->assertNotNull($service);
    }
}
