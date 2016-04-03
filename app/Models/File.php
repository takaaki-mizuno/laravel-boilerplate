<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\FilePresenter;

/**
 * App\Models\File
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property boolean $is_local
 * @property integer $file_category
 * @property integer $file_subcategory
 * @property string $s3_key
 * @property string $s3_bucket
 * @property string $s3_region
 * @property string $s3_extension
 * @property string $media_type
 * @property string $format
 * @property integer $file_size
 * @property boolean $is_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereIsLocal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereFileCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereFileSubcategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereS3Key($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereS3Bucket($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereS3Region($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereS3Extension($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereMediaType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereFormat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereFileSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereIsEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereDeletedAt($value)
 * @mixin \Eloquent
 */
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
        'file_category',
        'file_subcategory',
        's3_key',
        's3_bucket',
        's3_region',
        's3_extension',
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

    public function getPresenterClass()
    {
        return FilePresenter::class;
    }

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
