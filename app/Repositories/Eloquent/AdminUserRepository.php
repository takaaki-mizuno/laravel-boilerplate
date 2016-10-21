<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AdminUserRepositoryInterface;
use App\Models\AdminUser;

class AdminUserRepository extends AuthenticatableRepository implements AdminUserRepositoryInterface
{
    public function getBlankModel()
    {
        return new AdminUser();
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
