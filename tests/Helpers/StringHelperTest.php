<?php

namespace Tests\Helpers;

use Tests\TestCase;

class StringHelperTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Helpers\StringHelperInterface $helper */
        $helper = \App::make(\App\Helpers\StringHelperInterface::class);
        $this->assertNotNull($helper);
    }

    public function testRandomString()
    {
        /** @var  \App\Helpers\StringHelperInterface $helper */
        $helper = \App::make(\App\Helpers\StringHelperInterface::class);
        $string = $helper->randomString(10);
        $this->assertEquals(10, strlen($string));

        $anotherString = $helper->randomString(10);
        $this->assertNotEquals($anotherString, $string);
    }
}
