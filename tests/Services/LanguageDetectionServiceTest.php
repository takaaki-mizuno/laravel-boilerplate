<?php

class LanguageDetectionServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\LanguageDetectionServiceInterface $service */
        $service = App::make(\App\Services\LanguageDetectionServiceInterface::class);
        $this->assertNotNull($service);
    }

    public function testDetect()
    {
        /** @var  \App\Services\LanguageDetectionServiceInterface $service */
        $service = App::make(\App\Services\LanguageDetectionServiceInterface::class);
        $this->assertNotNull($service);

        $locale = $service->detect('en');
        $this->assertEquals('en', $locale);

    }

}
