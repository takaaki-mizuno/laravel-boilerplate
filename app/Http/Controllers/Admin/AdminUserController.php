<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\AdminUserRepositoryInterface;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Requests\PaginationRequest;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;

class AdminUserController extends Controller {

    /** @var \App\Repositories\AdminUserRepositoryInterface */
    protected $adminUserRepository;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    public function __construct(
        AdminUserRepositoryInterface $adminUserRepository,
        FileUploadServiceInterface      $fileUploadService,
        ImageRepositoryInterface        $imageRepository
    ) {
        $this->adminUserRepository      = $adminUserRepository;
        $this->fileUploadService        = $fileUploadService;
        $this->imageRepository          = $imageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\PaginationRequest $request
     *
     * @return \Response
     */
    public function index( PaginationRequest $request ) {
        $paginate[ 'offset' ] = $request->offset();
        $paginate[ 'limit' ] = $request->limit();
        $paginate[ 'order' ] = $request->order();
        $paginate[ 'direction' ] = $request->direction();
        $paginate[ 'baseUrl' ] = action( 'Admin\AdminUserController@index' );

        $count = $this->adminUserRepository->count();
        $models = $this->adminUserRepository->get(
            $paginate[ 'order' ],
            $paginate[ 'direction' ],
            $paginate[ 'offset' ],
            $paginate[ 'limit' ]
        );

        return view(
            'pages.admin.admin-users.index',
            [
                'models'   => $models,
                'count'    => $count,
                'paginate' => $paginate,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create() {
        return view(
            'pages.admin.admin-users.edit',
            [
                'isNew'     => true,
                'adminUser' => $this->adminUserRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Response
     */
    public function store( AdminUserRequest $request ) {
        $input = $request->only(
            [
                'name',
                'email',
                'password',
                'locale',
            ]
        );

        $model = $this->adminUserRepository->create( $input );

        if( empty( $model ) ) {
            return redirect()
                ->back()
                ->withErrors( trans( 'admin.errors.general.save_failed' ) );
        }

        if ($request->hasFile('profile_image')) {
            $file       = $request->file('profile_image');
            $mediaType  = $file->getClientMimeType();
            $path       = $file->getPathname();
            $image      = $this->fileUploadService->upload('user-profile-image', $path, $mediaType, [
                'entityType' => 'user-profile-image',
                'entityId'   => $model->id,
                'title'      => $request->input('name', ''),
            ]);

            if (!empty($image)) {
                $this->adminUserRepository->update($model, ['profile_image_id' => $image->id]);
            }
        }

        return redirect()
            ->action( 'Admin\AdminUserController@index' )
            ->with( 'message-success', trans( 'admin.messages.general.create_success' ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function show( $id ) {
        $model = $this->adminUserRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }

        return view(
            'pages.admin.admin-users.edit',
            [
                'isNew'     => false,
                'adminUser' => $model,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function edit( $id ) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param      $request
     *
     * @return \Response
     */
    public function update( $id, AdminUserRequest $request ) {
        /** @var \App\Models\AdminUser $model */
        $model = $this->adminUserRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }
        $input = $request->only(
            [
                'name',
                'email',
                'locale',
            ]
        );

        $this->adminUserRepository->update( $model, $input );

        if ($request->hasFile('profile_image')) {
            $file       = $request->file('profile_image');
            $mediaType  = $file->getClientMimeType();
            $path       = $file->getPathname();
            $image      = $this->fileUploadService->upload('user-profile-image', $path, $mediaType, [
                'entityType' => 'user-profile-image',
                'entityId'   => $model->id,
                'title'      => $request->input('name', ''),
            ]);

            if (!empty($image)) {
                $oldImage = $model->coverImage;
                if (!empty($oldImage)) {
                    $this->fileUploadService->delete($oldImage);
                    $this->imageRepository->delete($oldImage);
                }

                $this->adminUserRepository->update($model, [ 'profile_image_id' => $image->id ]);
            }
        }

        return redirect()
            ->action( 'Admin\AdminUserController@show', [$id] )
            ->with( 'message-success', trans( 'admin.messages.general.update_success' ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function destroy( $id ) {
        /** @var \App\Models\AdminUser $model */
        $model = $this->adminUserRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }
        $this->adminUserRepository->delete( $model );

        return redirect()
            ->action( 'Admin\AdminUserController@index' )
            ->with( 'message-success', trans( 'admin.messages.general.delete_success' ) );
    }

}
