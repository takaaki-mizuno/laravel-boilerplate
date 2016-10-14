<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class URLHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\URLHelperInterface';
    }
}
