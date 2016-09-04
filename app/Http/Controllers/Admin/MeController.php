<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MeUpdateRequest;
use App\Repositories\AdminUserRepositoryInterface;
use App\Services\AdminUserServiceInterface;

class MeController extends Controller
{
    /** @var AdminUserServiceInterface $adminUserService */
    protected $adminUserService;

    /** @var AdminUserRepositoryInterface $adminUserRepository */
    protected $adminUserRepository;

    public function __construct(
        AdminUserServiceInterface $adminUserService,
        AdminUserRepositoryInterface $adminUserRepository
    )
    {
        $this->adminUserService = $adminUserService;
        $this->adminUserRepository = $adminUserRepository;
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

        if( !empty($password) ) {
            $update['password'] = $password;
        }

        $this->adminUserRepository->update($adminUser, $update);

        return redirect()->action('Admin\MeController@index')->with('message-success',
            trans('admin.messages.general.update_success'));
    }
}
