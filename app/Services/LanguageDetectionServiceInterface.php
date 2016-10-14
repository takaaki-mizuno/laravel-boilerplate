<?php

namespace App\Services;

interface LanguageDetectionServiceInterface extends BaseServiceInterface
{
    /**
     * @param string $language
     *
     * @return string
     */
    public function normalize($language);

    /**
     * @param null|string $language
     *
     * @return string
     */
    public function detect($language = null);
}
