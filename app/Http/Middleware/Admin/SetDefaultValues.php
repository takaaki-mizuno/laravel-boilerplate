<?php

namespace App\Http\Middleware\Admin;

use Closure;
use App\Services\AdminUserService;

class SetDefaultValues
{
    /** @var AdminUserService */
    protected $adminUserService;

    /**
     * Create a new filter instance.
     *
     * @param AdminUserService $adminUserService
     */
    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
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
        $user = $this->adminUserService->getUser();
        \View::share('authUser', $user);

        return $next($request);
    }
}
