<?php namespace App\Services;

interface LanguageDetectionServiceInterface extends BaseServiceInterface
{
    /**
     * @param null|string $language
     * @return string
     */
    public function detect($language = null);
}
