<?php

namespace App\Http\Controllers\Admin;

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
    ) {
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
            'offset' => $offset,
            'limit' => $limit,
            'count' => $count,
            'baseUrl' => action('Admin\AdminUserController@index'),
        ]);
    }

    public function show($id)
    {
        $adminUser = $this->adminUserRepository->find($id);
        if (empty($adminUser)) {
            abort(404);
        }

        return view('pages.admin.admin-users.edit', [
            'isNew' => false,
            'adminUser' => $adminUser,
        ]);
    }

    public function create()
    {
        return view('pages.admin.admin-users.edit', [
            'isNew' => true,
            'adminUser' => $this->adminUserRepository->getBlankModel(),
        ]);
    }

    public function store(AdminUserUpdateRequest $request)
    {
       $input = $request->only([
            'name',
            'email',
            'password',
        ]);

        $exist = $this->adminUserRepository->findByEmail($input['email']);
        if ( !empty($exist) ) {
            return redirect()->back()->withErrors(['error'=> 'This Email Is Already In Use'])->withInput();
        }

        $adminUser = $this->adminUserRepository->create($input);
        $this->adminUserRoleRepository->setAdminUserRoles($adminUser->id, $request->input('role', []));

        return redirect()->action('Admin\AdminUserController@show', [$adminUser->id])->with('message-success',
            trans('admin.messages.general.create_success'));
    }

    public function update($id, AdminUserUpdateRequest $request)
    {
        $adminUser = $this->adminUserRepository->find($id);
        if (empty($adminUser)) {
            abort(404);
        }

        $input = $request->only([
            'name',
            'email',
        ]);
        $password = $request->get('password', '');
        if ( !empty($password) ) {
            $input['password'] = $password;
        }

        $this->adminUserRepository->update($adminUser, $request->all());
        $this->adminUserRoleRepository->setAdminUserRoles($id, $request->input('role', []));

        return redirect()->action('Admin\AdminUserController@show', [$id])->with('message-success',
            trans('admin.messages.general.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function destroy($id)
    {
        /** @var \App\Models\Article $model */
        $model = $this->adminUserRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $this->adminUserRepository->delete($model);

        return redirect()->action('Admin\AdminUserController@index')->with('message-success',
            trans('admin.messages.general.delete_success'));
    }
}
