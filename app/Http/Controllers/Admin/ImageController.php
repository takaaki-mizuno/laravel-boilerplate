<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepositoryInterface;
use App\Http\Requests\Admin\ImageRequest;
use App\Http\Requests\PaginationRequest;
use App\Services\FileUploadServiceInterface;

class ImageController extends Controller
{
    /** @var \App\Repositories\ImageRepositoryInterface */
    protected $imageRepository;

    protected $fileUploadService;

    public function __construct(
        ImageRepositoryInterface $imageRepository,
        FileUploadServiceInterface $fileUploadService
    )
    {
        $this->imageRepository = $imageRepository;
        $this->fileUploadService = $fileUploadService;
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
        $count = $this->imageRepository->count();
        $models = $this->imageRepository->get('id', 'desc', $offset, $limit);

        return view('pages.admin.images.index', [
            'models'  => $models,
            'count'   => $count,
            'offset'  => $offset,
            'limit'   => $limit,
            'baseUrl' => action('Admin\ImageController@index'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        return view('pages.admin.images.edit', [
            'isNew' => true,
            'image' => $this->imageRepository->getBlankModel(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Response
     */
    public function store(ImageRequest $request)
    {
        $input = $request->only([
            'url',
            'title',
            'entity_type',
            'entity_id',
            'file_category_type',
            's3_key',
            's3_bucket',
            's3_region',
            's3_extension',
            'media_type',
            'format',
            'file_size',
            'width',
            'height',
        ]);
        $input['is_enabled'] = $request->get('is_enabled', 0);
        foreach (['s3_key', 's3_bucket', 's3_region'] as $key) {
            if (empty($input[$key])) {
                $input[$key] = "";
            }
        }

        $model = $this->imageRepository->create($input);

        if (empty($model)) {
            return redirect()->back()->withErrors(trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\ImageController@index')->with('message-success',
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
        $model = $this->imageRepository->find($id);
        if (empty($model)) {
            abort(404);
        }

        return view('pages.admin.images.edit', [
            'isNew' => false,
            'image' => $model,
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
    public function update($id, ImageRequest $request)
    {
        /** @var \App\Models\Image $model */
        $model = $this->imageRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $input = $request->only([
            'url',
            'title',
            'entity_type',
            'entity_id',
            'file_category_type',
            's3_key',
            's3_bucket',
            's3_region',
            's3_extension',
            'media_type',
            'format',
            'file_size',
            'width',
            'height',
        ]);
        $input['is_enabled'] = $request->get('is_enabled', 0);
        foreach (['s3_key', 's3_bucket', 's3_region'] as $key) {
            if (empty($input[$key])) {
                $input[$key] = "";
            }
        }
        $this->imageRepository->update($model, $input);

        return redirect()->action('Admin\ImageController@show', [$id])->with('message-success',
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
        /** @var \App\Models\Image $model */
        $model = $this->imageRepository->find($id);
        if (empty($model)) {
            abort(404);
        }

        $this->fileUploadService->delete($model);
        $this->imageRepository->delete($model);

        return redirect()->action('Admin\ImageController@index')->with('message-success',
            trans('admin.messages.general.delete_success'));
    }
}
