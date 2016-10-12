<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CollectionHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\CollectionHelperInterface';
    }
}
