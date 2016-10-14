<?php

namespace App\Services\Production;

use App\Services\LanguageDetectionServiceInterface;

class LanguageDetectionService extends BaseService implements LanguageDetectionServiceInterface
{
    public function normalize($language)
    {
        $language = strtolower($language);
        if (!array_key_exists($language, config('locale.languages'))) {
            $language = config('locale.default');
        }

        return $language;
    }

    public function detect($language = null)
    {
        if (isset($language)) {
            $language = strtolower($language);
            if (in_array($language, array_keys(config('locale.languages')))) {
                \Session::put('locale', $language);
            } else {
                $locale = null;
            }
        }
        if (empty($locale)) {
            $locale = \Session::get('locale');
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
                arsort($languages, SORT_NUMERIC);
            }
        }
        foreach ($languages as $lang => $val) {
            foreach (array_keys(config('locale.languages')) as $langcode) {
                if (strpos($lang, $langcode) === 0) {
                    return $langcode;
                }
            }
        }

        return config('locale.default');
    }
}
