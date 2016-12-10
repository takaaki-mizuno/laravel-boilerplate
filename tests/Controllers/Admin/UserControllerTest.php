<?php
namespace Tests\Controllers\Admin;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\UserController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\UserController::class);
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
        $response = $this->action('GET', 'Admin\UserController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\UserController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $user = factory(\App\Models\User::class)->make();
        $this->action('POST', 'Admin\UserController@store', [
                '_token' => csrf_token(),
            ] + $user->toFillableArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $user = factory(\App\Models\User::class)->create();
        $this->action('GET', 'Admin\UserController@show', [$user->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $user = factory(\App\Models\User::class)->create();

        $name = $faker->name;
        $id = $user->id;

        $user->name = $name;

        $this->action('PUT', 'Admin\UserController@update', [$id], [
                '_token' => csrf_token(),
            ] + $user->toFillableArray());
        $this->assertResponseStatus(302);

        $newUser = \App\Models\User::find($id);
        $this->assertEquals($name, $newUser->name);
    }

    public function testDeleteModel()
    {
        $user = factory(\App\Models\User::class)->create();

        $id = $user->id;

        $this->action('DELETE', 'Admin\UserController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);
        $this->assertRedirectedToAction('Admin\UserController@index');

        $checkUser = \App\Models\User::find($id);
        $this->assertNull($checkUser);
    }
}
