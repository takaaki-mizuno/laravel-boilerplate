@extends('layouts.admin.application', ['menu' => 'admin_user_notifications'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
@stop

@section('header')
AdminUserNotifications
@stop

@section('breadcrumb')
    <li><a href="{!! action('Admin\AdminUserNotificationController@index') !!}"><i class="fa fa-files-o"></i> AdminUserNotifications</a></li>
    @if( $isNew )
        <li class="active">New</li>
    @else
        <li class="active">{{ $adminUserNotification->id }}</li>
    @endif
@stop

@section('content')
    @if( $isNew )
        <form action="{!! action('Admin\AdminUserNotificationController@store') !!}" method="POST" enctype="multipart/form-data">
    @else
        <form action="{!! action('Admin\AdminUserNotificationController@update', [$adminUserNotification->id]) !!}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
    @endif
            {!! csrf_field() !!}
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">

                    </h3>
                </div>
                <div class="box-body">

                    <div class="form-group">
                        <p>{{ $adminUserNotification->present()->userName }}</p>
                        <input type="hidden" name="user_id" value="{{ $adminUserNotification->user_id  }}">
                    </div>

                    <div class="form-group @if ($errors->has('category_type')) has-error @endif">
                        <label for="category_type">@lang('admin.pages.admin-user-notifications.columns.category_type')</label>
                        <input type="text" class="form-control" id="category_type" name="category_type" value="{{ old('category_type') ? old('category_type') : $adminUserNotification->category_type }}">
                    </div>

                    <div class="form-group @if ($errors->has('type')) has-error @endif">
                        <label for="type">@lang('admin.pages.admin-user-notifications.columns.type')</label>
                        <input type="text" class="form-control" id="type" name="type" value="{{ old('type') ? old('type') : $adminUserNotification->type }}">
                    </div>

                    <div class="form-group @if ($errors->has('locale')) has-error @endif">
                        <label for="locale">@lang('admin.pages.site-configurations.columns.locale')</label>
                        <select name="locale" class="form-control">
                            @foreach( config('locale.languages') as $code => $locale )
                                <option value="{!! $code !!}" @if( (old('locale') && old('locale') == $code) || (!old('locale') && $adminUserNotification->locale == $code)) selected @endif >@lang(array_get($locale, 'name', $code))</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group @if ($errors->has('data')) has-error @endif">
                        <label for="data">@lang('admin.pages.admin-user-notifications.columns.data')</label>
                        <textarea name="data" class="form-control" rows="5" placeholder="JSON DATA">{{ old('data') ? old('data') : json_encode($adminUserNotification->data) }}</textarea>
                    </div>

                    <div class="form-group @if ($errors->has('content')) has-error @endif">
                        <label for="content">@lang('admin.pages.admin-user-notifications.columns.content')</label>
                        <textarea name="content" class="form-control" rows="5" placeholder="Content">{{ old('content') ? old('content') : $adminUserNotification->content }}</textarea>
                    </div>

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">@lang('admin.pages.common.buttons.save')</button>
                </div>
            </div>
        </form>
@stop