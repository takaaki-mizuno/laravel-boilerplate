<?php

class LanguageDetectionServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\LanguageDetectionServiceInterface $service */
        $service = App::make(\App\Services\LanguageDetectionServiceInterface::class);
        $this->assertNotNull($service);
    }

}
