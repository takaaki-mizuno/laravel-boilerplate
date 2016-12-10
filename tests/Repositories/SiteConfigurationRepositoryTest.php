<?php

namespace Tests\Repositories;

use App\Models\SiteConfiguration;
use Tests\TestCase;

class SiteConfigurationRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\SiteConfigurationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SiteConfigurationRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $siteConfigurations = factory(SiteConfiguration::class, 3)->create();
        $siteConfigurationIds = $siteConfigurations->pluck('id')->toArray();

        /** @var  \App\Repositories\SiteConfigurationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SiteConfigurationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $siteConfigurationsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(SiteConfiguration::class, $siteConfigurationsCheck[0]);

        $siteConfigurationsCheck = $repository->getByIds($siteConfigurationIds);
        $this->assertEquals(3, count($siteConfigurationsCheck));
    }

    public function testFind()
    {
        $siteConfigurations = factory(SiteConfiguration::class, 3)->create();
        $siteConfigurationIds = $siteConfigurations->pluck('id')->toArray();

        /** @var  \App\Repositories\SiteConfigurationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SiteConfigurationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $siteConfigurationCheck = $repository->find($siteConfigurationIds[0]);
        $this->assertEquals($siteConfigurationIds[0], $siteConfigurationCheck->id);
    }

    public function testCreate()
    {
        $siteConfigurationData = factory(SiteConfiguration::class)->make();

        /** @var  \App\Repositories\SiteConfigurationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SiteConfigurationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $siteConfigurationCheck = $repository->create($siteConfigurationData->toFillableArray());
        $this->assertNotNull($siteConfigurationCheck);
    }

    public function testUpdate()
    {
        $siteConfigurationData = factory(SiteConfiguration::class)->create();

        /** @var  \App\Repositories\SiteConfigurationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SiteConfigurationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $siteConfigurationCheck = $repository->update($siteConfigurationData, $siteConfigurationData->toFillableArray());
        $this->assertNotNull($siteConfigurationCheck);
    }

    public function testDelete()
    {
        $siteConfigurationData = factory(SiteConfiguration::class)->create();

        /** @var  \App\Repositories\SiteConfigurationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SiteConfigurationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($siteConfigurationData);

        $siteConfigurationCheck = $repository->find($siteConfigurationData->id);
        $this->assertNull($siteConfigurationCheck);
    }
}
