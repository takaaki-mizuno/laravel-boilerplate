<?php

namespace App\Helpers;

interface URLHelperInterface
{
    /**
     * @param string $url
     * @param string $host
     *
     * @return string
     */
    public function swapHost($url, $host);

    /**
     * @param string $url
     * @param string $locale
     *
     * @return string
     */
    public function canonicalizeHost($url, $locale = null);

    /**
     * @param string $urlPath
     *
     * @return string
     */
    public function normalizeUrlPath($urlPath);

    /**
     * @param string|null $locale
     * @param string|null $host
     *
     * @return string
     */
    public function getHostWithLocale($locale = null, $host = null);

    /**
     * @param string $path
     * @param string $type
     *
     * @return string
     */
    public function asset($path, $type = 'user');

    /**
     * @param string $path
     * @param string $type
     *
     * @return string
     */
    public function elixir($path, $type = 'user');
}
