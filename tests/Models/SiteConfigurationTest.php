<?php

namespace Tests\Models;

use App\Models\SiteConfiguration;
use Tests\TestCase;

class SiteConfigurationTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\SiteConfiguration $siteConfiguration */
        $siteConfiguration = new SiteConfiguration();
        $this->assertNotNull($siteConfiguration);
    }

    public function testStoreNew()
    {
        /* @var  \App\Models\SiteConfiguration $siteConfiguration */
        $siteConfigurationModel = new SiteConfiguration();

        $siteConfigurationData = factory(SiteConfiguration::class)->make();
        foreach ($siteConfigurationData->toFillableArray() as $key => $value) {
            $siteConfigurationModel->$key = $value;
        }
        $siteConfigurationModel->save();

        $this->assertNotNull(SiteConfiguration::find($siteConfigurationModel->id));
    }
}
