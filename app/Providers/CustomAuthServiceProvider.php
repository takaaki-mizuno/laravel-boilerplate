<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\Auth\UserProvider;
use App\Providers\Auth\AdminUserProvider;
use App\Providers\Auth\Guard;

class CustomAuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Auth::extend('user', function ($app) {
            $provider = new UserProvider($app['hash']);
            return new Guard($provider, $this->app['session.store'], 'user');
        });
        \Auth::extend('admin', function ($app) {
            $provider = new AdminUserProvider($app['hash']);
            return new Guard($provider, $this->app['session.store'], 'admin');
        });
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
    }

}
