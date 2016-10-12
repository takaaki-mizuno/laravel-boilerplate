<?php

namespace App\Services;

interface APIServiceInterface extends BaseServiceInterface
{
    /**
     * @param string $type
     *
     * @return \Response
     */
    public function error($type);

    /**
     * @param array  $models
     * @param string $key
     * @param int    $offset
     * @param int    $limit
     * @param int    $count
     * @param int    $statusCode
     *
     * @return \Response
     */
    public function listResponse($models, $key, $offset, $limit, $count, $statusCode = 200);

    /**
     * @param \App\Models\Base[] $models
     *
     * @return array
     */
    public function getAPIArray($models);

    /**
     * @param array  $models
     * @param string $key
     * @param int    $offset
     * @param int    $limit
     * @param int    $count
     *
     * @return array
     */
    public function getAPIListObject($models, $key, $offset, $limit, $count);
}
