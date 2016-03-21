<?php namespace App\Repositories;

use Illuminate\Auth\Passwords\TokenRepositoryInterface;

interface PasswordResettableRepositoryInterface extends TokenRepositoryInterface
{
    /**
     * @param $token
     * @return integer
     */
    public function findEmailByToken($token);

}
