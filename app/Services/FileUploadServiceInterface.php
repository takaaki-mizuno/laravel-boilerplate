<?php

namespace App\Services;

interface FileUploadServiceInterface extends BaseServiceInterface
{
    /**
     * @param string $categoryType
     * @param string $text
     * @param string $mediaType
     * @param array  $metaInputs
     *
     * @return \App\Models\Image|\App\Models\File|null
     */
    public function uploadFromText($categoryType, $text, $mediaType, $metaInputs);

    /**
     * @param int    $categoryType
     * @param string $path
     * @param string $mediaType
     * @param array  $metaInputs
     *
     * @return \App\Models\Image|\App\Models\File|null
     */
    public function upload($categoryType, $path, $mediaType, $metaInputs);

    /**
     * @param \App\Models\Image|\App\Models\File $model
     *
     * @return bool|null
     */
    public function delete($model);
}
