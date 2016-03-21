<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\PasswordController as PasswordControllerBase;
use App\Services\UserService;

class PasswordController extends PasswordControllerBase
{

    /** @var string $emailSetPageView */
    protected $emailSetPageView = 'pages.user.auth.forgot-password';

    /** @var string $passwordResetPageView */
    protected $passwordResetPageView = 'pages.user.auth.reset-password';

    public function __construct(UserService $userService)
    {
        $this->authenticatableService = $userService;
    }

}
