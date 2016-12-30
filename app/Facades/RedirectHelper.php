<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RedirectHelper extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\RedirectHelperInterface';
    }

}
