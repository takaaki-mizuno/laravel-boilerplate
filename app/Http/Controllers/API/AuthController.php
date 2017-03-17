<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequest;
use App\Services\UserServiceAuthenticationServiceInterface;
use App\Services\UserServiceInterface;
use App\Repositories\UserRepositoryInterface;

class AuthController extends Controller
{
    /** @var \App\Services\UserServiceInterface */
    protected $userService;

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;

    /** @var \App\Services\ServiceAuthenticationServiceInterface */
    protected $serviceAuthenticationService;

    /** @var \App\Services\AuthenticatableServiceInterface */
    protected $authenticatableService;

    public function __construct(
        UserServiceInterface                    $userService,
        UserRepositoryInterface                 $userRepository,
        UserServiceAuthenticationServiceInterface   $serviceAuthenticationService,
        UserServiceInterface                    $authenticatableService
    )
    {
        $this->userService                  = $userService;
        $this->userRepository               = $userRepository;
        $this->serviceAuthenticationService = $serviceAuthenticationService;
        $this->authenticatableService       = $authenticatableService;
    }

    public function signIn(APIRequest $request)
    {
        $data = $request->all();
        $paramsAllow = [
            'string'  => [
                'email',
                'password'
            ]
        ];
        $paramsRequire = [
            'email',
            'password'
        ];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);
        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        $user = $this->userService->signInByAPI($data);
        if(empty($user)) {
            return $this->response(101);
        }

        return $this->response(100, $user->toAPIArray());
    }

    public function signUp(APIRequest $request)
    {
        $data = $request->all();
        $paramsAllow = [
            'string'  => [
                'name',
                'email',
                'password',
                'telephone',
                'birthday',
                'locale',
                'address'
            ],
            'numeric' => [
                '>=0' => ['gender'],
                '<=1' => ['gender']
            ]
        ];
        $paramsRequire = [
            'name',
            'email',
            'password',
            'gender',
            'telephone',
            'birthday',
        ];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);
        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        if( !empty($this->userRepository->findByEmail($data['email'])) ) {
            return $this->response(110);
        }

        $user = $this->userService->signUpByAPI($data);
        if (empty($user)) {
            return $this->response(901);
        }

        return $this->response(100, $user->toAPIArray());
    }

    public function signOut(APIRequest $request)
    {
        $user = $request->get('_user');
        $this->userRepository->update($user, ['api_access_token' => '']);

        return $this->response(100);
    }

    public function signInBySocial(APIRequest $request, $social)
    {
        switch ($social) {
            case 'facebook':
            case 'google':
                break;
            default:
                return $this->response(107);
        }

        $data = $request->all();
        $paramsAllow = [
            'string'  => [
                'name',
                'email',
                'service_id',
                'telephone',
                'birthday',
                'avatar',
            ],
            'numeric' => [
                '>=0' => ['gender'],
                '<=1' => ['gender']
            ]
        ];
        $paramsRequire = [
            'name',
            'email',
            'service_id'
        ];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);
        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        $authUserId = $this->serviceAuthenticationService->getAuthModelId(
            $social,
            [
                'service'    => $social,
                'service_id' => $data['service_id'],
                'name'       => $data['name'],
                'email'      => $data['email'],
                'avatar'     => $data['avatar'] ? $data['avatar'] : \URLHelper::asset('img/user_avatar.png', 'common'),
                'telephone'  => $data['telephone'] ? $data['telephone'] : null,
                'birthday'   => $data['birthday'] ? $data['birthday'] : null,
            ]
        );

        if (!empty($authUserId)) {
            $user = $this->authenticatableService->signInById($authUserId);
            return $this->response(100, $user->toAPIArray());
        } else {
            return $this->response(101);
        }
    }
}
