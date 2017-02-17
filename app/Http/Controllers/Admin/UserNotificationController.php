<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\UserNotificationRepositoryInterface;
use App\Http\Requests\Admin\UserNotificationRequest;
use App\Http\Requests\PaginationRequest;

class UserNotificationController extends Controller {

    /** @var \App\Repositories\UserNotificationRepositoryInterface */
    protected $userNotificationRepository;


    public function __construct(
        UserNotificationRepositoryInterface $userNotificationRepository
    ) {
        $this->userNotificationRepository = $userNotificationRepository;
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
        $paginate[ 'baseUrl' ] = action( 'Admin\UserNotificationController@index' );

        $count = $this->userNotificationRepository->count();
        $models = $this->userNotificationRepository->get(
            $paginate[ 'order' ],
            $paginate[ 'direction' ],
            $paginate[ 'offset' ],
            $paginate[ 'limit' ]
        );

        return view(
            'pages.admin.user-notifications.index',
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
            'pages.admin.user-notifications.edit',
            [
                'isNew'            => true,
                'userNotification' => $this->userNotificationRepository->getBlankModel(),
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
    public function store( UserNotificationRequest $request ) {
        $input = $request->only(
            [
                'category_type',
                'type',
                'data',
                'content',
                'locale',
                'sent_at'
            ]
        );

        $input[ 'sent_at' ] = ( $input[ 'sent_at' ] != "" ) ? $input[ 'sent_at' ] : null;
        $input[ 'read' ]    = $request->get( 'read', 0 );

        $model = $this->userNotificationRepository->create( $input );

        if( empty( $model ) ) {
            return redirect()
                ->back()
                ->withErrors( trans( 'admin.errors.general.save_failed' ) );
        }

        return redirect()
            ->action( 'Admin\UserNotificationController@index' )
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
        $model = $this->userNotificationRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }

        return view(
            'pages.admin.user-notifications.edit',
            [
                'isNew'            => false,
                'userNotification' => $model,
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
    public function update( $id, UserNotificationRequest $request ) {
        /** @var \App\Models\UserNotification $model */
        $model = $this->userNotificationRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }
        $input = $request->only(
            [
                'category_type',
                'type',
                'data',
                'content',
                'locale',
                'sent_at'
            ]
        );

        $input[ 'sent_at' ] = ( $input[ 'sent_at' ] != "" ) ? $input[ 'sent_at' ] : null;
        $input[ 'read' ]    = $request->get( 'read', 0 );
 
        $this->userNotificationRepository->update( $model, $input );

        return redirect()
            ->action( 'Admin\UserNotificationController@show', [$id] )
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
        /** @var \App\Models\UserNotification $model */
        $model = $this->userNotificationRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }
        $this->userNotificationRepository->delete( $model );

        return redirect()
            ->action( 'Admin\UserNotificationController@index' )
            ->with( 'message-success', trans( 'admin.messages.general.delete_success' ) );
    }

}
