<?php

class SlackServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\SlackServiceInterface $service */
        $service = App::make(\App\Services\SlackServiceInterface::class);
        $this->assertNotNull($service);
    }

}
