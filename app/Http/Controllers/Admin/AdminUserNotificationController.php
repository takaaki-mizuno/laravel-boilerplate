<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminUserNotificationRepositoryInterface;
use App\Http\Requests\Admin\AdminUserNotificationRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\BaseRequest;

class AdminUserNotificationController extends Controller
{
    /** @var \App\Repositories\AdminUserNotificationRepositoryInterface */
    protected $adminUserNotificationRepository;

    public function __construct(
        AdminUserNotificationRepositoryInterface $adminUserNotificationRepository
    ) {
        $this->adminUserNotificationRepository = $adminUserNotificationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\PaginationRequest $request
     *
     * @return \Response
     */
    public function index(PaginationRequest $request)
    {
        $offset = $request->offset();
        $limit = $request->limit();
        $count = $this->adminUserNotificationRepository->count();
        $models = $this->adminUserNotificationRepository->get('id', 'desc', $offset, $limit);

        return view('pages.admin.admin-user-notifications.index', [
            'models' => $models,
            'count' => $count,
            'offset' => $offset,
            'limit' => $limit,
            'baseUrl' => action('Admin\AdminUserNotificationController@index'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param BaseRequest $request
     *
     * @return \Response
     */
    public function create(BaseRequest $request)
    {
        $userId = $request->get('admin_user_id');
        $model = $this->adminUserNotificationRepository->getBlankModel();
        if ($userId !== null) {
            $model->user_id = (int) $userId;
        }

        return view('pages.admin.admin-user-notifications.edit', [
            'isNew' => true,
            'adminUserNotification' => $model,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Response
     */
    public function store(AdminUserNotificationRequest $request)
    {
        $input = $request->only(['user_id', 'category_type', 'type', 'locale', 'content']);
        $input['data'] = json_decode($request->get('data', ''));
        $input['sent_at'] = \DateTimeHelper::now();

        $model = $this->adminUserNotificationRepository->create($input);

        if (empty($model)) {
            return redirect()->back()->withErrors(trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\AdminUserNotificationController@index')->with('message-success',
            trans('admin.messages.general.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function show($id)
    {
        $model = $this->adminUserNotificationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }

        return view('pages.admin.admin-user-notifications.edit', [
            'isNew' => false,
            'adminUserNotification' => $model,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param     $request
     *
     * @return \Response
     */
    public function update($id, AdminUserNotificationRequest $request)
    {
        /** @var \App\Models\AdminUserNotification $model */
        $model = $this->adminUserNotificationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $input = $request->only(['user_id', 'category_type', 'type', 'locale', 'content']);
        $input['data'] = json_decode($request->get('data', ''));
        $this->adminUserNotificationRepository->update($model, $input);

        return redirect()->action('Admin\AdminUserNotificationController@show', [$id])->with('message-success',
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
        /** @var \App\Models\AdminUserNotification $model */
        $model = $this->adminUserNotificationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $this->adminUserNotificationRepository->delete($model);

        return redirect()->action('Admin\AdminUserNotificationController@index')->with('message-success',
            trans('admin.messages.general.delete_success'));
    }
}
