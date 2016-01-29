<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Requests\Admin\SignInRequest;
use Laravel\Socialite\Contracts\Factory as Socialite;

class AuthController extends Controller
{

    /** @var \App\Services\UserService UserService */
    protected $userService;

    /** @var Socialite */
    protected $socialite;

    public function __construct(UserService $userService, Socialite $socialite)
    {
        $this->userService = $userService;
        $this->socialite = $socialite;
    }

    public function getSignIn()
    {
        return view('pages.user.auth.signin', [
        ]);
    }

    public function postSignIn(SignInRequest $request)
    {
        $user = $this->userService->signIn($request->all());
        if (empty($user)) {
            return redirect()->action('User\AuthController@getSignIn');
        }

        return redirect()->action('User\IndexController@index');
    }
}
