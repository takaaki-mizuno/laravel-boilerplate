<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Image
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property integer $file_category
 * @property integer $file_subcategory
 * @property string $s3_key
 * @property string $s3_bucket
 * @property string $s3_region
 * @property string $s3_extension
 * @property string $media_type
 * @property string $format
 * @property integer $file_size
 * @property integer $width
 * @property integer $height
 * @property boolean $has_alternative
 * @property string $alternative_media_type
 * @property string $alternative_format
 * @property string $alternative_extension
 * @property boolean $is_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereFileCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereFileSubcategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereS3Key($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereS3Bucket($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereS3Region($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereS3Extension($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereMediaType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereFormat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereFileSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereHasAlternative($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereAlternativeMediaType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereAlternativeFormat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereAlternativeExtension($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereIsEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereDeletedAt($value)
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
        'format',
        'content_type',
        'file_size',
        'width',
        'height',
        'has_alternative',
        'alternative_media_type',
        'alternative_format',
        'alternative_extension',
        'is_enabled',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    /**
     * @return string
     */
    public function getUrl()
    {

        if (\Config::get('app.offline_mode', false)) {
            return \URL::to('static/img/local/local.png');
        }

        return !empty($this->url) ? $this->url : 'https://placehold.jp/1440x900.jpg';
    }

    /**
     * @param  int $width
     * @param  int $height
     * @return string
     */
    public function getThumbnailUrl($width, $height)
    {

        if (\Config::get('app.offline_mode', false)) {
            return \URL::to('static/img/local/local.png');
        }

        if (empty($this->url)) {
            if ($height == 0) {
                $height = intval($width / 4 * 3);
            }

            return 'https://placehold.jp/' . $width . 'x' . $height . '.jpg';
        }

        $categoryType = $this->file_category;
        $confList = \Config::get('file.categories');

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
                    return $base . '_' . $thumbnail[0] . '_' . $thumbnail[1] . '.' . $ext;
                }
                if ($thumbnail[1] == 0 && $height == 0 && $width <= $thumbnail[0]) {
                    return $base . '_' . $thumbnail[0] . '_' . $thumbnail[1] . '.' . $ext;
                }
                if ($thumbnail[1] == 0 && $height != 0 && $size[1] != 0) {
                    if (floor($width / $height * 1000) === floor($size[0] / $size[1] * 1000) && $width <= $thumbnail[0]) {
                        return $base . '_' . $thumbnail[0] . '_' . $thumbnail[1] . '.' . $ext;
                    }
                }
                if ($thumbnail[1] > 0 && $height > 0) {
                    if (floor($width / $height * 1000) === floor($thumbnail[0] / $thumbnail[1] * 1000)
                        && $width <= $thumbnail[0]
                    ) {
                        return $base . '_' . $thumbnail[0] . '_' . $thumbnail[1] . '.' . $ext;
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
        ];
    }

}
