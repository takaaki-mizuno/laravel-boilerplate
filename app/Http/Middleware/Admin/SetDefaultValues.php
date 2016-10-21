<?php

namespace App\Http\Middleware\Admin;

use App\Services\AdminUserNotificationServiceInterface;
use App\Services\AdminUserServiceInterface;

class SetDefaultValues
{
    /** @var AdminUserServiceInterface */
    protected $adminUserService;

    /** @var AdminUserNotificationServiceInterface */
    protected $adminUserNotificationService;

    /**
     * Create a new filter instance.
     *
     * @param AdminUserServiceInterface             $adminUserService
     * @param AdminUserNotificationServiceInterface $adminUserNotificationService
     */
    public function __construct(
        AdminUserServiceInterface $adminUserService,
        AdminUserNotificationServiceInterface $adminUserNotificationService
    ) {
        $this->adminUserService = $adminUserService;
        $this->adminUserNotificationService = $adminUserNotificationService;
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
        $user = $this->adminUserService->getUser();
        \View::share('authUser', $user);
        \View::share('menu', '');

        if (!empty($user)) {
            $notificationCount = $this->adminUserNotificationService->getUnreadNotificationCount($user);
            $notifications = $this->adminUserNotificationService->getNotifications($user, 0, 10);
        } else {
            $notificationCount = 0;
            $notifications = 0;
        }

        \View::share('unreadNotificationCount', $notificationCount);
        \View::share('notifications', $notifications);

        return $next($request);
    }
}
