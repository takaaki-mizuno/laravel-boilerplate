<?php namespace Tests\Helpers;

use Tests\TestCase;

class TypeHelperTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Helpers\TypeHelperInterface $helper */
        $helper = \App::make(\App\Helpers\TypeHelperInterface::class);
        $this->assertNotNull($helper);
    }

}
