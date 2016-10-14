<?php

namespace App\Models;

/**
 * App\Models\SiteConfiguration.
 *
 * @property int $id
 * @property string $locale
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property int $ogp_image_id
 * @property int $twitter_card_image_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Image $ogpImage
 * @property-read \App\Models\Image $twitterCardImage
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereOgpImageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereTwitterCardImageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteConfiguration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function twitterCardImage()
    {
        return $this->hasOne('App\Models\Image', 'id', 'twitter_card_image_id');
    }
}
