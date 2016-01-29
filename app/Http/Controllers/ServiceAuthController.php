<?php namespace App\Http\Controllers;

use App\Services\AuthenticatableService;
use App\Services\ServiceAuthenticationService;

use Laravel\Socialite\Contracts\Factory as Socialite;

class ServiceAuthController extends Controller
{

    /** @var string */
    protected $driver = '';

    /** @var string */
    protected $redirectAction = '';

    /** @var \App\Services\AuthenticatableService */
    protected $authenticatableService;

    /** @var \App\Services\ServiceAuthenticationService */
    protected $serviceAuthenticationService;

    /** @var Socialite */
    protected $socialite;

    public function __construct(
        AuthenticatableService $authenticatableService,
        ServiceAuthenticationService $serviceAuthenticationService,
        Socialite $socialite
    )
    {
        $this->authenticatableService = $authenticatableService;
        $this->serviceAuthenticationService = $serviceAuthenticationService;
        $this->socialite = $socialite;
    }

    public function redirect()
    {
        \Config::set("services.$this->driver.redirect", action(\Config::get("services.$this->driver.redirect_action")));

        return $this->socialite->driver($this->driver)->redirect();
    }

    public function callback()
    {
        \Config::set("services.$this->driver.redirect", action(\Config::get("services.$this->driver.redirect_action")));

        $serviceUser = $this->socialite->driver($this->driver)->user();

        $serviceUserId = $serviceUser->getId();
        $name = $serviceUser->getName();
        $email = $serviceUser->getEmail();

        $authUserId = $this->serviceAuthenticationService->getAuthModelId($this->driver, [
            'service'    => $this->driver,
            'service_id' => $serviceUserId,
            'name'       => $name,
            'email'      => $email,
        ]);

        if (!empty($authUserId)) {
            $this->authenticatableService->signInById($authUserId);
        }

        return redirect()->action($this->redirectAction);
    }
}
