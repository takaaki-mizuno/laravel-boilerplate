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
        $this->app->singleton(\App\Helpers\DateTimeHelperInterface::class, \App\Helpers\Production\DateTimeHelper::class);

        $this->app->singleton(\App\Helpers\LocaleHelperInterface::class, \App\Helpers\Production\LocaleHelper::class);

        $this->app->singleton(\App\Helpers\MetaInformationHelperInterface::class,
            \App\Helpers\Production\MetaInformationHelper::class);

        $this->app->singleton(\App\Helpers\URLHelperInterface::class, \App\Helpers\Production\URLHelper::class);

        $this->app->singleton(\App\Helpers\CollectionHelperInterface::class, \App\Helpers\Production\CollectionHelper::class);

        $this->app->singleton(\App\Helpers\StringHelperInterface::class, \App\Helpers\Production\StringHelper::class);

        $this->app->singleton(
            \App\Helpers\PaginationHelperInterface::class,
            \App\Helpers\Production\PaginationHelper::class
        );

        $this->app->singleton(
            \App\Helpers\UserNotificationHelperInterface::class,
            \App\Helpers\Production\UserNotificationHelper::class
        );

        $this->app->singleton(
            \App\Helpers\TypeHelperInterface::class,
            \App\Helpers\Production\TypeHelper::class
        );

        $this->app->singleton(
            \App\Helpers\RedirectHelperInterface::class,
            \App\Helpers\Production\RedirectHelper::class
        );

        /* NEW BINDING */
    }
}
