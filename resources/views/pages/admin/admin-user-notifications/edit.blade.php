@extends('layouts.admin.application', ['menu' => 'admin_user_notifications'] )

@section('metadata')
@stop

@section('styles')
    <link rel="stylesheet" href="{!! \URLHelper::asset('libs/datetimepicker/css/bootstrap-datetimepicker.min.css', 'admin') !!}">
@stop

@section('scripts')
    <script src="{{ \URLHelper::asset('libs/moment/moment.min.js', 'admin') }}"></script>
    <script src="{{ \URLHelper::asset('libs/datetimepicker/js/bootstrap-datetimepicker.min.js', 'admin') }}"></script>
    <script>
        $('.datetime-field').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss'});

        $(document).ready(function () {
            $('#avatar-image').change(function (event) {
                $('#avatar-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
@stop

@section('title')
@stop

@section('header')
    AdminUserNotifications
@stop

@section('breadcrumb')
    <li><a href="{!! action('Admin\AdminUserNotificationController@index') !!}"><i class="fa fa-files-o"></i>
            AdminUserNotifications</a></li>
    @if( $isNew )
        <li class="active">New</li>
    @else
        <li class="active">{{ $adminUserNotification->id }}</li>
    @endif
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="@if($isNew) {!! action('Admin\AdminUserNotificationController@store') !!} @else {!! action('Admin\AdminUserNotificationController@update', [$adminUserNotification->id]) !!} @endif"
          method="POST" enctype="multipart/form-data">
        @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
        {!! csrf_field() !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <a href="{!! URL::action('Admin\AdminUserNotificationController@index') !!}"
                       class="btn btn-block btn-default btn-sm"
                       style="width: 125px;">@lang('admin.pages.common.buttons.back')</a>
                </h3>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if ($errors->has('type')) has-error @endif">
                            <label for="type">@lang('admin.pages.admin-user-notifications.columns.type')</label>
                            <input type="text" class="form-control" id="type" name="type" value="{{ old('type') ? old('type') : $adminUserNotification->type }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group @if ($errors->has('category_type')) has-error @endif">
                            <label for="category_type">@lang('admin.pages.admin-user-notifications.columns.category_type')</label>
                            <input type="text" class="form-control" id="category_type" name="category_type" value="{{ old('category_type') ? old('category_type') : $adminUserNotification->category_type }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group @if ($errors->has('locale')) has-error @endif">
                            <label for="locale">@lang('admin.pages.admin-user-notifications.columns.locale')</label>
                            <input type="text" class="form-control" id="locale" name="locale" value="{{ old('locale') ? old('locale') : $adminUserNotification->locale }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="sent_at">@lang('admin.pages.admin-user-notifications.columns.sent_at')</label>
                            <div class="input-group date datetime-field">
                                <input type="text" class="form-control" name="sent_at"
                                       value="{{ old('sent_at') ? old('sent_at') : $adminUserNotification->sent_at }}">
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="read">@lang('admin.pages.admin-user-notifications.columns.read')</label>
                            <div class="switch">
                                <input id="read" name="read" value="1"
                                       @if( $adminUserNotification->read) checked
                                       @endif class="cmn-toggle cmn-toggle-round-flat" type="checkbox">
                                <label for="read"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group @if ($errors->has('data')) has-error @endif">
                            <label for="data">@lang('admin.pages.admin-user-notifications.columns.data')</label>
                            <textarea name="data" class="form-control" rows="5" placeholder="@lang('admin.pages.admin-user-notifications.columns.data')">{{ old('data') ? old('data') : $adminUserNotification->data }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group @if ($errors->has('content')) has-error @endif">
                            <label for="content">@lang('admin.pages.admin-user-notifications.columns.content')</label>
                            <textarea name="content" class="form-control" rows="5" placeholder="@lang('admin.pages.admin-user-notifications.columns.content')">{{ old('content') ? old('content') : $adminUserNotification->content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm" style="width: 125px;">@lang('admin.pages.common.buttons.save')</button>
            </div>
        </div>
    </form>
@stop
