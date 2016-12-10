<?php

namespace App\Http\Middleware\Admin;

use Closure;
use App\Services\AdminUserServiceInterface;
use App\Models\AdminUserRole;

class HasRoleSiteAdmin
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
    public function handle($request, Closure $next)
    {
        /** @var \App\Models\AdminUser $adminUser */
        $adminUser = $this->adminUserService->getUser();
        if ($adminUser && $adminUser->hasRole(AdminUserRole::ROLE_SITE_ADMIN)) {
            return $next($request);
        }
        abort(403);
    }
}
