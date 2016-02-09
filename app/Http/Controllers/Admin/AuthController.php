<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminUserService;
use App\Http\Requests\Admin\SignInRequest;

class AuthController extends Controller
{

    /** @var \App\Services\AdminUserService AdminUserService */
    protected $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    public function getSignIn()
    {
        return view('pages.admin.auth.signin', [
        ]);
    }

    public function postSignIn(SignInRequest $request)
    {
        $adminUser = $this->adminUserService->signIn($request->all());
        if (empty($adminUser)) {
            return redirect()->action('Admin\AuthController@getSignIn');
        }

        return redirect()->action('Admin\IndexController@index');
    }

}
