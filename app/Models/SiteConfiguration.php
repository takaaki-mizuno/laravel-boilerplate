<?php namespace App\Models;


class SiteConfiguration extends Base
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'site_configurations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'locale',
        'name',
        'title',
        'keywords',
        'description',
        'ogp_image_id',
        'twitter_card_image_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /*
     * API Presentation
     */

    public function toAPIArray()
    {
        return [
            'id' => $this->id,
        ];
    }

    /**
     * @return Image|null
     */
    public function getOGPImage()
    {
        $this->load('ogpImage');
        return $this->ogpImage ? $this->ogpImage : new Image();
    }

    // Relations
    public function ogpImage()
    {
        return $this->hasOne('App\Models\Image', 'id', 'ogp_image_id');
    }

}
