<?php

namespace App\Models;

/**
 * App\Models\AdminUserNotification.
 *
 * @property int $id
 * @property int $user_id
 * @property string $category_type
 * @property string $type
 * @property string $data
 * @property string $content
 * @property string $locale
 * @property bool $read
 * @property \Carbon\Carbon $sent_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereCategoryType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUserNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property-read \App\Models\AdminUser $adminUser
 */
class AdminUserNotification extends Notification
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_user_notifications';

    protected $presenter = \App\Presenters\AdminUserNotificationPresenter::class;

    public function adminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'user_id', 'id');
    }
}
