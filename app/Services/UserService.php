<?php namespace App\Services;

use App\Repositories\UserRepositoryInterface;

class UserService extends AuthenticatableService
{

    public function __construct(
        UserRepositoryInterface $userRepository
    )
    {
        $this->authenticatableRepository = $userRepository;
    }

    protected function getGuardName()
    {
        return "users";
    }

}
