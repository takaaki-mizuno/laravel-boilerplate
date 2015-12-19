<?php namespace App\Http\Middleware\Admin;

use Closure;
use App\Services\AdminUserService;

class RedirectIfAuthenticated
{
    /** @var AdminUserService */
    protected $adminUserService;

    /**
     * Create a new filter instance.
     *
     * @param  AdminUserService $adminUserService
     */
    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
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
