<?php

namespace App\Models;

/**
 * App\Models\AdminUserRole.
 *
 * @property int $id
 * @property int $admin_user_id
 * @property string $role
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\AdminUser $adminUser
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserRole whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserRole whereAdminUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserRole whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserRole whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
    protected $hidden = [];

    protected $dates = ['publish_started_at', 'publish_ended_at', 'deleted_at'];

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
        return trans(config('admin_user.roles.'.$this->role.'.name'));
    }
}
