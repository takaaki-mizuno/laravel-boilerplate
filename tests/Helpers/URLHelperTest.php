<?php

namespace Tests\Helpers;

use Tests\TestCase;

class URLHelperTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Helpers\URLHelperInterface $helper */
        $helper = \App::make(\App\Helpers\URLHelperInterface::class);
        $this->assertNotNull($helper);
    }

    public function testSwapHost()
    {
        /** @var  \App\Helpers\URLHelperInterface $helper */
        $helper = \App::make(\App\Helpers\URLHelperInterface::class);
        $result = $helper->swapHost('http://takaaki.info/path/to/somewhere', 'example.com');
        $this->assertEquals('http://example.com/path/to/somewhere', $result);
    }

    public function testNormalizeUrlPath()
    {
        /** @var  \App\Helpers\URLHelperInterface $helper */
        $helper = \App::make('App\Helpers\URLHelperInterface');
        $result = $helper->normalizeUrlPath('Test Strings');
        $this->assertEquals('test-strings', $result);
    }

    public function testAsset()
    {
        /** @var  \App\Helpers\URLHelperInterface $helper */
        $helper = \App::make(\App\Helpers\URLHelperInterface::class);
        $hash = config('asset.hash');
        $result = $helper->asset('img/test.png');
        $this->assertEquals('http://localhost/static/user/img/test.png?'.$hash, $result);

        $result = $helper->asset('img/test.png', 'common');
        $this->assertEquals('http://localhost/static/common/img/test.png?'.$hash, $result);
    }
}
