<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Base
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'url',
        'title',
        'file_category_id',
        's3_key',
        's3_bucket',
        's3_region',
        'media_type',
        'content_type',
        'file_size',
        'is_enabled',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    /*
     * API Presentation
     */

    public function toAPIArray()
    {
        return [
            'id' => $this->id,
        ];
    }

}
