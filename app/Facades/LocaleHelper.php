<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LocaleHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\LocaleHelperInterface';
    }
}
