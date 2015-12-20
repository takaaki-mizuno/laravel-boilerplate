<?php namespace App\Http\Middleware\User;

use Closure;
use App\Services\UserService;

class Authenticate
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
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->userService->isSignedIn()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(\URL::action('User\AuthController@getSignIn'));
            }
        }
        view()->share('authUser', $this->userService->getUser());

        return $next($request);
    }

}
