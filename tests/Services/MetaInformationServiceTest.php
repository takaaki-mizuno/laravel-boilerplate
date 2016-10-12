<?php

namespace Tests\Services;

use Tests\TestCase;

class MetaInformationServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\MetaInformationServiceInterface $service */
        $service = \App::make(\App\Services\MetaInformationServiceInterface::class);
        $this->assertNotNull($service);
    }

    public function testGetKeywordArray()
    {
        /** @var  \App\Services\MetaInformationServiceInterface $service */
        $service = \App::make(\App\Services\MetaInformationServiceInterface::class);
        $result = $service->getKeywordArray('test1,test2,test3');
        $this->assertEquals(['test1', 'test2', 'test3'], $result);

        $result = $service->getKeywordArray('test1,test2,test1,test3');
        $this->assertEquals(['test1', 'test2', 'test3'], $result);
    }
}
