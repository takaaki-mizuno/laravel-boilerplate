<?php
namespace Tests\Controllers\Admin;

use Tests\TestCase;

class UserNotificationControllerTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\UserNotificationController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\UserNotificationController::class);
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
        $response = $this->action('GET', 'Admin\UserNotificationController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\UserNotificationController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $userNotification = factory(\App\Models\UserNotification::class)->make();
        $this->action('POST', 'Admin\UserNotificationController@store', [
                '_token' => csrf_token(),
            ] + $userNotification->toFillableArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $userNotification = factory(\App\Models\UserNotification::class)->create();
        $this->action('GET', 'Admin\UserNotificationController@show', [$userNotification->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $userNotification = factory(\App\Models\UserNotification::class)->create();

        $text = $faker->sentence();
        $id = $userNotification->id;

        $userNotification->content = $text;

        $this->action('PUT', 'Admin\UserNotificationController@update', [$id], [
                '_token' => csrf_token(),
            ] + $userNotification->toFillableArray());
        $this->assertResponseStatus(302);

        $newUserNotification = \App\Models\UserNotification::find($id);
        $this->assertEquals($text, $newUserNotification->content);
    }

    public function testDeleteModel()
    {
        $userNotification = factory(\App\Models\UserNotification::class)->create();

        $id = $userNotification->id;

        $this->action('DELETE', 'Admin\UserNotificationController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkUserNotification = \App\Models\UserNotification::find($id);
        $this->assertNull($checkUserNotification);
    }
}
