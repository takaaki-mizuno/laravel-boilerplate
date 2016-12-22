<?php

namespace App\Helpers\Production;

use App\Helpers\LocaleHelperInterface;

class LocaleHelper implements LocaleHelperInterface
{
    public function setLocale($locale = null, $user = null)
    {
        if (isset($locale)) {
            $locale = strtolower($locale);
            if (array_key_exists($locale, config('locale.languages'))) {
                if (!empty($user)) {
                    $user->setLocale($locale);
                }
                \Session::put('locale', $locale);
            } else {
                $locale = null;
            }
        }

        if (empty($locale)) {
            if (!empty($user)) {
                $locale = $user->getLocale();
            }
            if (empty($locale)) {
                $locale = \Session::get('locale');
            }
        }
        if (empty($locale)) {
            $locale = $this->parseAcceptLanguage();
        }

        return $locale;
    }

    public function getLocale()
    {
        $pieces = explode('.', \Request::getHost());
        $locale = null;
        $availableDomains = config('locale.domains', []);

        if (in_array(strtolower($pieces[0]), $availableDomains)) {
            $locale = strtolower($pieces[0]);
        }

        if (empty($locale)) {
            $locale = \LocaleHelper::setLocale();
        }

        if (\Request::has('fb_locale')) {
            $fbLocale = \Request::get('fb_locale');
            $languages = array_filter(config('locale.languages'), function ($language) use ($fbLocale) {
                if (array_get($language, 'ogp') === $fbLocale) {
                    return true;
                }

                return false;
            });

            if ($languages) {
                reset($languages);
                $locale = key($languages);
            }
        }

        return $locale;
    }

    public function getLocaleSubDomain()
    {
        $pieces = explode('.', \Request::getHost());
        $locale = null;
        $availableDomains = config('locale.domains', []);

        if (in_array(strtolower($pieces[0]), $availableDomains)) {
            $locale = strtolower($pieces[0]);
        }

        return $locale;
    }

    public function getEnableLocales()
    {
        return array_where(config('locale.languages'), function ($value, $key) {
            return $value['status'] == true;
        });
    }

    private function parseAcceptLanguage()
    {
        $languages = [];
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
                $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
            if (count($lang_parse[1])) {
                $languages = array_combine($lang_parse[1], $lang_parse[4]);
                foreach ($languages as $lang => $val) {
                    if ($val === '') {
                        $languages[ $lang ] = 1;
                    }
                }
                //                arsort($languages, SORT_NUMERIC);
            }
        }
        foreach ($languages as $lang => $val) {
            foreach (config('locale.languages') as $langCode => $data) {
                if (strpos(strtolower($lang), $langCode) === 0) {
                    return $langCode;
                }
            }
        }

        return config('locale.default');
    }
}
