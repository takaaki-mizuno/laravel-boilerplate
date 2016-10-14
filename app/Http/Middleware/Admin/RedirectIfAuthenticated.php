<?php

namespace App\Http\Middleware\Admin;

use App\Services\AdminUserServiceInterface;

class RedirectIfAuthenticated
{
    /** @var AdminUserServiceInterface */
    protected $adminUserService;

    /**
     * Create a new filter instance.
     *
     * @param AdminUserServiceInterface $adminUserService
     */
    public function __construct(AdminUserServiceInterface $adminUserService)
    {
        $this->adminUserService = $adminUserService;
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
        if ($this->adminUserService->isSignedIn()) {
            return redirect()->action('Admin\IndexController@index');
        }

        return $next($request);
    }
}
