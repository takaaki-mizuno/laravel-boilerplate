<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserPasswordResetRepositoryInterface;

class UserPasswordResetRepository extends PasswordResettableRepository implements UserPasswordResetRepositoryInterface
{
    protected $tableName = 'password_resets';
}
