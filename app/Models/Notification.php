<?php

namespace App\Models;

use Illuminate\Support\Arr;

/**
 * App\Models\Notification.
 *
 * This is Abstract Class for Notifications.
 * No table named 'notifications' exists
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
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereCategoryType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Notification extends Base
{
    const BROADCAST_USER_ID = 0;

    const CATEGORY_TYPE_SYSTEM_MESSAGE = 'system';

    const TYPE_GENERAL_MESSAGE = 'general_message';
    const TYPE_GENERAL_ALERT = 'general_alert';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_type',
        'type',
        'data',
        'locale',
        'content',
        'read',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates = ['sent_at'];

    // Relations

    // Utility Functions
    public function getData($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                Arr::set($this->data, $innerKey, $innerValue);
            }
        } else {
            Arr::set($this->data, $key, $value);
        }
    }

    public function isBroadcast()
    {
        return  $this->user_id == static::BROADCAST_USER_ID;
    }

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category_type' => $this->category_type,
            'type' => $this->type,
            'data' => $this->data,
            'content' => $this->content,
            'read' => $this->read,
            'sent_at' => $this->sent_at,
        ];
    }
}
