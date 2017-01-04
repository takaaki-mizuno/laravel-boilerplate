@extends('layouts.admin.application', ['menu' => 'user_notifications'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
@stop

@section('header')
    UserNotifications
@stop

@section('breadcrumb')
    <li><a href="{!! action('Admin\UserNotificationController@index') !!}"><i class="fa fa-files-o"></i>
            UserNotifications</a></li>
    @if( $isNew )
        <li class="active">New</li>
    @else
        <li class="active">{{ $userNotification->id }}</li>
    @endif
@stop

@section('content')
    @if( $isNew )
        <form action="{!! action('Admin\UserNotificationController@store') !!}" method="POST"
              enctype="multipart/form-data">
    @else
        <form action="{!! action('Admin\UserNotificationController@update', [$userNotification->id]) !!}"
              method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            @endif
            {!! csrf_field() !!}
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">

                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>{{ $userNotification->present()->userName }}</p>
                                <input type="hidden" name="user_id" value="{{ $userNotification->user_id  }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('category_type')) has-error @endif">
                                <label
                                    for="category_type">@lang('admin.pages.user-notifications.columns.category_type')</label>
                                <input type="text" class="form-control" id="category_type" name="category_type"
                                       value="{{ old('category_type') ? old('category_type') : $userNotification->category_type }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('type')) has-error @endif">
                                <label for="type">@lang('admin.pages.user-notifications.columns.type')</label>
                                <input type="text" class="form-control" id="type" name="type"
                                       value="{{ old('type') ? old('type') : $userNotification->type }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('locale')) has-error @endif">
                                <label
                                    for="locale">@lang('admin.pages.site-configurations.columns.locale')</label>
                                <select name="locale" class="form-control">
                                    @foreach( config('locale.languages') as $code => $locale )
                                        <option value="{!! $code !!}"
                                                @if( (old('locale') && old('locale') == $code) || (!old('locale') && $userNotification->locale == $code)) selected @endif >@lang(array_get($locale, 'name', $code))</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('data')) has-error @endif">
                                <label for="data">@lang('admin.pages.user-notifications.columns.data')</label>
                                <textarea name="data" class="form-control" rows="5"
                                          placeholder="JSON DATA">{{ old('data') ? old('data') : json_encode($userNotification->data) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('content')) has-error @endif">
                                <label
                                    for="content">@lang('admin.pages.user-notifications.columns.content')</label>
                                <textarea name="content" class="form-control" rows="5"
                                          placeholder="Content">{{ old('content') ? old('content') : $userNotification->content }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit"
                            class="btn btn-primary">@lang('admin.pages.common.buttons.save')</button>
                </div>
            </div>
        </form>
@stop
