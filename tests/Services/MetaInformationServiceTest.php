<?php

class MetaInformationServiceTest extends TestCase
{

    public function testGetKeywordArray()
    {
        /** @var  \App\Services\MetaInformationService $service */
        $service = App::make('App\Services\MetaInformationService');
        $result = $service->getKeywordArray('test1,test2,test3');
        $this->assertEquals(['test1','test2','test3'], $result);

        $result = $service->getKeywordArray('test1,test2,test1,test3');
        $this->assertEquals(['test1','test2','test3'], $result);
    }

}
