<?php

namespace Tests\Services;

use Tests\TestCase;

class ArticleServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\ArticleServiceInterface $service */
        $service = \App::make(\App\Services\ArticleServiceInterface::class);
        $this->assertNotNull($service);
    }
}
