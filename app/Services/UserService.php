<?php namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserPasswordResetRepositoryInterface;

class UserService extends AuthenticatableService
{

    /** @var string $resetEmailTitle */
    protected $resetEmailTitle = "Reset Password";

    /** @var string $resetEmailTemplate */
    protected $resetEmailTemplate = "emails.user.reset_password";

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserPasswordResetRepositoryInterface $userPasswordResetRepository
    )
    {
        $this->authenticatableRepository = $userRepository;
        $this->passwordResettableRepository = $userPasswordResetRepository;
    }

    protected function getGuardName()
    {
        return "users";
    }

}
