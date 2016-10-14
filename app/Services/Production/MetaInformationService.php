<?php

namespace App\Services\Production;

use App\Services\MetaInformationServiceInterface;

class MetaInformationService extends BaseService implements MetaInformationServiceInterface
{
    /**
     * @param string $string
     *
     * @return array
     */
    public function getKeywordArray($string)
    {
        $keywords = explode(',', $string);
        $result = [];
        foreach ($keywords as $keyword) {
            if (!in_array($keyword, $result)) {
                $result[] = $keyword;
            }
        }

        return $result;
    }

    /**
     * @param array $keywords
     *
     * @return string
     */
    public function generateKeywordString($keywords)
    {
        return implode(',', $keywords);
    }
}
