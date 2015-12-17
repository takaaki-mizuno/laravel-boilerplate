<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryBindServiceProvider extends ServiceProvider
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

        $this->app->singleton(
            'App\Repositories\AdminUserRepositoryInterface',
            'App\Repositories\Eloquent\AdminUserRepository'
        );

        $this->app->singleton(
            'App\Repositories\AdminUserRoleRepositoryInterface',
            'App\Repositories\Eloquent\AdminUserRoleRepository'
        );

        $this->app->singleton(
            'App\Repositories\UserRepositoryInterface',
            'App\Repositories\Eloquent\UserRepository'
        );

        $this->app->singleton(
            'App\Repositories\FileRepositoryInterface',
            'App\Repositories\Eloquent\FileRepository'
        );

        $this->app->singleton(
            'App\Repositories\ImageRepositoryInterface',
            'App\Repositories\Eloquent\ImageRepository'
        );

        $this->app->singleton(
            'App\Repositories\SiteConfigurationRepositoryInterface',
            'App\Repositories\Eloquent\SiteConfigurationRepository'
        );

    }
}
