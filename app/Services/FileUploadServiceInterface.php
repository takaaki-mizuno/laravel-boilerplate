<?php namespace App\Services;

interface FileUploadServiceInterface extends BaseServiceInterface
{

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
