<?php

namespace App\Repositories;

interface RelationModelRepositoryInterface extends SingleKeyModelRepositoryInterface
{

    /**
     * @return array
     */
    public function getRelationKeys();

    /**
     * @return string
     */
    public function getParentKey();

    /**
     * @return string
     */
    public function getChildKey();

    /**
     * @param int $parentKey
     * @param int $childKey

     * @return \App\Models\Base|null
     */
    public function findByRelationKeys($parentKey, $childKey);

    /**
     * @param int $parentKey
     *
     * @return \App\Models\Base[]
     */
    public function allByParentKey($parentKey);

    /**
     * @param int   $parentKey
     * @param array $childKeys
     *
     * @return \App\Models\Base[]
     */
    public function updateList( $parentKey, $childKeys );

}
