<?php

namespace App\Services;

interface ArticleServiceInterface extends BaseServiceInterface
{
    /**
     * @param string $content
     * @param string $locale
     *
     * @return string
     */
    public function filterContent($content, $locale = null);

    /**
     *
     */
    public function resetImageIdSession();

    /**
     * @param int $imageId
     */
    public function addImageIdToSession($imageId);

    /**
     * @param int $imageId
     */
    public function removeImageIdFromSession($imageId);

    /**
     * @return array
     */
    public function getImageIdsFromSession();

    /**
     * @param int $imageId
     *
     * @return bool
     */
    public function hasImageIdInSession($imageId);
}
