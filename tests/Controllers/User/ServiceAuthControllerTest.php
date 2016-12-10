<?php

namespace tests\Controllers\User;

use App\Models\User;
use Tests\TestCase;

/**
 * @group dev
 * */
class ServiceAuthControllerTest extends TestCase
{
    protected $useDatabase = true;

    public function testPostSignUpSuccess()
    {
        $this->post(action('User\AuthController@postSignUp'), [
            'email' => 'test_email@example.com',
            'password' => '123456',
        ])->assertRedirectedToAction('User\IndexController@index');

        $this->assertEquals(1, User::count());
    }

    public function testPostSignUpWithExistUser()
    {
        factory(User::class)->create([
           'email' => 'test_email@example.com',
        ]);
        $this->post(action('User\AuthController@postSignUp'), [
            'email' => 'test_email@example.com',
            'password' => '123456',
        ])->assertRedirectedToAction('User\AuthController@getSignUp');

        $this->assertEquals(1, User::count());
    }
}
