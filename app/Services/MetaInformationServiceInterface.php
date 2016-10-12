<?php

namespace App\Services;

interface MetaInformationServiceInterface extends BaseServiceInterface
{
    /**
     * @param string $string
     *
     * @return array
     */
    public function getKeywordArray($string);

    /**
     * @param array $keywords
     *
     * @return string
     */
    public function generateKeywordString($keywords);
}
