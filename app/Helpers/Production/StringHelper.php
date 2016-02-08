<?php namespace App\Helpers\Production;

use App\Helpers\StringHelperInterface;

class StringHelper implements StringHelperInterface
{

    // Ref: http://stackoverflow.com/questions/1993721/how-to-convert-camelcase-to-camel-case
    public function camel2Snake($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }

    public function pluralize($singular)
    {
        $last_letter = strtolower($singular[ strlen($singular) - 1 ]);
        switch ($last_letter) {
            case 'y':
                return substr($singular, 0, -1) . 'ies';
            case 's':
                return $singular . 'es';
            default:
                return $singular . 's';
        }
    }
}
