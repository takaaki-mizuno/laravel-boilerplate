<?php

namespace App\Helpers\Production;

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

    public function snake2Camel($input)
    {
        $string = preg_replace_callback('/(^|_)([a-z])/', function ($match) {
            return strtoupper($match[2]);
        }, $input);

        return lcfirst($string);
    }

    public function pluralize($singular)
    {
        $inflector = Inflector::get('en');

        return $inflector->pluralize($singular);
    }

    public function singularize($plural)
    {
        $inflector = Inflector::get('en');

        return $inflector->singularize($plural);
    }

    public function randomString($length)
    {
        mt_rand();
        $characters = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $result = '';
        for ($i = 0; $i < $length; ++$i) {
            $result .= $characters[ mt_rand(0, count($characters) - 1) ];
        }

        return $result;
    }

    // Ref: http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
    public function startsWith($haystack, $needle)
    {
        return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public function endsWith($haystack, $needle)
    {
        return $needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle,
                $temp) !== false);
    }
}
