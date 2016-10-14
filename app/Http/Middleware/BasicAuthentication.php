<?php

namespace App\Http\Middleware;

class BasicAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $needAuthentication = config('app.basic_authentication', true);
        if ($needAuthentication) {
            if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
                return response('Please enter username and password', 401,
                    ['WWW-Authenticate' => 'Basic realm="RESTRICTED"']);
            }
            if ($_SERVER['PHP_AUTH_USER'] != 'test' && $_SERVER['PHP_AUTH_PW'] != 'abcdef') {
                return response('Please enter username and password', 401,
                    ['WWW-Authenticate' => 'Basic realm="RESTRICTED"']);
            }
        }

        return $next($request);
    }
}
