<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\SecurityHeaders::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.basic'                => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'admin.auth'                => \App\Http\Middleware\Admin\Authenticate::class,
        'admin.guest'               => \App\Http\Middleware\Admin\RedirectIfAuthenticated::class,
        'admin.has_role.super_user' => \App\Http\Middleware\Admin\HasRoleSuperUser::class,
        'user.auth'                 => \App\Http\Middleware\User\Authenticate::class,
        'user.guest'                => \App\Http\Middleware\User\RedirectIfAuthenticated::class,
    ];
}
