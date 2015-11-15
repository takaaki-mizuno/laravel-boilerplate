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

    }
}
