<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $facebook_id
 * @property string $facebook_token
 * @property string $api_access_token
 * @property integer $profile_image_id
 * @property \Carbon\Carbon $deleted_at
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereFacebookId( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereFacebookToken( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereApiAccessToken( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereProfileImageId( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDeletedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt( $value )
 * @property-read \App\Models\Image $profileImage
 * @mixin \Eloquent
 */
class User extends AuthenticatableBase
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'facebook_id',
        'facebook_token',
        'remember_token',
        'api_access_token',
        'profile_image_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'facebook_token'];

    protected $dates  = ['deleted_at'];

    /*
     * API Presentation
     */

    public function toAPIArray()
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
