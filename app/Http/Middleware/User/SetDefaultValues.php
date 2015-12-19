<?php namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Services\UserService;

class SetDefaultValues
{
    /** @var UserService */
    protected $userService;

    /**
     * Create a new filter instance.
     *
     * @param  UserService $userService
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
        $user = $this->userService->getUser();
        \View::share('authUser', $user);
        return $next($request);
    }
}
