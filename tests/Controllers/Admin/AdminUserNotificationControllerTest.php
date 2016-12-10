<?php
namespace Tests\Controllers\Admin;

use Tests\TestCase;

class AdminUserNotificationControllerTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\AdminUserNotificationController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\AdminUserNotificationController::class);
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
        $response = $this->action('GET', 'Admin\AdminUserNotificationController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\AdminUserNotificationController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $adminUserNotification = factory(\App\Models\AdminUserNotification::class)->make();
        $this->action('POST', 'Admin\AdminUserNotificationController@store', [
                '_token' => csrf_token(),
            ] + $adminUserNotification->toFillableArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $adminUserNotification = factory(\App\Models\AdminUserNotification::class)->create();

        $this->action('GET', 'Admin\AdminUserNotificationController@show', [$adminUserNotification->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $adminUserNotification = factory(\App\Models\AdminUserNotification::class)->create();

        $text = $faker->sentence();
        $id = $adminUserNotification->id;

        $adminUserNotification->content = $text;

        $this->action('PUT', 'Admin\AdminUserNotificationController@update', [$id], [
                '_token' => csrf_token(),
            ] + $adminUserNotification->toFillableArray());
        $this->assertResponseStatus(302);

        $newAdminUserNotification = \App\Models\AdminUserNotification::find($id);
        $this->assertEquals($text, $newAdminUserNotification->content);
    }

    public function testDeleteModel()
    {
        $adminUserNotification = factory(\App\Models\AdminUserNotification::class)->create();

        $id = $adminUserNotification->id;

        $this->action('DELETE', 'Admin\AdminUserNotificationController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkAdminUserNotification = \App\Models\AdminUserNotification::find($id);
        $this->assertNull($checkAdminUserNotification);
    }
}
