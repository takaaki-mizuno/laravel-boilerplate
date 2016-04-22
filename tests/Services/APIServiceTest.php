<?php

class APIServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\APIServiceInterface $service */
        $service = App::make(\App\Services\APIServiceInterface::class);
        $this->assertNotNull($service);
    }

}
