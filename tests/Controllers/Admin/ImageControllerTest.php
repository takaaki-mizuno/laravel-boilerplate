<?php
namespace Tests\Controllers\Admin;

use Tests\TestCase;

class ImageControllerTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\ImageController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\ImageController::class);
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
        $response = $this->action('GET', 'Admin\ImageController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\ImageController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $image = factory(\App\Models\Image::class)->make();
        $this->action('POST', 'Admin\ImageController@store', [
                '_token' => csrf_token(),
            ] + $image->toFillableArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $image = factory(\App\Models\Image::class)->create();
        $this->action('GET', 'Admin\ImageController@show', [$image->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $image = factory(\App\Models\Image::class)->create();

        $name = $faker->name;
        $id = $image->id;

        $image->title = $name;

        $this->action('PUT', 'Admin\ImageController@update', [$id], [
                '_token' => csrf_token(),
            ] + $image->toFillableArray());
        $this->assertResponseStatus(302);

        $newImage = \App\Models\Image::find($id);
        $this->assertEquals($name, $newImage->title);
    }

    public function testDeleteModel()
    {
        $image = factory(\App\Models\Image::class)->create();

        $id = $image->id;

        $this->action('DELETE', 'Admin\ImageController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkImage = \App\Models\Image::find($id);
        $this->assertNull($checkImage);
    }
}
