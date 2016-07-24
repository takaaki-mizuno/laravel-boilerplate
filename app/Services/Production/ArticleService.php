<?php namespace App\Services\Production;

use \App\Services\ArticleServiceInterface;

class ArticleService extends BaseService implements ArticleServiceInterface
{
    public function filterContent($content, $locale = null)
    {
        $locale = empty($locale) ? \App::getLocale() : $locale;
        return $content;
    }
}
