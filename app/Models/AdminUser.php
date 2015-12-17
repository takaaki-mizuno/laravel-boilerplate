<?php namespace App\Models;

class AdminUser extends AuthenticatableBase
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_users';

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

    // Relation

    public function profileImage()
    {
        return $this->belongsTo('App\Models\Image', 'profile_image_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany('App\Models\AdminUserRole', 'admin_user_id', 'id');
    }

    // Urility Functions

    /**
     * @return string
     */
    public function getProfileImageUrl()
    {
        $profileImage = $this->profileImage;
        if (empty($profileImage)) {
            return url('static/img/default-user-image.jpg');
        }
    }

    /**
     * @param  string $targetRole
     * @param  bool $checkSubRoles
     * @return bool
     */
    public function hasRole($targetRole, $checkSubRoles = true)
    {
        $roles = $this->roles->lists('role')->toArray();
        if (in_array($targetRole, $roles)) {
            return true;
        }
        if (!$checkSubRoles) {
            return false;
        }
        $roleConfigs = \Config::get('admin_user.roles', []);
        foreach ($roles as $role) {
            $subRoles = array_get($roleConfigs, "$role.sub_roles", []);
            if (in_array($targetRole, $subRoles)) {
                return true;
            }
        }
        return false;
    }

}
