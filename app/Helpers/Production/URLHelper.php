<?php

namespace App\Helpers\Production;

use App\Helpers\URLHelperInterface;

// http://mio-koduki.blogspot.jp/2012/05/php-httpbuildurl.html
if (!function_exists('http_build_url')) {
    define('HTTP_URL_REPLACE', 1);
    define('HTTP_URL_JOIN_PATH', 2);
    define('HTTP_URL_JOIN_QUERY', 4);
    define('HTTP_URL_STRIP_USER', 8);
    define('HTTP_URL_STRIP_PASS', 16);
    define('HTTP_URL_STRIP_AUTH', 24);
    define('HTTP_URL_STRIP_PORT', 32);
    define('HTTP_URL_STRIP_PATH', 64);
    define('HTTP_URL_STRIP_QUERY', 128);
    define('HTTP_URL_STRIP_FRAGMENT', 256);
    define('HTTP_URL_STRIP_ALL', 504);
    function http_build_url($url, $parts = [], $flags = HTTP_URL_REPLACE, &$new_url = [])
    {
        $key = ['user', 'pass', 'port', 'path', 'query', 'fragment'];

        $new_url = parse_url($url);

        if (isset($parts['scheme'])) {
            $new_url['scheme'] = $parts['scheme'];
        }
        if (isset($parts['host'])) {
            $new_url['host'] = $parts['host'];
        }
        if (array_key_exists('port', $parts)) {
            $new_url['port'] = $parts['port'];
        }

        if ($flags & HTTP_URL_REPLACE) {
            foreach ($key as $v) {
                if (isset($parts[$v])) {
                    $new_url[$v] = $parts[$v];
                }
            }
        } else {
            if (isset($parts['path']) && $flags & HTTP_URL_JOIN_PATH) {
                if (isset($new_url['path'])) {
                    $new_url['path'] = rtrim(preg_replace('#'.preg_quote(basename($new_url['path']), '#').'$#', '',
                            $new_url['path']), '/').'/'.ltrim($parts['path'], '/');
                } else {
                    $new_url['path'] = $parts['path'];
                }
            }

            if (isset($parts['query']) && $flags & HTTP_URL_JOIN_QUERY) {
                if (isset($new_url['query'])) {
                    $new_url['query'] .= '&'.$parts['query'];
                } else {
                    $new_url['query'] = $parts['query'];
                }
            }
        }

        foreach ($key as $v) {
            if ($flags & constant('HTTP_URL_STRIP_'.strtoupper($v))) {
                unset($new_url[$v]);
            }
        }

        return (isset($new_url['scheme']) ? $new_url['scheme'].'://' : '').(isset($new_url['user']) ? $new_url['user'].(isset($new_url['pass']) ? ':'.$new_url['pass'] : '').'@' : '').(isset($new_url['host']) ? $new_url['host'] : '').(isset($new_url['port']) ? ':'.$new_url['port'] : '').(isset($new_url['path']) ? $new_url['path'] : '').(isset($new_url['query']) ? '?'.$new_url['query'] : '').(isset($new_url['fragment']) ? '#'.$new_url['fragment'] : '');
    }
}

class URLHelper implements URLHelperInterface
{
    public function getHostWithLocale($locale = null, $host = null)
    {
        if (empty($host)) {
            $host = config('app.host');
        }
        if (empty($locale)) {
            $locale = config('locale.default');
        }
        if (array_key_exists($locale, config('locale.languages'))) {
            $host = $locale.'.'.$host;
        }

        return $host;
    }

    public function canonicalizeHost($url, $locale = null)
    {
        $host = config('app.host');

        if (empty($host)) {
            return $url;
        }

        $needLocalePrefix = config('app.need_locale_prefix');
        if ($needLocalePrefix) {
            $host = $this->getHostWithLocale($locale, $host);
        }

        return $this->swapHost($url, $host);
    }

    public function swapHost($url, $host)
    {
        return http_build_url($url, ['host' => $host, 'port' => null]);
    }

    public function normalizeUrlPath($urlPath)
    {
        $urlPath = strtolower($urlPath);
        $urlPath = str_replace('_', '-', $urlPath);
        $urlPath = str_replace(' ', '-', $urlPath);

        return $urlPath;
    }

    public function asset($path, $type = 'user')
    {
        $hash = config('asset.hash');
        $url = asset('static/'.$type.'/'.$path);

        if (\App::environment() == 'local' || empty($hash)) {
            return $url;
        }

        return $url.'?'.$hash;
    }

    public function elixir($path, $type = 'user')
    {
        $url = elixir('static/'.$type.'/'.$path);

        return $url;
    }
}
