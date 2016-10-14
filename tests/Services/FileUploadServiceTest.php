<?php

namespace Tests\Services;

use Tests\TestCase;

class FileUploadServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\FileUploadServiceInterface $service */
        $service = \App::make(\App\Services\FileUploadServiceInterface::class);
        $this->assertNotNull($service);
    }
}
