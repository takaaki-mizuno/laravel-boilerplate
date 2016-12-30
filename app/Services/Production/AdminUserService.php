<?php

namespace App\Services\Production;

use App\Repositories\AdminUserRepositoryInterface;
use App\Repositories\AdminPasswordResetRepositoryInterface;
use App\Services\AdminUserServiceInterface;

class AdminUserService extends AuthenticatableService implements AdminUserServiceInterface
{
    /** @var string $resetEmailTitle */
    protected $resetEmailTitle = 'Reset Password';

    /** @var string $resetEmailTemplate */
    protected $resetEmailTemplate = 'emails.admin.reset_password';

    public function __construct(
        AdminUserRepositoryInterface $adminUserRepository,
        AdminPasswordResetRepositoryInterface $adminPasswordResetRepository
    ) {
        $this->authenticatableRepository = $adminUserRepository;
        $this->passwordResettableRepository = $adminPasswordResetRepository;
    }

    public function getGuardName()
    {
        return 'admins';
    }
}
