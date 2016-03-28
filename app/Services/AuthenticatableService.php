<?php namespace App\Services;

use App\Repositories\AuthenticatableRepositoryInterface;
use App\Repositories\PasswordResettableRepositoryInterface;

class AuthenticatableService
{

    /** @var \App\Repositories\AuthenticatableRepositoryInterface */
    protected $authenticatableRepository;

    /** @var  \App\Repositories\PasswordResettableRepositoryInterface */
    protected $passwordResettableRepository;

    /** @var string $resetEmailTitle */
    protected $resetEmailTitle = "Reset Password";

    /** @var string $resetEmailTemplate */
    protected $resetEmailTemplate = "";

    public function __construct(
        AuthenticatableRepositoryInterface $authenticatableRepository,
        PasswordResettableRepositoryInterface $passwordResettableRepository
    )
    {
        $this->authenticatableRepository = $authenticatableRepository;
        $this->passwordResettableRepository = $passwordResettableRepository;
    }

    public function signInById($id)
    {
        /** @var \App\Models\AuthenticatableBase $user */
        $user = $this->authenticatableRepository->find($id);
        if (empty( $user )) {
            return null;
        }
        $guard = $this->getGuard();
        $guard->login($user);

        return $guard->user();
    }

    /**
     * @param  array            $input
     * @return \App\Models\Base
     */
    public function signIn($input)
    {
        $rememberMe = !!array_get($input, 'remember_me', 0);
        $guard = $this->getGuard();
        if (!$guard->attempt(['email' => $input['email'], 'password' => $input['password']], $rememberMe, true)) {
            return null;
        }

        return $guard->user();
    }

    /**
     * @param  array            $input
     * @return \App\Models\Base
     */
    public function signUp($input)
    {
        /** @var \App\Models\AuthenticatableBase $user */
        $user = $this->authenticatableRepository->create($input);
        if (empty( $user )) {
            return null;
        }
        $guard = $this->getGuard();
        $guard->login($user);

        return $guard->user();
    }

    /**
     * @param  string           $token
     * @return \App\Models\Base
     */
    public function signInWithFacebook($token)
    {
        $facebookService = new FacebookService();
        $node = $facebookService->getMe($token);
        if (empty( $node )) {
            return null;
        }
        $facebookId = $node->getId();
        $email = $node->getEmail();
        if (empty( $email )) {
            return null;
        }
        $user = $this->authenticatableRepository->findByFacebookId($facebookId);
        if (empty( $user )) {
            $user = $this->authenticatableRepository->findByEmail($email);
        }

        if (empty( $user )) {
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
        if (empty( $user )) {
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
        if (empty( $user )) {
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
     * @param string $email
     */
    public function sendPasswordResetEmail($email)
    {

        $user = $this->authenticatableRepository->findByEmail($email);
        if (empty( $user )) {
            return;
        }

        $token = $this->passwordResettableRepository->create($user);

        /** @var \App\Services\MailService $mailService */
        $mailService = \App::make('App\Services\MailService');

        $mailService->sendMail($this->resetEmailTitle, \Config::get('mail.from'),
            ['name' => '', 'address' => $user->email], $this->resetEmailTemplate, [
                'token' => $token,
            ]);
    }

    /**
     * @param  string                               $token
     * @return null|\App\Models\AuthenticatableBase
     */
    public function getUserByPasswordResetToken($token)
    {
        $email = $this->passwordResettableRepository->findEmailByToken($token);
        if (empty( $email )) {
            return null;
        }

        return $this->authenticatableRepository->findByEmail($email);
    }

    /**
     * @param  string $email
     * @param  string $password
     * @param  string $token
     * @return bool
     */
    public function resetPassword($email, $password, $token)
    {
        $user = $this->authenticatableRepository->findByEmail($email);
        if (empty( $user )) {
            return false;
        }
        if (!$this->passwordResettableRepository->exists($user, $token)) {
            return false;
        }
        $this->authenticatableRepository->update($user, ['password' => $password]);
        $this->passwordResettableRepository->delete($token);
        $this->setUser($user);

        return true;
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
