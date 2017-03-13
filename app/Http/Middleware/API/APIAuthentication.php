<?php

namespace App\Http\Middleware\API;

use App\Repositories\UserRepositoryInterface;

class APIAuthentication
{
    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['HTTP_AUTHORIZATION'];
            $user = $this->userRepository->findByApiAccessToken($token);
            if (!empty($user)) {
                $request['_user'] = $user;
                
                return $next($request);
            } else {
                return response(
                    'Error, Authentication failed !!!',
                    401,
                    ['WWW-Authenticate' => 'Basic realm="RESTRICTED"']
                );
            }
        } else {
            return response(
                'Error, Authentication failed !!!',
                401,
                ['WWW-Authenticate' => 'Basic realm="RESTRICTED"']
            );
        }
    }
}
