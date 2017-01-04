<?php

namespace App\Models;

/*
 * App\Models\AuthenticatableBase
 *
 * @property string $password
 * @property int $profile_image_id
 * @property string $api_access_token
 * @property-read \App\Models\Image $profileImage
 * @mixin \Eloquent
 */

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class AuthenticatableBase extends LocaleStorableBase implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    public function setPasswordAttribute($password)
    {
        if (empty($password)) {
            $this->attributes['password'] = '';
        } else {
            $this->attributes['password'] = \Hash::make($password);
        }
    }

    public function setAPIAccessToken()
    {
        $user = null;
        do {
            $code = md5(\Hash::make($this->id.$this->email.$this->password.time().mt_rand()));
            $user = static::whereApiAccessToken($code)->first();
        } while (isset($user));
        $this->api_access_token = $code;

        return $code;
    }

    // Relation

    public function profileImage()
    {
        return $this->belongsTo('App\Models\Image', 'profile_image_id', 'id');
    }

    public function getProfileImageUrl($width = 0, $height = 0)
    {
        if ($this->profile_image_id == 0) {
            return \URLHelper::asset('img/user.png', 'common');
        }
        if ($width == 0 && $height == 0) {
            return $this->profileImage->url;
        } else {
            return $this->profileImage->url;
        }
    }
}
