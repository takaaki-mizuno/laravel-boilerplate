<?php

namespace App\Repositories;

use Illuminate\Auth\Passwords\TokenRepositoryInterface;

interface PasswordResettableRepositoryInterface extends TokenRepositoryInterface
{
    /**
     * @param $token
     *
     * @return int
     */
    public function findEmailByToken($token);
}
