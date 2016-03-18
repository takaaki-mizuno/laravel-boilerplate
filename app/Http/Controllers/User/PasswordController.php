<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;

class PasswordController extends Controller
{

    /** @var \App\Services\UserService UserService */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getForgotPassword()
    {
        return view('pages.user.auth.forgot-password', [
        ]);
    }

    public function postForgotPassword()
    {
        return view('pages.user.auth.forgot-password', [
        ]);
    }

}
