<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\AdminUserRepositoryInterface;
use App\Repositories\AdminUserRoleRepositoryInterface;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use Illuminate\Support\MessageBag;

class AdminUserController extends Controller
{

    /** @var \App\Repositories\AdminUserRepositoryInterface */
    protected $adminUserRepository;

    /** @var \App\Repositories\AdminUserRoleRepositoryInterface */
    protected $adminUserRoleRepository;

    /** @var \Illuminate\Support\MessageBag */
    protected $messageBag;

    public function __construct(
        AdminUserRepositoryInterface $adminUserRepositoryInterface,
        AdminUserRoleRepositoryInterface $adminUserRoleRepositoryInterface,
        MessageBag $messageBag
    )
    {
        $this->adminUserRepository = $adminUserRepositoryInterface;
        $this->adminUserRoleRepository = $adminUserRoleRepositoryInterface;
        $this->messageBag = $messageBag;
    }

    public function index(PaginationRequest $request)
    {
        $offset = $request->offset();
        $limit = $request->limit();

        $adminUsers = $this->adminUserRepository->get('id', 'desc', $offset, $limit);
        $count = $this->adminUserRepository->count();

        return view('pages.admin.admin-users.index', [
            'adminUsers' => $adminUsers,
            'offset'     => $offset,
            'limit'      => $limit,
            'count'      => $count,
            'baseUrl'    => \URL::action('Admin\AdminUserController@index'),
        ]);
    }

    public function show($id)
    {
        $adminUser = $this->adminUserRepository->find($id);
        if (empty( $adminUser )) {
            abort(404);
        }

        return view('pages.admin.admin-users.edit', [
            'adminUser' => $adminUser,
        ]);
    }

    public function create()
    {

    }

    public function store(AdminUserUpdateRequest $request)
    {

    }

    public function update($id, AdminUserUpdateRequest $request)
    {
        $adminUser = $this->adminUserRepository->find($id);
        if (empty( $adminUser )) {
            abort(404);
        }

        $this->adminUserRepository->update($adminUser, $request->all());
        $this->adminUserRoleRepository->setAdminUserRoles($id, $request->input('role', []));

        return redirect()->action('Admin\AdminUserController@show', [$id])->with('message-success',
            \Lang::get('admin.messages.general.update_success'));
    }
}
