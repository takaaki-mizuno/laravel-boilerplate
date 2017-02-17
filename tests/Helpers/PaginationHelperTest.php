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

        $data = $helper->data('id', 'desc', 10, 10, 100, '/abc', []);

        $this->assertEquals($data['previousPageLink'], '/abc?page=1&order=id&direction=desc');
        $this->assertEquals($data['nextPageLink'], '/abc?page=3&order=id&direction=desc');
    }
}
