<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DateTimeHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\DateTimeHelperInterface';
    }
}
