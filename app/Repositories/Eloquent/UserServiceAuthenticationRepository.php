<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserServiceAuthenticationRepositoryInterface;
use App\Models\UserServiceAuthentication;

class UserServiceAuthenticationRepository extends ServiceAuthenticationRepository implements UserServiceAuthenticationRepositoryInterface
{
    public $authModelColumn = 'user_id';

    public function getBlankModel()
    {
        return new UserServiceAuthentication();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
