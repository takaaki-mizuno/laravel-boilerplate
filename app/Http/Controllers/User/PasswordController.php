<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\PasswordController as PasswordControllerBase;
use App\Http\Requests\ForgotPasswordRequest;
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
    
    public function postForgotPassword(ForgotPasswordRequest $request)
    {
        parent::postForgotPassword($request);
        
        return redirect()->back()->with('status', 'success');
    }
}
