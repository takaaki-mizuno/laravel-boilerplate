<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperBindServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        /* Helpers */
        $this->app->singleton('App\Helpers\DateTimeHelperInterface', 'App\Helpers\Production\DateTimeHelper');

        $this->app->singleton('App\Helpers\LocaleHelperInterface', 'App\Helpers\Production\LocaleHelper');

        $this->app->singleton('App\Helpers\MetaInformationHelperInterface',
            'App\Helpers\Production\MetaInformationHelper');

        $this->app->singleton('App\Helpers\URLHelperInterface', 'App\Helpers\Production\URLHelper');

        $this->app->singleton('App\Helpers\CollectionHelperInterface', 'App\Helpers\Production\CollectionHelper');

        $this->app->singleton('App\Helpers\StringHelperInterface', 'App\Helpers\Production\StringHelper');

        $this->app->singleton(
            'App\Helpers\PaginationHelperInterface',
            'App\Helpers\Production\PaginationHelper'
        );

        $this->app->singleton(
            'App\Helpers\UserNotificationHelperInterface',
            'App\Helpers\Production\UserNotificationHelper'
        );

        $this->app->singleton(
            'App\Helpers\TypeHelperInterface',
            'App\Helpers\Production\TypeHelper'
        );

        /* NEW BINDING */
    }
}
