<?php namespace App\Services;

use App\Repositories\AuthenticatableRepositoryInterface;

class AuthenticatableService
{

    /** @var \App\Repositories\AuthenticatableRepositoryInterface */
    protected $authenticatableRepository;

    public function __construct(
        AuthenticatableRepositoryInterface $authenticatableRepository
    )
    {
        $this->authenticatableRepository = $authenticatableRepository;
    }

    protected function getAuthDriverName()
    {
        return "";
    }

    protected function getAuthDriver()
    {
        return \Auth::driver($this->getAuthDriverName());
    }

    /**
     * @param  $input
     * @return \App\Models\Base
     */
    public function signIn($input)
    {
        $authDriver = $this->getAuthDriver();
        if (!$authDriver->attempt(['email' => $input['email'], 'password' => $input['password']])) {
            \Log::info($input);
            \Log::info('No');
            return null;
        }
        return $authDriver->user();
    }

    /**
     * @param  $input
     * @return \App\Models\Base
     */
    public function signUp($input)
    {
        $user = $this->authenticatableRepository->create($input);
        if (empty($user)) {
            return null;
        }
        $authDriver = $this->getAuthDriver();
        $authDriver->login($user);
        return $authDriver->user();
    }

    /**
     * @param $email
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
        $authDriver = $this->getAuthDriver();
        $authDriver->logout();
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
        $authDriver = $this->getAuthDriver();
        $authDriver->logout();
        \Session::flush();
        $this->authenticatableRepository->delete($user);
        return true;
    }

    /**
     * @param \App\Models\AuthenticatableBase $user
     */
    public function setUser($user)
    {
        $authDriver = $this->getAuthDriver();
        $authDriver->login($user);
    }

    /**
     * @return \App\Models\AuthenticatableBase
     */
    public function getUser()
    {
        $authDriver = $this->getAuthDriver();
        return $authDriver->user();
    }

    /**
     * @return bool
     */
    public function isSignedIn()
    {
        $authDriver = $this->getAuthDriver();
        return $authDriver->check();
    }

}