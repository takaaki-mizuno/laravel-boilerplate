<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\File.
 *
 * @property int $id
 * @property string $url
 * @property string $title
 * @property string $entity_type
 * @property int $entity_id
 * @property bool $is_local
 * @property int $file_category_type
 * @property string $s3_key
 * @property string $s3_bucket
 * @property string $s3_region
 * @property string $s3_extension
 * @property string $media_type
 * @property string $format
 * @property int $file_size
 * @property bool $is_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereEntityType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereIsLocal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File whereFileCategoryType($value)
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
        'entity_type',
        'entity_id',
        'file_category_type',
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

    protected $dates = ['deleted_at'];

    protected $presenter = \App\Presenters\FilePresenter::class;

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
