<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ProxyServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $request = app('request');
        $proxies = app('config')->get('proxy.trusted');
        if ($proxies === '*') {
            $proxies = [ $request->getClientIp() ];
        }
        $request->setTrustedProxies($proxies);
    }

}