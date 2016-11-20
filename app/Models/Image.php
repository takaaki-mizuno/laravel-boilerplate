<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Image.
 *
 * @property int $id
 * @property string $url
 * @property string $title
 * @property string $entity_type
 * @property int $entity_id
 * @property bool $is_local
 * @property string $file_category_type
 * @property string $s3_key
 * @property string $s3_bucket
 * @property string $s3_region
 * @property string $s3_extension
 * @property string $media_type
 * @property string $format
 * @property int $file_size
 * @property int $width
 * @property int $height
 * @property bool $is_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereEntityType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereIsLocal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereFileCategoryType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereS3Key($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereS3Bucket($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereS3Region($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereS3Extension($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereMediaType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereFormat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereFileSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereIsEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Image extends Base
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'title',
        'is_local',
        'entity_type',
        'entity_id',
        'file_category_type',
        's3_key',
        's3_bucket',
        's3_region',
        's3_extension',
        'media_type',
        'format',
        'file_size',
        'width',
        'height',
        'is_enabled',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates = ['deleted_at'];

    protected $presenter = \App\Presenters\ImagePresenter::class;

    // Relations

    // Urility Functions

    /**
     * @return string
     */
    public function getUrl()
    {
        if (config('app.offline_mode', false)) {
            return \URL::to('static/img/local/local.png');
        }

        return !empty($this->url) ? $this->url : 'https://placehold.jp/1440x900.jpg';
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return string
     */
    public function getThumbnailUrl($width, $height)
    {
        if (config('app.offline_mode', false)) {
            return \URL::to('static/img/local/local.png');
        }

        if (empty($this->url)) {
            if ($height == 0) {
                $height = intval($width / 4 * 3);
            }

            return 'https://placehold.jp/'.$width.'x'.$height.'.jpg';
        }

        $categoryType = $this->file_category_type;
        $confList = config('file.categories');

        $conf = array_get($confList, $categoryType);

        if (empty($conf)) {
            return $this->getUrl();
        }

        $size = array_get($conf, 'size');
        if ($width === $size[0] && $height === $size[1]) {
            return $this->getUrl();
        }

        if (preg_match(' /^(.+?)\.([^\.]+)$/', $this->url, $match)) {
            $base = $match[1];
            $ext = $match[2];

            foreach (array_get($conf, 'thumbnails', []) as $thumbnail) {
                if ($width === $thumbnail[0] && $height === $thumbnail[1]) {
                    return $base.'_'.$thumbnail[0].'_'.$thumbnail[1].'.'.$ext;
                }
                if ($thumbnail[1] == 0 && $height == 0 && $width <= $thumbnail[0]) {
                    return $base.'_'.$thumbnail[0].'_'.$thumbnail[1].'.'.$ext;
                }
                if ($thumbnail[1] == 0 && $height != 0 && $size[1] != 0) {
                    if (floor($width / $height * 1000) === floor($size[0] / $size[1] * 1000) && $width <= $thumbnail[0]) {
                        return $base.'_'.$thumbnail[0].'_'.$thumbnail[1].'.'.$ext;
                    }
                }
                if ($thumbnail[1] > 0 && $height > 0) {
                    if (floor($width / $height * 1000) === floor($thumbnail[0] / $thumbnail[1] * 1000) && $width <= $thumbnail[0]) {
                        return $base.'_'.$thumbnail[0].'_'.$thumbnail[1].'.'.$ext;
                    }
                }
            }
        }

        return $this->getUrl();
    }

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'title' => $this->title,
            'file_category' => $this->file_category_type,
            's3_key' => $this->s3_key,
            's3_bucket' => $this->s3_bucket,
            's3_region' => $this->s3_region,
            's3_extension' => $this->s3_extension,
            'media_type' => $this->media_type,
            'format' => $this->format,
            'file_size' => $this->file_size,
            'width' => $this->width,
            'height' => $this->height,
            'is_enabled' => $this->is_enabled,
        ];
    }
}
