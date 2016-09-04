<?php namespace App\Services;

interface ImageServiceInterface extends BaseServiceInterface
{
    /**
     * @param  string      $src    file path
     * @param  string      $dst    file path
     * @param  string|null $format file format
     * @param  array       $size   [ width, height ]
     * @return array
     */
    public function convert($src, $dst, $format, $size);

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
     * @param  int  $imageId
     * @return bool
     */
    public function hasImageIdInSession($imageId);
}
