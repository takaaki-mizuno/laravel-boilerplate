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
<li class="active">Notifications</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            {!! \PaginationHelper::render($offset, $limit, $count, $baseUrl, []) !!}
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th>@lang('admin.pages.user-notifications.columns.content')</th>
                    <th>@lang('admin.pages.user-notifications.columns.sent_at')</th>
                </tr>
                @foreach( $models as $model )
                    <tr>
                        <td>{{ $model->content }}</td>
                        <td>{{ $model->sent_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($offset, $limit, $count, $baseUrl, []) !!}
        </div>
    </div>
@stop