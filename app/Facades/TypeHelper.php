<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TypeHelper extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\TypeHelperInterface';
    }

}
