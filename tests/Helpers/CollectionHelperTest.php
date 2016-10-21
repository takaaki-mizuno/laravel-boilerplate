<?php

namespace Tests\Helpers;

use Tests\TestCase;

class CollectionHelperTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Helpers\CollectionHelperInterface $helper */
        $helper = \App::make(\App\Helpers\CollectionHelperInterface::class);
        $this->assertNotNull($helper);
    }

    public function testGetSelectOptions()
    {

        /** @var  \App\Helpers\CollectionHelperInterface $helper */
        $helper = \App::make(\App\Helpers\CollectionHelperInterface::class);
        /** @var \App\Repositories\AdminUserRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\AdminUserRepositoryInterface::class);
        $adminUsers = $repository->all();

        $expects = [];
        foreach ($adminUsers as $adminUser) {
            /* @var \App\Models\AdminUser $adminUser */
            $expects[ $adminUser->id ] = $adminUser->name;
        }

        $result = $helper->getSelectOptions($adminUsers);
        $this->assertEquals($expects, $result);
    }
}
