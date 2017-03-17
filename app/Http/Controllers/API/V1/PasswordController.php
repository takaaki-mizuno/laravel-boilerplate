<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\PasswordController as PasswordControllerBase;
use App\Http\Requests\APIRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Services\UserServiceInterface;
use App\Repositories\UserRepositoryInterface;

class PasswordController extends PasswordControllerBase
{
    /** @var  \App\Repositories\UserRepositoryInterface $userRepository */
    protected $userRepository;

    public function __construct(
        UserServiceInterface    $userService,
        UserRepositoryInterface $userRepository
    )
    {
        $this->authenticatableService   = $userService;
        $this->userRepository           = $userRepository;
    }

    public function forgotPassword(APIRequest $request)
    {
        $data = $request->all();
        $paramsAllow = [
            'string'  => [
                'email'
            ]
        ];
        $paramsRequire = [
            'email'
        ];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);
        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        $user = $this->userRepository->findByEmail( $data['email'] );
        if (empty($user)) {
            return $this->response(104);
        }

        $forgotPasswordRequest = new ForgotPasswordRequest();
        $forgotPasswordRequest['email'] = $data['email'];
        if( $this->postForgotPassword( $forgotPasswordRequest ) ) {
            return $this->response(100);
        }

        return $this->response(904);
    }
}
