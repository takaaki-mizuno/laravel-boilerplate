<?php namespace Tests\Helpers;

use Tests\TestCase;

class PaginationHelperTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Helpers\PaginationHelperInterface $helper */
        $helper = \App::make(\App\Helpers\PaginationHelperInterface::class);
        $this->assertNotNull($helper);
    }

}
