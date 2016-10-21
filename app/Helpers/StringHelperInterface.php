<?php

namespace App\Helpers;

interface StringHelperInterface
{
    /**
     * @param string $input
     *
     * @return string
     */
    public function camel2Snake($input);

    /**
     * @param string $input
     *
     * @return string
     */
    public function camel2Spinal($input);

    /**
     * @param string $input
     *
     * @return string
     */
    public function snake2Camel($input);

    /**
     * @param string $singular
     *
     * @return string mixed
     */
    public function pluralize($singular);

    /**
     * @param string $plural
     *
     * @return string
     */
    public function singularize($plural);

    /**
     * @param int $length
     *
     * @return string
     */
    public function randomString($length);

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public function startsWith($haystack, $needle);

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public function endsWith($haystack, $needle);
}
