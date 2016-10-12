<?php

namespace App\Helpers;

interface MetaInformationHelperInterface
{
    /**
     * @param string|null $keywords
     *
     * @return string
     */
    public function getMetaKeywords($keywords = null);

    /**
     * @param string|null $description
     *
     * @return string
     */
    public function getMetaDescription($description = null);

    /**
     * @param string|null $title
     *
     * @return string
     */
    public function getTitle($title = null);

    /**
     * @param string|null $url
     *
     * @return string
     */
    public function getOGPImage($url = null);

    /**
     * @param string|null $url
     *
     * @return string
     */
    public function getTwitterCardImage($url = null);

    /**
     * @param string|null $url
     *
     * @return string
     */
    public function getUrl($url = null);
}
