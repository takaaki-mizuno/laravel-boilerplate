<?php namespace App\Models;

/**
 * App\Models\Notification
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $category_type
 * @property string $type
 * @property string $data
 * @property string $content
 * @property boolean $read
 * @property \Carbon\Carbon $sent_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereCategoryType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Notification extends Base
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

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
        'content',
        'read',
        'sent_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['sent_at'];

    // Relations

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'category_type' => $this->category_type,
            'type'          => $this->type,
            'data'          => $this->data,
            'content'       => $this->content,
            'read'          => $this->read,
            'sent_at'       => $this->sent_at,
        ];
    }

}
