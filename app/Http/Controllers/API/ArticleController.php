<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequest;
use App\Repositories\ArticleRepositoryInterface;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;

class ArticleController extends Controller
{
    /** @var \App\Repositories\ArticleRepositoryInterface */
    protected $articleRepository;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    public function __construct(
        ArticleRepositoryInterface  $articleRepository,
        FileUploadServiceInterface  $fileUploadService,
        ImageRepositoryInterface    $imageRepository
    ) {
        $this->articleRepository    = $articleRepository;
        $this->fileUploadService    = $fileUploadService;
        $this->imageRepository      = $imageRepository;
    }

    public function index(APIRequest $request)
    {
        $data = $request->all();
        $paramsAllow = [
            'enum'    => [
                'order'     => ['id', 'title', 'keywords', 'description', 'content', 'locale', 'publish_started_at'],
                'direction' => ['asc', 'desc']
            ],
            'numeric' => [
                '>=0' => ['offset', 'limit']
            ]
        ];
        $paramsRequire = ['order', 'direction', 'offset', 'limit'];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);
        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        $articles = $this->articleRepository->get($data['order'], $data['direction'], $data['offset'], $data['limit']); // change get() to geEnabled as requirement
        foreach( $articles as $key => $article ) {
            $articles[$key] = $article->toAPIArray();
        }

        return $this->response(100, $articles);
    }

    public function show($id, APIRequest $request)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return $this->response(104);
        }

        $article = $this->articleRepository->find($id);
        if( empty($article) ) {
            return $this->response(902);
        }

        if( $article->author_id != $request['_user']['id'] ) {
            return $this->response(107);
        }


        return $this->response(100, $article->toAPIArray());
    }

    public function store(APIRequest $request)
    {
        $data = $request->all();
        $paramsAllow = [
            'string'   => [
                'slug',
                'title',
                'keywords',
                'description',
                'content'
            ],
            'enum'     => [
                'locale' => ['en', 'ja', 'th']
            ],
            'numeric'  => [
                '>=0' => ['is_enabled'],
                '<=1' => ['is_enabled']
            ],
            'datetime' => [
                'publish_started_at' => 'Y-m-d H:i:s',
                'publish_ended_at'   => 'Y-m-d H:i:s'
            ]
        ];
        $paramsRequire = ['title', 'content', 'publish_started_at'];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);
        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        $data['publish_started_at'] = isset($data['publish_started_at']) ? \DateTimeHelper::convertToStorageDateTime($data['publish_started_at']) : null;
        $data['publish_ended_at']   = isset($data['publish_ended_at']) ? \DateTimeHelper::convertToStorageDateTime($data['publish_ended_at']) : null;

        try {
            $article = $this->articleRepository->create($data);
        } catch (\Exception $e) {
            return $this->response(901);
        }

        if( empty( $article ) ) {
            return $this->response(901);
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $image = $this->fileUploadService->upload(
                'article-cover-image',
                $path,
                $mediaType,
                [
                    'entityType' => 'article-cover-image',
                    'entityId'   => $article->id,
                    'title'      => $request->input('title', ''),
                ]
            );

            if (!empty($image)) {
                $article = $this->articleRepository->update($article, ['cover_image_id' => $image->id]);
            }
        }

        return $this->response(100, $article->toAPIArray());
    }

    public function update($id, APIRequest $request)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return $this->response(104);
        }

        $article = $this->articleRepository->find($id);
        if( empty($article) ) {
            return $this->response(902);
        }

        $data = $request->all();
        $paramsAllow = [
            'string'   => [
                'slug',
                'title',
                'keywords',
                'description',
                'content'
            ],
            'enum'     => [
                'locale' => ['en', 'ja', 'th']
            ],
            'numeric'  => [
                '>=0' => ['is_enabled'],
                '<=1' => ['is_enabled']
            ],
            'datetime' => [
                'publish_started_at' => 'Y-m-d H:i:s',
                'publish_ended_at'   => 'Y-m-d H:i:s'
            ]
        ];
        $paramsRequire = ['title', 'content', 'publish_started_at'];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);
        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        $data['publish_started_at'] = isset($data['publish_started_at']) ? \DateTimeHelper::convertToStorageDateTime($data['publish_started_at']) : null;
        $data['publish_ended_at']   = isset($data['publish_ended_at']) ? \DateTimeHelper::convertToStorageDateTime($data['publish_ended_at']) : null;


        try {
            $this->articleRepository->update($article, $data);
        } catch (\Exception $e) {
            return $this->response(901);
        }

        if( $request->hasFile( 'cover_image' ) ) {
            $currentImage = $article->coverImage;
            $file = $request->file( 'cover_image' );
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $newImage = $this->fileUploadService->upload(
                'article-cover-image',
                $path,
                $mediaType,
                [
                    'entityType' => 'article',
                    'entityId'   => $article->id,
                    'title'      => $request->input( 'name', '' ),
                ]
            );

            if( !empty( $newImage ) ) {
                $article = $this->articleRepository->update( $article, ['cover_image_id' => $newImage->id] );

                if( !empty( $currentImage ) ) {
                    $this->fileUploadService->delete( $currentImage );
                    $this->imageRepository->delete( $currentImage );
                }
            }
        }

        return $this->response(100, $article->toAPIArray());
    }

    public function destroy($id, APIRequest $request)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return $this->response(104);
        }

        $article = $this->articleRepository->find($id);
        if( empty($article) ) {
            return $this->response(902);
        }

        if( $article->author_id != $request['_user']['id'] ) {
            return $this->response(107);
        }
        
        try {
            $this->articleRepository->delete($article);
        } catch (\Exception $e) {
            return $this->response(901);
        }

        return $this->response(100);
    }
}
