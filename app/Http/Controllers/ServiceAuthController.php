<?php

namespace App\Http\Controllers;

use App\Services\AuthenticatableServiceInterface;
use App\Services\ServiceAuthenticationServiceInterface;
use Laravel\Socialite\Contracts\Factory as Socialite;

class ServiceAuthController extends Controller
{
    /** @var string */
    protected $driver = '';

    /** @var string */
    protected $redirectAction = '';

    /** @var string */
    protected $errorRedirectAction = '';

    /** @var \App\Services\AuthenticatableServiceInterface */
    protected $authenticatableService;

    /** @var \App\Services\ServiceAuthenticationServiceInterface */
    protected $serviceAuthenticationService;

    /** @var Socialite */
    protected $socialite;

    public function __construct(
        AuthenticatableServiceInterface $authenticatableService,
        ServiceAuthenticationServiceInterface $serviceAuthenticationService,
        Socialite $socialite
    ) {
        $this->authenticatableService = $authenticatableService;
        $this->serviceAuthenticationService = $serviceAuthenticationService;
        $this->socialite = $socialite;
    }

    public function redirect()
    {
        \Config::set("services.$this->driver.redirect", action(config("services.$this->driver.redirect_action")));

        return $this->socialite->driver($this->driver)->redirect();
    }

    public function callback()
    {
        \Config::set("services.$this->driver.redirect", action(config("services.$this->driver.redirect_action")));

        try {
            $serviceUser = $this->socialite->driver($this->driver)->user();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return redirect()->action($this->errorRedirectAction)->withErrors([trans('sign_in_failed_title'), trans('social_sign_in_failed')]);
        }

        $serviceUserId = $serviceUser->getId();
        $name = $serviceUser->getName();
        $email = $serviceUser->getEmail();
        $avatar = $serviceUser->getAvatar();

        if (empty($email)) {
            return redirect()->action($this->errorRedirectAction)->withErrors([trans('sign_in_failed_title'), trans('failed_to_get_email')]);
        }

        $authUserId = $this->serviceAuthenticationService->getAuthModelId($this->driver, [
            'service' => $this->driver,
            'service_id' => $serviceUserId,
            'name' => $name,
            'email' => $email,
            'avatar' => $avatar,
        ]);

        if (!empty($authUserId)) {
            $this->authenticatableService->signInById($authUserId);
        }

        return redirect()->intended(action($this->redirectAction));
    }
}
