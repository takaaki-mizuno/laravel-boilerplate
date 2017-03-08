<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\UserRepositoryInterface;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Requests\PaginationRequest;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;

class UserController extends Controller {

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    public function __construct(
        UserRepositoryInterface         $userRepository,
        FileUploadServiceInterface      $fileUploadService,
        ImageRepositoryInterface        $imageRepository
    ) {
        $this->userRepository           = $userRepository;
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
        $paginate[ 'offset' ]    = $request->offset();
        $paginate[ 'limit' ]     = $request->limit();
        $paginate[ 'order' ]     = $request->order();
        $paginate[ 'direction' ] = $request->direction();
        $paginate[ 'baseUrl' ]   = action( 'Admin\UserController@index' );

        $count  = $this->userRepository->count();
        $models = $this->userRepository->get(
            $paginate[ 'order' ],
            $paginate[ 'direction' ],
            $paginate[ 'offset' ],
            $paginate[ 'limit' ]
        );

        return view(
            'pages.admin.users.index',
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
            'pages.admin.users.edit',
            [
                'isNew' => true,
                'user'  => $this->userRepository->getBlankModel(),
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
    public function store( UserRequest $request ) {
        $input = $request->only(
            [
                'name',
                'email',
                'password',
                'telephone',
                'birthday',
                'locale',
                'address',
            ]
        );

        $input['is_activated']  = $request->get('is_activated', 0);
        $input['gender']        = $request->get('gender', 1);
        $input['locale']        = $request->get('locale', 'en');
        $model = $this->userRepository->create( $input );

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
                $this->userRepository->update($model, ['profile_image_id' => $image->id]);
            }
        }

        return redirect()
            ->action( 'Admin\UserController@index' )
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
        $model = $this->userRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }

        return view(
            'pages.admin.users.edit',
            [
                'isNew' => false,
                'user'  => $model,
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
    public function update( $id, UserRequest $request ) {
        /** @var \App\Models\User $model */
        $model = $this->userRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }
        $input = $request->only(
            [
                'name',
                'email',
                'gender',
                'telephone',
                'birthday',
                'locale',
                'address',
            ]
        );

        $input['is_activated']  = $request->get('is_activated', 0);
        $input['gender']        = $request->get('gender', 1);
        $this->userRepository->update( $model, $input );

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

                $this->userRepository->update($model, [ 'profile_image_id' => $image->id ]);
            }
        }

        return redirect()
            ->action( 'Admin\UserController@show', [$id] )
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
        /** @var \App\Models\User $model */
        $model = $this->userRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }
        $this->userRepository->delete( $model );

        return redirect()
            ->action( 'Admin\UserController@index' )
            ->with( 'message-success', trans( 'admin.messages.general.delete_success' ) );
    }

}
