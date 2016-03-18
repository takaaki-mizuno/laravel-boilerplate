<?php namespace App\Http\Middleware\Admin;

use Closure;
use App\Services\AdminUserService;
use App\Models\AdminUserRole;

class HasRoleSuperUser
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
    public function handle($request, Closure $next)
    {
        /** @var \App\Models\AdminUser $adminUser */
        $adminUser = $this->adminUserService->getUser();
        if ($adminUser && $adminUser->hasRole(AdminUserRole::ROLE_SUPER_USER)) {
            return $next($request);
        }
        \App::abort(403);
    }

}
