<?php namespace App\Services;

use App\Repositories\AdminUserRepositoryInterface;

class AdminUserService extends AuthenticatableService
{

    public function __construct(
        AdminUserRepositoryInterface $adminUserRepository
    )
    {
        $this->authenticatableRepository = $adminUserRepository;
    }

    protected function getGuardName()
    {
        return "admins";
    }

}
