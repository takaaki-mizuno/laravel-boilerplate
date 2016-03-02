<?php

class StringHelperTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Helpers\StringHelperInterface $helper */
        $helper = App::make('App\Helpers\StringHelperInterface');
        $this->assertNotNull($helper);
    }

}
