<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MeUpdateRequest;
use App\Http\Requests\PaginationRequest;
use App\Repositories\AdminUserRepositoryInterface;
use App\Services\AdminUserNotificationServiceInterface;
use App\Services\AdminUserServiceInterface;

class MeController extends Controller
{
    /** @var AdminUserServiceInterface $adminUserService */
    protected $adminUserService;

    /** @var AdminUserRepositoryInterface $adminUserRepository */
    protected $adminUserRepository;

    /** @var AdminUserNotificationServiceInterface */
    protected $adminUserNotificationService;

    public function __construct(
        AdminUserServiceInterface $adminUserService,
        AdminUserRepositoryInterface $adminUserRepository,
        AdminUserNotificationServiceInterface $adminUserNotificationService
    ) {
        $this->adminUserService = $adminUserService;
        $this->adminUserRepository = $adminUserRepository;
        $this->adminUserNotificationService = $adminUserNotificationService;
    }

    public function index()
    {
        return view('pages.admin.me.index');
    }

    public function update(MeUpdateRequest $request)
    {
        $adminUser = $this->adminUserService->getUser();

        $password = $request->get('password');

        $update = [
            'name' => $request->get('name', ''),
            'email' => $request->get('email', ''),
        ];

        if (!empty($password)) {
            $update['password'] = $password;
        }

        $this->adminUserRepository->update($adminUser, $update);

        return redirect()->action('Admin\MeController@index')->with('message-success',
            trans('admin.messages.general.update_success'));
    }

    public function notifications(PaginationRequest $request)
    {
        $adminUser = $this->adminUserService->getUser();

        $offset = $request->offset();
        $limit = $request->limit();

        $notifications = $this->adminUserNotificationService->getNotifications($adminUser, $offset, $limit);
        $count = $this->adminUserNotificationService->countNotifications($adminUser);

        if (count($notifications) > 0) {
            $lastNotification = $notifications[0];
            $this->adminUserNotificationService->readUntil($adminUser, $lastNotification);
        }

        return view('pages.admin.me.notifications', [
            'models' => $notifications,
            'offset' => $offset,
            'limit' => $limit,
            'count' => $count,
            'baseUrl' => action('Admin\MeController@notifications'),
        ]);
    }
}
