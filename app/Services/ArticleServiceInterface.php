<?php namespace App\Services;

interface ArticleServiceInterface extends BaseServiceInterface
{

    /**
     * @param  string $content
     * @param  string $locale
     * @return string
     */
    public function filterContent($content, $locale = null);

}