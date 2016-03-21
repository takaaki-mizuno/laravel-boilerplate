<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\PasswordController as PasswordControllerBase;
use App\Services\AdminUserService;

class PasswordController extends PasswordControllerBase
{

    /** @var string $emailSetPageView */
    protected $emailSetPageView = 'pages.admin.auth.forgot-password';

    /** @var string $passwordResetPageView */
    protected $passwordResetPageView = 'pages.admin.auth.reset-password';

    public function __construct(AdminUserService $adminUserService)
    {
        $this->authenticatableService = $adminUserService;
    }

}
