<?php namespace App\Services;

interface FileUploadServiceInterface extends BaseServiceInterface
{

    /**
     * @param  string                                  $categoryType
     * @param  string                                  $categorySubType
     * @param  string                                  $text
     * @param  string                                  $mediaType
     * @param  array                                   $metaInputs
     * @return \App\Models\Image|\App\Models\File|null
     */
    public function uploadFromText($categoryType, $categorySubType, $text, $mediaType, $metaInputs);

    /**
     * @param  int                                     $categoryType
     * @param  int                                     $categorySubType
     * @param  string                                  $path
     * @param  string                                  $mediaType
     * @param  array                                   $metaInputs
     * @return \App\Models\Image|\App\Models\File|null
     */
    public function upload($categoryType, $categorySubType, $path, $mediaType, $metaInputs);

    /**
     * @param  \App\Models\Image|\App\Models\File $model
     * @return bool|null
     */
    public function delete($model);
}
