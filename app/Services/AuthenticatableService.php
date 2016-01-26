<?php namespace App\Services;

use App\Repositories\AuthenticatableRepositoryInterface;
use App\Services\FacebookService;

class AuthenticatableService
{

    /** @var \App\Repositories\AuthenticatableRepositoryInterface */
    protected $authenticatableRepository;

    /** @var \App\Services\FacebookService */
    protected $facebookService;

    public function __construct(
        AuthenticatableRepositoryInterface $authenticatableRepository,
        FacebookService $facebookService
    )
    {
        $this->authenticatableRepository = $authenticatableRepository;
        $this->facebookService = $facebookService;
    }

    /**
     * @param  array $input
     * @return \App\Models\Base
     */
    public function signIn($input)
    {
        $guard = $this->getGuard();
        if (!$guard->attempt(['email' => $input['email'], 'password' => $input['password']], false, true)) {
            \Log::info($input);
            \Log::info('No');

            return null;
        }

        return $guard->user();
    }

    /**
     * @param  array $input
     * @return \App\Models\Base
     */
    public function signUp($input)
    {
        /** @var \App\Models\AuthenticatableBase $user */
        $user = $this->authenticatableRepository->create($input);
        if (empty($user)) {
            return null;
        }
        $guard = $this->getGuard();
        $guard->login($user);

        return $guard->user();
    }

    /**
     * @param  string $token
     * @return \App\Models\Base
     */
    public function signInWithFacebook($token)
    {
        $node = $this->facebookService->getMe($token);
        if (empty($node)) {
            return null;
        }
        $facebookId = $node->getId();
        $email = $node->getEmail();
        if (empty($email)) {
            return null;
        }
        $user = $this->authenticatableRepository->findByFacebookId($facebookId);
        if (empty($user)) {
            $user = $this->authenticatableRepository->findByEmail($email);
        }

        if (empty($user)) {
            $user = $this->authenticatableRepository->create([
                'email'    => $email,
                'password' => '',
            ]);
        }

        $guard = $this->getGuard();
        $guard->login($user);

        return $guard->user();
    }

    /**
     * @param  string $email
     * @return bool
     */
    public function sendPasswordReset($email)
    {

        return false;
    }

    /**
     * @return bool
     */
    public function signOut()
    {
        $user = $this->getUser();
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->logout();
        \Session::flush();

        return true;
    }

    /**
     * @return bool
     */
    public function resignation()
    {
        $user = $this->getUser();
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->logout();
        \Session::flush();
        $this->authenticatableRepository->delete($user);

        return true;
    }

    /**
     * @param \App\Models\AuthenticatableBase $user
     */
    public function setUser($user)
    {
        $guard = $this->getGuard();
        $guard->login($user);
    }

    /**
     * @return \App\Models\AuthenticatableBase
     */
    public function getUser()
    {
        $guard = $this->getGuard();

        return $guard->user();
    }

    /**
     * @return bool
     */
    public function isSignedIn()
    {
        $guard = $this->getGuard();

        return $guard->check();
    }

    protected function getGuardName()
    {
        return "";
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function getGuard()
    {
        return \Auth::guard($this->getGuardName());
    }

}