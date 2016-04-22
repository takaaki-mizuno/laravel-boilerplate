<?php namespace App\Services;

interface APIServiceInterface extends BaseServiceInterface
{

    /**
     * @param  int $type
     * @return \Response
     */
    public function error($type);

    /**
     * @param  \App\Models\Base[] $models
     * @return array
     */
    public function getAPIArray($models);

}
