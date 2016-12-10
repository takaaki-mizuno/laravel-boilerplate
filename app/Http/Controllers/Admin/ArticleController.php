<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BaseRequest;
use App\Http\Controllers\Controller;
use App\Repositories\ArticleRepositoryInterface;
use App\Http\Requests\Admin\ArticleRequest;
use App\Http\Requests\PaginationRequest;
use App\Repositories\ImageRepositoryInterface;
use App\Services\ArticleServiceInterface;
use App\Services\FileUploadServiceInterface;
use App\Services\ImageServiceInterface;

class ArticleController extends Controller
{
    /** @var \App\Repositories\ArticleRepositoryInterface */
    protected $articleRepository;

    /** @var ArticleServiceInterface $articleService */
    protected $articleService;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    /** @var  ImageServiceInterface $imageService */
    protected $imageService;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        ArticleServiceInterface $articleService,
        FileUploadServiceInterface $fileUploadService,
        ImageRepositoryInterface $imageRepository,
        ImageServiceInterface $imageService
    ) {
        $this->articleRepository = $articleRepository;
        $this->articleService = $articleService;
        $this->fileUploadService = $fileUploadService;
        $this->imageRepository = $imageRepository;
        $this->imageService = $imageService;
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
        $count = $this->articleRepository->count();
        $models = $this->articleRepository->get('id', 'desc', $offset, $limit);

        return view('pages.admin.articles.index', [
            'models' => $models,
            'count' => $count,
            'offset' => $offset,
            'limit' => $limit,
            'baseUrl' => action('Admin\ArticleController@index'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        return view('pages.admin.articles.edit', [
            'isNew' => true,
            'article' => $this->articleRepository->getBlankModel(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Response
     */
    public function store(ArticleRequest $request)
    {
        $input = $request->only([
            'slug',
            'title',
            'keywords',
            'description',
            'content',
            'locale',
            'is_enabled',
            'publish_started_at',
            'publish_ended_at',
        ]);

        $dateTimeColumns = ['publish_started_at', 'publish_ended_at'];
        foreach ($dateTimeColumns as $dateTimeColumn) {
            if (array_key_exists($dateTimeColumn, $input) && !empty($input[ $dateTimeColumn ])) {
                $input[ $dateTimeColumn ] = \DateTimeHelper::convertToStorageDateTime($input[ $dateTimeColumn ]);
            } else {
                $input[ $dateTimeColumn ] = null;
            }
        }

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $model = $this->articleRepository->create($input);

        if (empty($model)) {
            return redirect()->back()->withErrors(trans('admin.errors.general.save_failed'));
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $image = $this->fileUploadService->upload('article-cover-image', $path, $mediaType, [
                'entityType' => 'article',
                'entityId' => $model->id,
                'title' => $request->input('title', ''),
            ]);

            if (!empty($image)) {
                $this->articleRepository->update($model, [
                    'cover_image_id' => $image->id,
                ]);
            }
        }

        $imageIds = $this->articleService->getImageIdsFromSession();
        $images = $this->imageRepository->allByIds($imageIds);
        foreach ($images as $image) {
            $image->entity_type = 'article';
            $image->entity_id = $model->id;
        }
        $this->articleService->resetImageIdSession();

        return redirect()->action('Admin\ArticleController@index')->with('message-success',
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
        $model = $this->articleRepository->find($id);
        if (empty($model)) {
            abort(404);
        }

        return view('pages.admin.articles.edit', [
            'isNew' => false,
            'article' => $model,
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
    public function update($id, ArticleRequest $request)
    {
        /** @var \App\Models\Article $model */
        $model = $this->articleRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $input = $request->only([
            'slug',
            'title',
            'keywords',
            'description',
            'content',
            'locale',
            'is_enabled',
            'publish_started_at',
            'publish_ended_at',
        ]);
        $input['is_enabled'] = $request->get('is_enabled', 0);
        $dateTimeColumns = ['publish_started_at', 'publish_ended_at'];
        foreach ($dateTimeColumns as $dateTimeColumn) {
            if (array_key_exists($dateTimeColumn, $input) && !empty($input[$dateTimeColumn])) {
                $input[$dateTimeColumn] = \DateTimeHelper::convertToStorageDateTime($input[$dateTimeColumn]);
            } else {
                $input[$dateTimeColumn] = null;
            }
        }

        $this->articleRepository->update($model, $input);

        if ($request->hasFile('cover_image')) {
            $image = $model->coverImage;
            if (!empty($image)) {
                $this->fileUploadService->delete($image);
                $this->imageRepository->delete($image);
            }

            $file = $request->file('cover_image');
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $image = $this->fileUploadService->upload('article-cover-image', $path, $mediaType, [
                'entityType' => 'article',
                'entityId' => $model->id,
                'title' => $request->input('title', ''),
            ]);

            if (!empty($image)) {
                $this->articleRepository->update($model, ['cover_image_id' => $image->id]);
            }
        }

        return redirect()->action('Admin\ArticleController@show', [$id])->with('message-success',
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
        $model = $this->articleRepository->find($id);
        if (empty($model)) {
            abort(404);
        }
        $this->articleRepository->delete($model);

        return redirect()->action('Admin\ArticleController@index')->with('message-success',
            trans('admin.messages.general.delete_success'));
    }

    /**
     * @param BaseRequest $request
     *
     * @return \Response
     */
    public function preview(BaseRequest $request)
    {
        $locale = $request->input('language');

        $content = $this->articleService->filterContent($request->input('content'), $locale);
        $title = $request->input('title');
        $response = response()->view('pages.admin.articles.preview', [
            'content' => $content,
            'title' => $title,
        ]);
        //        $response->headers->set('Content-Security-Policy', "default-src 'self' 'unsafe-inline'");
        $response->headers->set('X-XSS-Protection', '0');

        return $response;
    }

    public function getImages(PaginationRequest $request)
    {
        $offset = $request->offset();
        $limit = $request->limit(12);

        $entityId = intval($request->input('article_id', 0));
        $type = $request->input('type', 'article-image');

        if ($entityId == 0) {
            $imageIds = $this->articleService->getImageIdsFromSession();
            $models = $this->imageRepository->allByIds($imageIds);
        } else {
            /** @var \App\Models\Image[] $models */
            $models = $this->imageRepository->allByFileCategoryTypeAndEntityId($type, $entityId);
        }
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'id' => $model->id,
                'url' => $model->url,
                'thumb' => $model->getThumbnailUrl(400, 300),
            ];
        }

        return response()->json($result);
    }

    public function postImage(BaseRequest $request)
    {
        if (!$request->hasFile('file')) {
            // [TODO] ERROR JSON
            abort(400, 'No Image File');
        }

        $type = $request->input('type', 'article-image');
        $entityId = $request->input('article_id', 0);

        $conf = config('file.categories.'.$type);
        if (empty($conf)) {
            abort(400, 'Invalid type: '.$type);
        }

        $file = $request->file('file');
        $mediaType = $file->getClientMimeType();
        $path = $file->getPathname();
        $image = $this->fileUploadService->upload($type, $path, $mediaType, [
            'entityType' => 'article',
            'entityId' => $entityId,
            'title' => $request->input('title', ''),
        ]);

        if ($entityId == 0) {
            $this->articleService->addImageIdToSession($image->id);
        }

        return response()->json([
            'id' => $image->id,
            'link' => $image->getUrl(),
        ]);
    }

    public function deleteImage(BaseRequest $request)
    {
        $url = $request->input('src');
        if (empty($url)) {
            abort(400, 'No URL Given');
        }
        /** @var \App\Models\Image|null $image */
        $image = $this->imageRepository->findByUrl($url);
        if (empty($image)) {
            abort(404);
        }
        $entityId = $request->input('article_id', 0);
        if ($entityId != $image->entity_id) {
            abort(400, 'Article ID Mismatch');
        } else {
            if ($entityId == 0 && !$this->articleService->hasImageIdInSession($image->id)) {
                abort(400, 'Entity ID Mismatch');
            }
        }

        $this->fileUploadService->delete($image);
        $this->imageRepository->delete($image);

        if ($entityId == 0) {
            $this->articleService->removeImageIdFromSession($image->id);
        }

        return response()->json(['status' => 'ok'], 204);
    }
}
