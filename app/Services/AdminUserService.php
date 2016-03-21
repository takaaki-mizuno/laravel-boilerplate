<?php namespace App\Services;

use App\Repositories\AdminUserRepositoryInterface;
use App\Repositories\AdminPasswordResetRepositoryInterface;

class AdminUserService extends AuthenticatableService
{

    /** @var string $resetEmailTitle */
    protected $resetEmailTitle = "Reset Password";

    /** @var string $resetEmailTemplate */
    protected $resetEmailTemplate = "emails.admin.reset_password";

    public function __construct(
        AdminUserRepositoryInterface $adminUserRepository,
        AdminPasswordResetRepositoryInterface $adminPasswordResetRepository
    )
    {
        $this->authenticatableRepository = $adminUserRepository;
        $this->passwordResettableRepository = $adminPasswordResetRepository;
    }

    protected function getGuardName()
    {
        return "admins";
    }

}
