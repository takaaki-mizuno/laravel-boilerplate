<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Article.
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property int $cover_image_id
 * @property string $locale
 * @property bool $is_enabled
 * @property \Carbon\Carbon $publish_started_at
 * @property \Carbon\Carbon $publish_ended_at
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Image $coverImage
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereCoverImageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereIsEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article wherePublishStartedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article wherePublishEndedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Base
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'articles';

    protected $presenter = \App\Presenters\ArticlePresenter::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'keywords',
        'description',
        'content',
        'cover_image_id',
        'locale',
        'is_enabled',
        'publish_started_at',
        'publish_ended_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates = ['publish_started_at', 'publish_ended_at', 'deleted_at'];

    // Relations
    public function coverImage()
    {
        return $this->hasOne('App\Models\Image', 'id', 'cover_image_id');
    }

    // Utility Functions
    /**
     * @return bool
     */
    public function isEnabled()
    {
        $now = \DateTimeHelper::now();
        if ($this->publish_started_at <= $now && ($this->publish_ended_at == null || $now <= $this->publish_ended_at) && $this->is_enabled) {
            return true;
        }

        return false;
    }

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'keywords' => $this->keywords,
            'description' => $this->description,
            'content' => $this->content,
            'cover_image_id' => $this->cover_image_id,
            'locale' => $this->locale,
            'is_enabled' => $this->is_enabled,
            'publish_started_at' => $this->publish_started_at,
            'publish_ended_at' => $this->publish_ended_at,
        ];
    }
}
