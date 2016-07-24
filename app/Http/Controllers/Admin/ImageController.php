<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ArticleRepositoryInterface;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\Admin\ImageRequest;

use App\Services\FileUploadServiceInterface;
use App\Services\ImageServiceInterface;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    /** @var \App\Repositories\ImageRepositoryInterface */
    protected $imageRepository;

    /** @var ArticleRepositoryInterface */
    protected $articleRepository;

    /** @var \App\Services\FileUploadServiceInterface */
    protected $fileUploadService;

    /** @var \App\Services\ImageServiceInterface */
    protected $imageService;

    public function __construct(
        ImageRepositoryInterface $imageRepository,
        ArticleRepositoryInterface $articleRepository,
        FileUploadServiceInterface $fileUploadService,
        ImageServiceInterface $imageService
    )
    {
        $this->imageRepository = $imageRepository;
        $this->articleRepository = $articleRepository;
        $this->fileUploadService = $fileUploadService;

        $this->middleware('admin.has_role.editor');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Requests\PaginationRequest $request
     * @return \Response
     */
    public function index(PaginationRequest $request)
    {
        $entityId = $request->input('entity_id', null);
        $type = $request->input('type', '');
        if ($entityId === null) {
            $offset = $request->offset();
            $limit = $request->limit();
            $count = $this->imageRepository->count();
            $models = $this->imageRepository->get('id', 'asc', $offset, $limit);

            return response()->json([
                'models'  => $models,
                'count'   => $count,
                'offset'  => $offset,
                'limit'   => $limit,
                'baseUrl' => \URL::action('Admin\ImageController@index'),
            ], 200);
        } else {
            if ($entityId > 0) {
                $models = $this->imageRepository->allByTypeAndEntityId($type, $entityId);
            } else {
                $ids = $this->image();
                $models = $this->imageRepository->getByIds($ids);
            }
            $ret = [];
            foreach ($models as $model) {
                $ret[] = $model->getUrl();
            }

            return response()->json($ret, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ImageRequest $imageRequest
     * @return \Response
     */
    public function store(ImageRequest $imageRequest)
    {
        if (!$imageRequest->hasFile('file')) {
            // [TODO] ERROR JSON
            \App::abort(400, 'No Image File');
        }

        $type = $imageRequest->input('type', '');
        $entityId = $imageRequest->input('entity_id', 0);
        $categoryType = $imageRequest->input('file_category_type', '');

        $conf = \Config::get('file.categories.'.$categoryType);
        if (empty($conf)) {
            \App::abort(400, "Invalid category: ".$categoryType);
        }

        $file = $imageRequest->file('file');
        $mediaType = $file->getClientMimeType();
        $path = $file->getPathname();
        $image = $this->fileUploadService->upload($categoryType, $path, $mediaType, [
            'entityType' => $type,
            'entityId'   => $entityId,
            'title'      => $imageRequest->input('title', ''),
        ]);

        if ($entityId == 0) {
            $this->imageService->addImageIdToSession($image->id);
        }

        return response()->json([
            'id'   => $image->id,
            'link' => $image->getUrl(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Response
     */
    public function show($id)
    {
        //
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
     * @return \Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Response
     */
    public function destroy($id)
    {
        $image = $this->imageRepository->find($id);
        if (empty($image)) {
            \App::abort(404);
        }
    }

    public function deleteByUrl(Request $request)
    {
        $url = $request->input('url');
        if (empty($url)) {
            \App::abort(400, 'No URL Given');
        }
        /** @var \App\Models\Image|null $image */
        $image = $this->imageRepository->findByUrl($url);
        if (empty($image)) {
            \App::abort(404);
        }
        $entityId = $request->input('entity_id', 0);
        if ($entityId != $image->entity_id) {
            \App::abort(400, 'Article ID Mismatch');
        } else {
            if ($entityId == 0 && !$this->imageService->hasImageIdInSession($image->id)) {
                \App::abort(400, 'Entity ID Mismatch');
            }
        }

        $this->fileUploadService->delete($image);
        $this->imageRepository->delete($image);

        if ($entityId == 0) {
            $this->imageService->removeImageIdFromSession($image->id);
        }

        return response()->json(['status' => 'ok'], 204);
    }

}
