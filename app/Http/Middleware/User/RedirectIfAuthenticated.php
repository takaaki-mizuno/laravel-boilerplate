<?php namespace App\Http\Middleware\User;

use Closure;
use App\Services\UserService;

class RedirectIfAuthenticated
{
    /** @var UserService */
    protected $userService;

    /**
     * Create a new filter instance.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if ($this->userService->isSignedIn()) {
            return redirect()->action('User\IndexController@index');
        }

        return $next($request);
    }
}
