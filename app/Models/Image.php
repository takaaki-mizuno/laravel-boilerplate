<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

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
        'file_category',
        'file_subcategory',
        's3_key',
        's3_bucket',
        's3_region',
        's3_extension',
        'media_type',
        'format',
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

    // Relations

    // Urility Functions
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
                    if (floor($width / $height * 1000) === floor($thumbnail[0] / $thumbnail[1] * 1000) && $width <= $thumbnail[0]) {
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
            'id'                     => $this->id,
            'url'                    => $this->url,
            'title'                  => $this->title,
            'file_category'          => $this->file_category,
            'file_subcategory'       => $this->file_subcategory,
            's3_key'                 => $this->s3_key,
            's3_bucket'              => $this->s3_bucket,
            's3_region'              => $this->s3_region,
            's3_extension'           => $this->s3_extension,
            'media_type'             => $this->media_type,
            'format'                 => $this->format,
            'file_size'              => $this->file_size,
            'width'                  => $this->width,
            'height'                 => $this->height,
            'has_alternative'        => $this->has_alternative,
            'alternative_media_type' => $this->alternative_media_type,
            'alternative_format'     => $this->alternative_format,
            'alternative_extension'  => $this->alternative_extension,
            'is_enabled'             => $this->is_enabled,
        ];
    }

}
