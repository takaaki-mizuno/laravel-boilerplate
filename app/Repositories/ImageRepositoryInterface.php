<?php

namespace App\Repositories;

interface ImageRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Get Models with Order.
     *
     * @param string $fileCategoryType
     * @param string $order
     * @param string $direction
     * @param int    $offset
     * @param int    $limit
     *
     * @return \App\Models\Image[]|\Traversable|array
     */
    public function getByFileCategoryType($fileCategoryType, $order, $direction, $offset, $limit);

    /**
     * @param string $url
     *
     * @return \App\Models\Image|null
     */
    public function findByUrl($url);

    /**
     * @param string $type
     * @param int    $entityId
     *
     * @return \App\Models\Image[]|\Traversable|array
     */
    public function allByEntityTypeAndEntityId($type, $entityId);
}
