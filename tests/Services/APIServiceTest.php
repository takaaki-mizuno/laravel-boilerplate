<?php

namespace Tests\Services;

use Tests\TestCase;

class APIServiceTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Services\APIServiceInterface $service */
        $service = \App::make(\App\Services\APIServiceInterface::class);
        $this->assertNotNull($service);
    }

    public function testError()
    {
        /** @var  \App\Services\APIServiceInterface $service */
        $service = \App::make(\App\Services\APIServiceInterface::class);
        $error = $service->error('unknown');
        $config = config('api.errors.unknown');

        $this->assertEquals($error->getStatusCode(), $config['status_code']);
    }

    public function testGetAPIArray()
    {
        /** @var  \App\Services\APIServiceInterface $service */
        $service = \App::make(\App\Services\APIServiceInterface::class);
        $users = factory(\App\Models\User::class, 10)->make();
        $array = $service->getAPIArray($users);
        $this->assertInternalType('array', $array, 'Array returned');
        $this->assertCount(10, $array, 'Array has 10 entries');
    }

    public function testGetAPIListObject()
    {
        /** @var  \App\Services\APIServiceInterface $service */
        $service = \App::make(\App\Services\APIServiceInterface::class);
        $users = factory(\App\Models\User::class, 10)->make();
        $list = $service->getAPIListObject($users, 'users', 30, 10, 20);
        $this->assertInternalType('array', $list, 'Array returned');
        $this->assertArrayHasKey('users', $list, 'name of list');
        $this->assertEquals(20, $list['count']);
        $this->assertEquals(30, $list['offset']);
        $this->assertEquals(10, $list['limit']);
    }

    public function testListResponse()
    {
        /** @var  \App\Services\APIServiceInterface $service */
        $service = \App::make(\App\Services\APIServiceInterface::class);
        $users = factory(\App\Models\User::class, 10)->make();
        $response = $service->listResponse($users, 'users', 30, 10, 20, 201);
        $this->assertEquals($response->getStatusCode(), 201);
    }
}
