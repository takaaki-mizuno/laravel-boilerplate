<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdminUserRole extends Base
{

    const ROLE_SUPER_USER = 'super_user';
    const ROLE_CHIEF_EDITOR = 'chief_editor';
    const ROLE_EDITOR = 'editor';
    const ROLE_SITE_ADMIN = 'site_admin';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_user_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id',
        'role',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden     = [];

    protected $primaryKey = ['admin_user_id', 'role'];

    protected $dates      = ['publish_started_at', 'publish_ended_at', 'deleted_at'];

    /*
     * API Presentation
     */

    public function toAPIArray()
    {
        return [
            'id' => $this->admin_user_id,
        ];
    }

    public function adminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'id', 'admin_user_id');
    }

    public function getRoleName()
    {
        return \Lang::get(\Config::get('admin_user.roles.' . $this->role . '.name'));
    }

}
