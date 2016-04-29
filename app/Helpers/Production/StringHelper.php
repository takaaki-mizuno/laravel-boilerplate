<?php namespace App\Helpers\Production;

use App\Helpers\StringHelperInterface;
use ICanBoogie\Inflector;

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

    public function camel2Spinal($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('-', $ret);
    }

    public function pluralize($singular)
    {
        $inflector = Inflector::get('en');

        return $inflector->pluralize($singular);
    }

}
