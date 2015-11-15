<?php namespace App\Models;

/**
 * App\Models\AuthenticatableBase
 *
 * @property string $password
 */

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class AuthenticatableBase extends LocaleStorableBase implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    function setPassword($password)
    {
        $this->password = \Hash::make($password);
    }
}
