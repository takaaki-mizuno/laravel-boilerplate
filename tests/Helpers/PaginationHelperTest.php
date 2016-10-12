<?php

namespace Tests\Helpers;

use Tests\TestCase;

class PaginationHelperTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Helpers\PaginationHelperInterface $helper */
        $helper = \App::make(\App\Helpers\PaginationHelperInterface::class);
        $this->assertNotNull($helper);
    }

    public function testRenderPager()
    {
        /** @var  \App\Helpers\PaginationHelperInterface $helper */
        $helper = \App::make(\App\Helpers\PaginationHelperInterface::class);
        $this->assertNotNull($helper);

        $data = $helper->data(100, 100, 1500, '/abc', []);

        $this->assertEquals($data['previousPageLink'], '/abc?offset=0&limit=100');
        $this->assertEquals($data['nextPageLink'], '/abc?offset=200&limit=100');
    }
}
