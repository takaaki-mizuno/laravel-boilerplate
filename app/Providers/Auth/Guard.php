<?php namespace App\Providers\Auth;

use Illuminate\Auth\Guard as OriginalGuard;
use Illuminate\Contracts\Auth\UserProvider as OriginalUserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Guard extends OriginalGuard
{

    protected $name;

    /**
     * @param OriginalUserProvider $provider
     * @param SessionInterface $session
     * @param string $name
     * @param Request|null $request
     */
    public function __construct(
        OriginalUserProvider $provider,
        SessionInterface $session,
        $name,
        Request $request = null
    )
    {
        parent::__construct($provider, $session, $request);
        $this->name = $name;
    }

    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_' . $this->name . '_' . md5(get_class($this));
    }

    /**
     * Get the name of the cookie used to store the "recaller".
     *
     * @return string
     */
    public function getRecallerName()
    {
        return 'remember_' . $this->name . '_' . md5(get_class($this));
    }

}