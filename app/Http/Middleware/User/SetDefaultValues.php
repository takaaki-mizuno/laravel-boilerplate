<?php

namespace App\Http\Middleware\User;

use App\Services\UserServiceInterface;

class SetDefaultValues
{
    /** @var UserServiceInterface */
    protected $userService;

    /**
     * Create a new filter instance.
     *
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

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
        $user = $this->userService->getUser();
        \View::share('authUser', $user);

        return $next($request);
    }
}
