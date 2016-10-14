<?php

namespace App\Repositories;

interface FileRepositoryInterface extends SingleKeyModelRepositoryInterface
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
}
