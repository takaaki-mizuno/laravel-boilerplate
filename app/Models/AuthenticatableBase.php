<?php namespace App\Models;

/**
 * App\Models\AuthenticatableBase
 *
 * @property string $password
 * @property string $api_access_token
 *
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

    public function setAPIAccessToken()
    {
        $user = null;
        do {
            $code = md5(\Hash::make($this->id . $this->email . $this->password . time() . mt_rand()));
            $user = static::whereApiAccessToken($code)->first();
        } while (isset($user));
        $this->api_access_token = $code;
        return $code;
    }
}
