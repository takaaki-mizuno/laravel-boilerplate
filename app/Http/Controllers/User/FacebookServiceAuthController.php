<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ServiceAuthController;
use App\Services\UserServiceInterface;
use App\Services\UserServiceAuthenticationServiceInterface;
use Laravel\Socialite\Contracts\Factory as Socialite;

class FacebookServiceAuthController extends ServiceAuthController
{
    protected $driver = 'facebook';

    protected $redirectAction = 'User\IndexController@index';

    protected $errorRedirectAction = 'User\AuthController@getSignUp';

    public function __construct(
        UserServiceInterface $authenticatableService,
        UserServiceAuthenticationServiceInterface $serviceAuthenticationService,
        Socialite $socialite
    ) {
        $this->authenticatableService = $authenticatableService;
        $this->serviceAuthenticationService = $serviceAuthenticationService;
        $this->socialite = $socialite;
    }
}
