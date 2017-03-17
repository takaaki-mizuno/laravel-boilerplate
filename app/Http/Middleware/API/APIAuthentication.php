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
                return response()->json(['code' => 101, 'message' => config('api.messages.101'), 'data' => null]);
            }
        } else {
            return response()->json(['code' => 101, 'message' => config('api.messages.101'), 'data' => null]);
        }
    }
}
