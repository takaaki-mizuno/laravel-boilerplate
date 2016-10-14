<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\PasswordController as PasswordControllerBase;
use App\Services\UserServiceInterface;

class PasswordController extends PasswordControllerBase
{
    /** @var string $emailSetPageView */
    protected $emailSetPageView = 'pages.user.auth.forgot-password';

    /** @var string $passwordResetPageView */
    protected $passwordResetPageView = 'pages.user.auth.reset-password';

    /** @var string $returnAction */
    protected $returnAction = 'User\IndexController@index';

    public function __construct(UserServiceInterface $userService)
    {
        $this->authenticatableService = $userService;
    }
}
