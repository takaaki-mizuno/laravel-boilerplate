<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\BaseRequest;
use App\Http\Controllers\Controller;

use App\Repositories\ArticleRepositoryInterface;
use App\Http\Requests\Admin\ArticleRequest;
use App\Http\Requests\PaginationRequest;
use App\Repositories\ImageRepositoryInterface;
use App\Services\ArticleServiceInterface;
use App\Services\FileUploadServiceInterface;

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

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        ArticleServiceInterface $articleService,
        FileUploadServiceInterface $fileUploadService,
        ImageRepositoryInterface $imageRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->articleService = $articleService;
        $this->fileUploadService = $fileUploadService;
        $this->imageRepository = $imageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\PaginationRequest $request
     * @return \Response
     */
    public function index(PaginationRequest $request)
    {
        $offset = $request->offset();
        $limit = $request->limit();
        $count = $this->articleRepository->count();
        $models = $this->articleRepository->get('id', 'desc', $offset, $limit);

        return view('pages.admin.articles.index', [
            'models'  => $models,
            'count'   => $count,
            'offset'  => $offset,
            'limit'   => $limit,
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
            'isNew'   => true,
            'article' => $this->articleRepository->getBlankModel(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Response
     */
    public function store(ArticleRequest $request)
    {
        $input = $request->only(['slug', 'title', 'keywords', 'description', 'content', 'locale', 'is_enabled']);

        $dateTimeColumns = ['publish_started_at', 'publish_ended_at'];
        foreach ($dateTimeColumns as $dateTimeColumn) {
            if (array_key_exists($dateTimeColumn, $input) and !empty($input[ $dateTimeColumn ])) {
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
                'entityId'   => $model->id,
                'title'      => $request->input('title', ''),
            ]);

            $this->articleRepository->update($model, [
                'cover_image_id' => $image->id,
            ]);
        }

        return redirect()->action('Admin\ArticleController@index')->with('message-success',
            trans('admin.messages.general.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Response
     */
    public function show($id)
    {
        $model = $this->articleRepository->find($id);
        if (empty($model)) {
            \App::abort(404);
        }

        return view('pages.admin.articles.edit', [
            'isNew'   => false,
            'article' => $model,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param      $request
     * @return \Response
     */
    public function update($id, ArticleRequest $request)
    {
        /** @var \App\Models\Article $model */
        $model = $this->articleRepository->find($id);
        if (empty($model)) {
            \App::abort(404);
        }
        $input = $request->only([
            'slug',
            'title',
            'keywords',
            'description',
            'content',
            'locale',
            'is_enabled',
        ]);
        $input['is_enabled'] = $request->get('is_enabled', 0);
        $dateTimeColumns = ['publish_started_at', 'publish_ended_at'];
        foreach ($dateTimeColumns as $dateTimeColumn) {
            if (array_key_exists($dateTimeColumn, $input) and !empty($input[ $dateTimeColumn ])) {
                $input[ $dateTimeColumn ] = \DateTimeHelper::convertToStorageDateTime($input[ $dateTimeColumn ]);
            } else {
                $input[ $dateTimeColumn ] = null;
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
                'entityId'   => $model->id,
                'title'      => $request->input('title', ''),
            ]);
            $model = $this->articleRepository->update($model, ['cover_image_id' => $image->id]);
        }

        return redirect()->action('Admin\ArticleController@show', [$id])->with('message-success',
            trans('admin.messages.general.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Response
     */
    public function destroy($id)
    {
        /** @var \App\Models\Article $model */
        $model = $this->articleRepository->find($id);
        if (empty($model)) {
            \App::abort(404);
        }
        $this->articleRepository->delete($model);

        return redirect()->action('Admin\ArticleController@index')->with('message-success',
            trans('admin.messages.general.delete_success'));
    }

    /**
     * @param BaseRequest $request
     * @return \Response
     */
    public function preview(BaseRequest $request)
    {
        $locale = $request->input('language');

        $content = $this->articleService->filterContent($request->input('content'), $locale);
        $title = $request->input('title');
        $response = response()->view('pages.admin.articles.preview', [
            'content' => $content,
            'title'   => $title,
        ]);
        //        $response->headers->set('Content-Security-Policy', "default-src 'self' 'unsafe-inline'");
        $response->headers->set('X-XSS-Protection', "0");

        return $response;
    }
}
