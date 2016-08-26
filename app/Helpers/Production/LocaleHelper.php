<?php namespace App\Helpers\Production;

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
                } else {
                    \Session::put('locale', $locale);
                }
            } else {
                $locale = null;
            }
        }

        if (empty($locale)) {
            if (!empty($user)) {
                $locale = $user->getLocale();
            } else {
                $locale = \Session::get('locale');
            }
        }
        if (empty($locale)) {
            $locale = $this->parseAcceptLanguage();
        }

        return $locale;
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
            foreach (config('locale.languages') as $langcode => $data) {
                if (strpos($lang, $langcode) === 0) {
                    return $langcode;
                }
            }
        }

        return config('locale.default');
    }

}
