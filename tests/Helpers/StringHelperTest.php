<?php

class StringHelperTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Helpers\StringHelperInterface $helper */
        $helper = App::make('App\Helper\StringHelperInterface');
        $this->assertNotNull($helper);
    }

}
