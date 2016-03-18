<?php namespace App\Http\Middleware\Admin;

use Closure;
use App\Services\AdminUserService;

class Authenticate
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
        if (!$this->adminUserService->isSignedIn()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(\URL::action('Admin\AuthController@getSignIn'));
            }
        }
        view()->share('authUser', $this->adminUserService->getUser());

        return $next($request);
    }

}
