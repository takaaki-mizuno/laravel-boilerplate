<?php

namespace App\Services\Production;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserPasswordResetRepositoryInterface;
use App\Services\UserServiceInterface;

class UserService extends AuthenticatableService implements UserServiceInterface
{
    /** @var string $resetEmailTitle */
    protected $resetEmailTitle = 'Reset Password';

    /** @var string $resetEmailTemplate */
    protected $resetEmailTemplate = 'emails.user.reset_password';

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserPasswordResetRepositoryInterface $userPasswordResetRepository
    ) {
        $this->authenticatableRepository = $userRepository;
        $this->passwordResettableRepository = $userPasswordResetRepository;
    }

    public function getGuardName()
    {
        return 'web';
    }
}
