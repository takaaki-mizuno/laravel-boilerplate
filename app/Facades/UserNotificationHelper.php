<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserNotificationHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Helpers\UserNotificationHelperInterface';
    }
}
