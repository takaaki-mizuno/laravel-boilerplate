<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AdminPasswordResetRepositoryInterface;

class AdminPasswordResetRepository extends PasswordResettableRepository implements AdminPasswordResetRepositoryInterface
{
    protected $tableName = 'admin_password_resets';
}
