@extends('layouts.admin.application', ['menu' => 'user_notifications'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('js/delete_item.js', 'admin') !!}"></script>
@stop

@section('title')
@stop

@section('header')
    UserNotifications
@stop

@section('breadcrumb')
    <li class="active">UserNotifications</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">

            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title">
                        <p class="text-right">
                            <a href="{!! URL::action('Admin\UserNotificationController@create') !!}"
                               class="btn btn-block btn-primary btn-sm"
                               style="width: 125px;">@lang('admin.pages.common.buttons.create')</a>
                        </p>
                    </h3>
                    <br>
                    <p style="display: inline-block;">@lang('admin.pages.common.label.search_results', ['count' => $count])</p>
                </div>
                <div class="col-sm-6 wrap-top-pagination">
                    <div class="heading-page-pagination">
                        {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], [], $count, 'shared.topPagination') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body" style=" overflow-x: scroll; ">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('locale', trans('admin.pages.user-notifications.columns.locale')) !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('read', trans('admin.pages.user-notifications.columns.read')) !!}</th>
                    <th>{!! \PaginationHelper::sort('category_type', trans('admin.pages.user-notifications.columns.category_type')) !!}</th>
                    <th>{!! \PaginationHelper::sort('type', trans('admin.pages.user-notifications.columns.type')) !!}</th>
                    <th>{!! \PaginationHelper::sort('sent_at', trans('admin.pages.user-notifications.columns.sent_at')) !!}</th>

                    <th style="width: 40px">@lang('admin.pages.common.label.actions')</th>
                </tr>
                @foreach( $models as $model )
                    <tr>
                        <td>{{ $model->id }}</td>
                        <td>{{ $model->locale }}</td>
                        <td>
                            @if( $model->read )
                                <span class="badge bg-green">@lang('admin.pages.user-notifications.columns.read_true')</span>
                            @else
                                <span class="badge bg-yellow">@lang('admin.pages.user-notifications.columns.read_false')</span>
                            @endif
                        </td>
                        <td>{{ $model->category_type }}</td>
                        <td>{{ $model->type }}</td>
                        <td>{{ $model->sent_at }}</td>
                        <td>
                            <a href="{!! URL::action('Admin\UserNotificationController@show', $model->id) !!}"
                               class="btn btn-block btn-primary btn-xs">@lang('admin.pages.common.buttons.edit')</a>
                            <a href="#" class="btn btn-block btn-danger btn-xs delete-button"
                               data-delete-url="{!! action('Admin\UserNotificationController@destroy', $model->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], []) !!}
        </div>
    </div>
@stop