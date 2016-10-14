<?php

namespace App\Http\Middleware;

class SecurityHeaders
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
        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-UA-Compatible', 'chrome=1');
        if ($response->headers->get('content-type') == 'application/json') {
            $response->headers->set('Content-Security-Policy', 'default-src \'none\'');
        }

        return $response;
    }
}
