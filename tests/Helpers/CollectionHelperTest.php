<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class CollectionHelperTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetSelectOptions()
    {

        /** @var  \App\Helpers\CollectionHelperInterface $helper */
        $helper = App::make('App\Helpers\CollectionHelperInterface');
        /** @var \App\Repositories\AdminUserRepositoryInterface $repository */
        $repository = App::make('App\Repositories\AdminUserRepositoryInterface');
        $adminUsers = $repository->all();

        $expects = [];
        foreach( $adminUsers as $adminUser ) {
            /** @var \App\Models\AdminUser $adminUser */
            $expects[$adminUser->id] = $adminUser->name;
        }

        $result = $helper->getSelectOptions($adminUsers);
        $this->assertEquals($expects, $result);
    }

}
