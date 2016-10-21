<?php

namespace Tests\Services;

use Tests\TestCase;

class LanguageDetectionServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\LanguageDetectionServiceInterface $service */
        $service = \App::make(\App\Services\LanguageDetectionServiceInterface::class);
        $this->assertNotNull($service);
    }

    public function testNormalize()
    {
        /** @var  \App\Services\LanguageDetectionServiceInterface $service */
        $service = \App::make(\App\Services\LanguageDetectionServiceInterface::class);
        $this->assertNotNull($service);

        $locale = $service->normalize('en');
        $this->assertEquals('en', $locale);

        $locale = $service->normalize('JA');
        $this->assertEquals('ja', $locale);

        $locale = $service->normalize('hage');
        $this->assertEquals('en', $locale);
    }

    public function testDetect()
    {
        /** @var  \App\Services\LanguageDetectionServiceInterface $service */
        $service = \App::make(\App\Services\LanguageDetectionServiceInterface::class);
        $this->assertNotNull($service);

        $locale = $service->detect('en');
        $this->assertEquals('en', $locale);
    }
}
