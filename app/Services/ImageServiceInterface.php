<?php

namespace App\Services;

interface ImageServiceInterface extends BaseServiceInterface
{
    /**
     * @param string      $src    file path
     * @param string      $dst    file path
     * @param string|null $format file format
     * @param array       $size   [ width, height ]
     *
     * @return array
     */
    public function convert($src, $dst, $format, $size);
}
