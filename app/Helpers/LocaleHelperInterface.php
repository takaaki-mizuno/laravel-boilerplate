<?php namespace App\Helpers;

interface LocaleHelperInterface
{

    /**
     * Set Locale
     *
     * @param  string $locale
     * @param  \App\Models\LocaleStorableBase $user
     * @return string
     */
    public function setLocale($locale = null, $user = null);

}
