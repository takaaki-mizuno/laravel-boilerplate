<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequest;
use App\Repositories\UserRepositoryInterface;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;

class UserController extends Controller
{
    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    public function __construct(
        UserRepositoryInterface     $userRepository,
        FileUploadServiceInterface  $fileUploadService,
        ImageRepositoryInterface    $imageRepository
    ) {
        $this->userRepository       = $userRepository;
        $this->fileUploadService    = $fileUploadService;
        $this->imageRepository      = $imageRepository;
    }

    public function show(APIRequest $request)
    {
        return $request['_user']->toAPIArray();
    }

    public function update(APIRequest $request)
    {
        $data = $request->all();
        $paramsAllow = [
            'string'   => [
                'name',
                'telephone',
                'address',
                'avatar',
            ],
            'enum'     => [
                'locale' => ['en', 'ja', 'th']
            ],
            'numeric'  => [
                '>=0' => ['gender'],
                '<=1' => ['gender']
            ],
            'datetime' => [
                'birthday' => 'Y-m-d'
            ]
        ];
        $paramsRequire = ['name', 'gender', 'telephone'];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);
        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        try {
            $user = $this->userRepository->update($request['_user'], $data);
        } catch (\Exception $e) {
            return $this->response(901);
        }

        if ($request->hasFile('profile_image')) {
            $file       = $request->file('profile_image');
            $mediaType  = $file->getClientMimeType();
            $path       = $file->getPathname();
            $image      = $this->fileUploadService->upload('user-profile-image', $path, $mediaType, [
                'entityType' => 'user-profile-image',
                'entityId'   => $user->id,
                'title'      => $request->input('name', ''),
            ]);

            if (!empty($image)) {
                $oldImage = $user->coverImage;
                if (!empty($oldImage)) {
                    $this->fileUploadService->delete($oldImage);
                    $this->imageRepository->delete($oldImage);
                }

                $this->userRepository->update($user, [ 'profile_image_id' => $image->id ]);
            }
        }

        return $this->response(100, $user->toAPIArray());
    }
}