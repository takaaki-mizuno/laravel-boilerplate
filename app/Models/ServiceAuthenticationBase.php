<?php

namespace App\Models;

/**
 * App\Models\ServiceAuthenticationBase.
 *
 * @mixin \Eloquent
 */
class ServiceAuthenticationBase extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'service',
        'service_id',
        'service_token',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates = [];
}
