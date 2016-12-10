<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\SiteConfigurationRepositoryInterface;
use App\Http\Requests\Admin\SiteConfigurationRequest;
use App\Http\Requests\PaginationRequest;
use App\Services\FileUploadServiceInterface;

class SiteConfigurationController extends Controller
{
    /** @var \App\Repositories\SiteConfigurationRepositoryInterface */
    protected $siteConfigurationRepository;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    public function __construct(
        SiteConfigurationRepositoryInterface $siteConfigurationRepository,
        FileUploadServiceInterface $fileUploadService,
        ImageRepositoryInterface $imageRepository
    ) {
        $this->siteConfigurationRepository = $siteConfigurationRepository;
        $this->fileUploadService = $fileUploadService;
        $this->imageRepository = $imageRepository;
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
        $count = $this->siteConfigurationRepository->count();
        $models = $this->siteConfigurationRepository->get('id', 'desc', $offset, $limit);

        return view('pages.admin.site-configurations.index', [
            'models' => $models,
            'count' => $count,
            'offset' => $offset,
            'limit' => $limit,
            'baseUrl' => action('Admin\SiteConfigurationController@index'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        return view('pages.admin.site-configurations.edit', [
            'isNew' => true,
            'siteConfiguration' => $this->siteConfigurationRepository->getBlankModel(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Response
     */
    public function store(SiteConfigurationRequest $request)
    {
        $input = $request->only([
            'locale',
            'name',
            'title',
            'keywords',
            'description',
        ]);
        $model = $this->siteConfigurationRepository->create($input);

        if (empty($model)) {
            return redirect()->back()->withErrors(trans('admin.errors.general.save_failed'));
        }

        if ($request->hasFile('ogp_image')) {
            $file = $request->file('ogp_image');
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $image = $this->fileUploadService->upload('ogp-image', $path, $mediaType, []);
            $this->siteConfigurationRepository->update($model, ['ogp_image_id' => $image->id]);
        }

        if ($request->hasFile('twitter_card_image')) {
            $file = $request->file('twitter_card_image');
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $image = $this->fileUploadService->upload('twitter-card-image', $path, $mediaType, []);
            $this->siteConfigurationRepository->update($model, ['twitter_card_image_id' => $image->id]);
        }

        return redirect()->action('Admin\SiteConfigurationController@index')->with('message-success',
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
        $model = $this->siteConfigurationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }

        return view('pages.admin.site-configurations.edit', [
            'isNew' => false,
            'siteConfiguration' => $model,
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
    public function update($id, SiteConfigurationRequest $request)
    {
        /** @var \App\Models\SiteConfiguration $model */
        $model = $this->siteConfigurationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $input = $request->only([
            'id',
            'locale',
            'name',
            'title',
            'keywords',
            'description',
            'ogp_image_id',
            'twitter_card_image_id',
        ]);
        $this->siteConfigurationRepository->update($model, $input);

        if ($request->hasFile('ogp_image')) {
            $image = $model->ogpImage;
            if (!empty($image)) {
                $this->fileUploadService->delete($image);
                $this->imageRepository->delete($image);
            }

            $file = $request->file('ogp_image');
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $image = $this->fileUploadService->upload('ogp-image', $path, $mediaType, []);
            $this->siteConfigurationRepository->update($model, ['ogp_image_id' => $image->id]);
        }

        if ($request->hasFile('twitter_card_image')) {
            $image = $model->ogpImage;
            if (!empty($image)) {
                $this->fileUploadService->delete($image);
                $this->imageRepository->delete($image);
            }

            $file = $request->file('twitter_card_image');
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $image = $this->fileUploadService->upload('twitter-card-image', $path, $mediaType, []);
            $this->siteConfigurationRepository->update($model, ['twitter_card_image_id' => $image->id]);
        }

        return redirect()->action('Admin\SiteConfigurationController@show', [$id])->with('message-success',
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
        /** @var \App\Models\SiteConfiguration $model */
        $model = $this->siteConfigurationRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $this->siteConfigurationRepository->delete($model);

        return redirect()->action('Admin\SiteConfigurationController@index')->with('message-success',
            trans('admin.messages.general.delete_success'));
    }
}
