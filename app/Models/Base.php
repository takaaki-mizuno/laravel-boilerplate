<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use App\Presenters\BasePresenter;

/**
 * App\Models\Base
 *
 */
class Base extends Model implements HasPresenter
{

    /**
     * @return string
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function getEditableColumns()
    {
        return $this->fillable;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getLocalizedColumn($key)
    {
        $locale = \App::getLocale();
        if (empty($locale)) {
            $locale = 'en';
        }
        $localizedKey = $key . '_' . strtolower($locale);
        $value = $this->$localizedKey;
        if (empty($value)) {
            $localizedKey = $key . '_en';
            $value = $this->$localizedKey;
        }
        return $value;
    }

    /**
     * @return array
     */
    public function toAPIArray()
    {
        return [];
    }

    public function getPresenterClass()
    {
        return BasePresenter::class;
    }
}
