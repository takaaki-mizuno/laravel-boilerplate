<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperBindServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /* Helpers */
        $this->app->singleton(
            'App\Helpers\DateTimeHelperInterface',
            'App\Helpers\Production\DateTimeHelper'
        );

        $this->app->singleton(
            'App\Helpers\LocaleHelperInterface',
            'App\Helpers\Production\LocaleHelper'
        );

        $this->app->singleton(
            'App\Helpers\MetaInformationHelperInterface',
            'App\Helpers\Production\MetaInformationHelper'
        );

        $this->app->singleton(
            'App\Helpers\URLHelperInterface',
            'App\Helpers\Production\URLHelper'
        );

    }
}
