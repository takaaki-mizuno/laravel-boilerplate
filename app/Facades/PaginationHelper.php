<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PaginationHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\PaginationHelperInterface';
    }
}
