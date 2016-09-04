<?php

namespace App\Http\Middleware;

use Closure;

class SecurePath
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\App::environment('production') && !\Request::secure()) {
            // The environment is production
            return \Redirect::secure(\Request::path());
        }

        return $next($request);
    }
}
