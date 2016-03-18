<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminUserService;

class PasswordController extends Controller
{

    /** @var \App\Services\AdminUserService AdminUserService */
    protected $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    public function getForgotPassword()
    {
        return view('pages.admin.auth.forgot_password', [
        ]);
    }

    public function postForgotPassword()
    {
        return view('pages.admin.auth.forgot_password', [
        ]);
    }
}
