<?php namespace Tests\Helpers;

use Tests\TestCase;

class RedirectHelperTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Helpers\RedirectHelperInterface $helper */
        $helper = \App::make(\App\Helpers\RedirectHelperInterface::class);
        $this->assertNotNull($helper);
    }

}
