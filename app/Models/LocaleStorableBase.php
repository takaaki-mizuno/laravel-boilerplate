<?php

namespace App\Models;

/**
 * App\Models\LocaleStorableBase.
 *
 * @property string $locale
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\LocaleStorableBase whereLocale($value)
 * @mixin \Eloquent
 */
class LocaleStorableBase extends Base
{
    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = strtolower($locale);
        $this->save();
    }
}
