<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserNotificationRepositoryInterface;
use App\Http\Requests\Admin\UserNotificationRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\BaseRequest;

class UserNotificationController extends Controller
{
    /** @var \App\Repositories\UserNotificationRepositoryInterface */
    protected $userNotificationRepository;

    public function __construct(
        UserNotificationRepositoryInterface $userNotificationRepository
    ) {
        $this->userNotificationRepository = $userNotificationRepository;
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
        $count = $this->userNotificationRepository->count();
        $models = $this->userNotificationRepository->get('id', 'desc', $offset, $limit);

        return view('pages.admin.user-notifications.index', [
            'models' => $models,
            'count' => $count,
            'offset' => $offset,
            'limit' => $limit,
            'baseUrl' => action('Admin\UserNotificationController@index'),
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
        $userId = $request->get('user_id');
        $model = $this->userNotificationRepository->getBlankModel();
        if ($userId !== null) {
            $model->user_id = (int) $userId;
        }

        return view('pages.admin.user-notifications.edit', [
            'isNew' => true,
            'userNotification' => $model,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Response
     */
    public function store(UserNotificationRequest $request)
    {
        $input = $request->only(['user_id', 'category_type', 'type', 'locale', 'content']);
        $input['data'] = json_decode($request->get('data', ''));
        $input['sent_at'] = \DateTimeHelper::now();

        $model = $this->userNotificationRepository->create($input);

        if (empty($model)) {
            return redirect()->back()->withErrors(trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\UserNotificationController@index')->with('message-success',
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
        $model = $this->userNotificationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }

        return view('pages.admin.user-notifications.edit', [
            'isNew' => false,
            'userNotification' => $model,
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
    public function update($id, UserNotificationRequest $request)
    {
        /** @var \App\Models\UserNotification $model */
        $model = $this->userNotificationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $input = $request->only(['user_id', 'category_type', 'type', 'locale', 'content']);
        $input['data'] = json_decode($request->get('data', ''));
        $this->userNotificationRepository->update($model, $input);

        return redirect()->action('Admin\UserNotificationController@show', [$id])->with('message-success',
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
        /** @var \App\Models\UserNotification $model */
        $model = $this->userNotificationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $this->userNotificationRepository->delete($model);

        return redirect()->action('Admin\UserNotificationController@index')->with('message-success',
                trans('admin.messages.general.delete_success'));
    }
}
