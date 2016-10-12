<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MetaInformationHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\MetaInformationHelperInterface';
    }
}
