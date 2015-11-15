<?php namespace App\Services;

class MetaInformationService
{

    /**
     * @param  string $string
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
     * @param  array $keywords
     * @return string
     */
    public function generateKeywordString($keywords)
    {
        return join(',', $keywords);
    }

}
