<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\ImageRepositoryInterface;
use App\Http\Requests\Admin\ImageRequest;
use App\Http\Requests\PaginationRequest;

class ImageController extends Controller {

    /** @var \App\Repositories\ImageRepositoryInterface */
    protected $imageRepository;


    public function __construct(
        ImageRepositoryInterface $imageRepository
    ) {
        $this->imageRepository = $imageRepository;
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
        $paginate[ 'baseUrl' ] = action( 'Admin\ImageController@index' );

        $count = $this->imageRepository->count();
        $models = $this->imageRepository->get(
            $paginate[ 'order' ],
            $paginate[ 'direction' ],
            $paginate[ 'offset' ],
            $paginate[ 'limit' ]
        );

        return view(
            'pages.admin.images.index',
            [
                'models' => $models,
                'count' => $count,
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
            'pages.admin.images.edit',
            [
                'isNew' => true,
                'image' => $this->imageRepository->getBlankModel(),
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
    public function store( ImageRequest $request ) {
        $input = $request->only(
            [
                'url',
                'title',
                'entity_type',
                'file_category_type',
                's3_key',
                's3_bucket',
                's3_region',
                's3_extension',
                'media_type',
                'format',
                'file_size',
                'width',
                'height'
            ]
        );
        $input[ 'is_local' ] = $request->get( 'is_local', 0 );

        $input[ 'is_enabled' ] = $request->get( 'is_enabled', 0 );
        $model = $this->imageRepository->create( $input );

        if( empty( $model ) ) {
            return redirect()
                ->back()
                ->withErrors( trans( 'admin.errors.general.save_failed' ) );
        }

        return redirect()
            ->action( 'Admin\ImageController@index' )
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
        $model = $this->imageRepository->find( $id );
        if( empty( $model ) ) {
            \App::abort( 404 );
        }

        return view(
            'pages.admin.images.edit',
            [
                'isNew' => false,
                'image' => $model,
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
    public function update( $id, ImageRequest $request ) {
        /** @var \App\Models\Image $model */
        $model = $this->imageRepository->find( $id );
        if( empty( $model ) ) {
            \App::abort( 404 );
        }
        $input = $request->only(
            [
                'url',
                'title',
                'entity_type',
                'file_category_type',
                's3_key',
                's3_bucket',
                's3_region',
                's3_extension',
                'media_type',
                'format',
                'file_size',
                'width',
                'height'
            ]
        );
        $input[ 'is_local' ] = $request->get( 'is_local', 0 );

        $input[ 'is_enabled' ] = $request->get( 'is_enabled', 0 );
        $this->imageRepository->update( $model, $input );

        return redirect()
            ->action( 'Admin\ImageController@show', [$id] )
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
        /** @var \App\Models\Image $model */
        $model = $this->imageRepository->find( $id );
        if( empty( $model ) ) {
            \App::abort( 404 );
        }
        $this->imageRepository->delete( $model );

        return redirect()
            ->action( 'Admin\ImageController@index' )
            ->with( 'message-success', trans( 'admin.messages.general.delete_success' ) );
    }

}
