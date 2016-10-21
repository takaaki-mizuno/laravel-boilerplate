<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class StringHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\StringHelperInterface';
    }
}
