<?php
namespace Tests\Controllers\Admin;

use Tests\TestCase;

class SiteConfigurationControllerTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\SiteConfigurationController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\SiteConfigurationController::class);
        $this->assertNotNull($controller);
    }

    public function setUp()
    {
        parent::setUp();
        $authUser = \App\Models\AdminUser::first();
        $this->be($authUser, 'admins');
    }

    public function testGetList()
    {
        $response = $this->action('GET', 'Admin\SiteConfigurationController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\SiteConfigurationController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $siteConfiguration = factory(\App\Models\SiteConfiguration::class)->make();
        $this->action('POST', 'Admin\SiteConfigurationController@store', [
                '_token' => csrf_token(),
            ] + $siteConfiguration->toFillableArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $siteConfiguration = factory(\App\Models\SiteConfiguration::class)->create();
        $this->action('GET', 'Admin\SiteConfigurationController@show', [$siteConfiguration->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $siteConfiguration = factory(\App\Models\SiteConfiguration::class)->create();

        $name = $faker->name;
        $id = $siteConfiguration->id;

        $siteConfiguration->name = $name;

        $this->action('PUT', 'Admin\SiteConfigurationController@update', [$id], [
                '_token' => csrf_token(),
            ] + $siteConfiguration->toFillableArray());
        $this->assertResponseStatus(302);

        $newSiteConfiguration = \App\Models\SiteConfiguration::find($id);
        $this->assertEquals($name, $newSiteConfiguration->name);
    }

    public function testDeleteModel()
    {
        $siteConfiguration = factory(\App\Models\SiteConfiguration::class)->create();

        $id = $siteConfiguration->id;

        $this->action('DELETE', 'Admin\SiteConfigurationController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);
        $this->assertRedirectedToAction('Admin\SiteConfigurationController@index');

        $checkSiteConfiguration = \App\Models\SiteConfiguration::find($id);
        $this->assertNull($checkSiteConfiguration);
    }
}
