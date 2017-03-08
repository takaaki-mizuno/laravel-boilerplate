@extends('layouts.admin.application',['menu' => 'users'] )

@section('metadata')
@stop

@section('styles')
    <link rel="stylesheet" href="{!! \URLHelper::asset('libs/datetimepicker/css/bootstrap-datetimepicker.min.css', 'admin') !!}">
@stop

@section('scripts')
    <script src="{{ \URLHelper::asset('libs/moment/moment.min.js', 'admin') }}"></script>
    <script src="{{ \URLHelper::asset('libs/datetimepicker/js/bootstrap-datetimepicker.min.js', 'admin') }}"></script>
    <script>
        $('.datetime-field').datetimepicker({'format': 'YYYY-MM-DD'});

        $(document).ready(function () {
            $('#profile-image').change(function (event) {
                $('#profile-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
@stop

@section('title')
    {{ config('site.name') }} | Admin | Users | Edit
@stop

@section('header')
    Edit User:
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

    <form action="@if($isNew) {!! action('Admin\UserController@store') !!} @else {!! action('Admin\UserController@update', [$user->id]) !!} @endif" method="POST" enctype="multipart/form-data">
        @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
        {!! csrf_field() !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <a href="{!! URL::action('Admin\UserController@index') !!}"
                       class="btn btn-block btn-default btn-sm"
                       style="width: 125px;">@lang('admin.pages.common.buttons.back')</a>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group text-center">
                            @if( !empty($user->profileImage) )
                                <img id="profile-image-preview" style="max-width: 500px; width: 100%;" src="{!! $user->profileImage->getThumbnailUrl(480, 300) !!}" alt="" class="margin"/>
                            @else
                                <img id="profile-image-preview" style="max-width: 500px; width: 100%;" src="{!! \URLHelper::asset('img/no_image.jpg', 'common') !!}" alt="" class="margin"/>
                            @endif
                            <input type="file" style="display: none;" id="profile-image" name="profile_image">
                            <p class="help-block" style="font-weight: bolder;">
                                @lang('admin.pages.users.columns.profile_image_id')
                                <label for="profile-image" style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">@lang('admin.pages.common.buttons.edit')</label>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <table class="edit-user-profile">
                            <tr class="@if ($errors->has('name')) has-error @endif">
                                <td>
                                    <label for="name">@lang('admin.pages.users.columns.name')</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') ? old('name') : $user->name }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('email')) has-error @endif">
                                <td>
                                    <label for="email">@lang('admin.pages.users.columns.email')</label>
                                </td>
                                <td>
                                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') ? old('email') : $user->email }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('password')) has-error @endif">
                                <td>
                                    <label for="password">@lang('admin.pages.users.columns.password')</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="password" name="password" @if(!$isNew) disabled @endif value="{{ old('password') ? old('password') : $user->password }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('gender')) has-error @endif">
                                <td>
                                    <label for="gender">@lang('admin.pages.users.columns.gender')</label>
                                </td>
                                <td>
                                    <select class="form-control" name="gender" id="gender" style="margin-bottom: 15px;" required>
                                        <option value="1">@lang('admin.pages.users.columns.gender_male')</option>
                                        <option value="0">@lang('admin.pages.users.columns.gender_female')</option>
                                    </select>
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('locale')) has-error @endif">
                                <td>
                                    <label for="locale">@lang('admin.pages.articles.columns.locale')</label>
                                </td>
                                <td>
                                    <select class="form-control" name="locale" id="locale" style="margin-bottom: 15px;" required>
                                        <option value="">@lang('admin.pages.common.label.select_locale')</option>
                                        @foreach( config('locale.languages') as $code => $locale )
                                            <option value="{!! $code !!}" @if( (old('locale') && old('locale') == $code) || ( $user->locale === $code) ) selected @endif >
                                                {{ trans($locale['name']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>


                            <tr class="@if ($errors->has('address')) has-error @endif">
                                <td>
                                    <label for="address">@lang('admin.pages.users.columns.address')</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') ? old('address') : $user->address }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('is_activated')) has-error @endif">
                                <td>
                                    <label for="is_activated">@lang('admin.pages.users.columns.is_activated')</label>
                                </td>
                                <td>
                                    <div class="switch" style="margin-bottom: 10px;">
                                        <input id="is_activated" name="is_activated" value="1" @if( $user->is_activated) checked @endif class="cmn-toggle cmn-toggle-round-flat" type="checkbox">
                                        <label for="is_activated"></label>
                                    </div>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('telephone')) has-error @endif">
                            <label for="telephone">@lang('admin.pages.users.columns.telephone')</label>
                            <input type="text" class="form-control" id="telephone" name="telephone"  value="{{ old('telephone') ? old('telephone') : $user->telephone }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('birthday')) has-error @endif">
                            <label for="birthday">@lang('admin.pages.users.columns.birthday')</label>
                            <div class="input-group date datetime-field" style="margin-bottom: 10px;">
                                <input type="text" class="form-control" style="margin: 0;" name="birthday" required value="{{ old('birthday') ? old('birthday') : $user->birthday }}">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('api_access_token')) has-error @endif">
                            <label for="api_access_token">@lang('admin.pages.users.columns.api_access_token')</label>
                            <input type="text" class="form-control" id="api_access_token" name="api_access_token" disabled value="{{ old('api_access_token') ? old('api_access_token') : $user->api_access_token }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('remember_token')) has-error @endif">
                            <label for="remember_token">@lang('admin.pages.users.columns.remember_token')</label>
                            <input type="text" class="form-control" id="remember_token" name="remember_token" disabled value="{{ old('remember_token') ? old('remember_token') : $user->remember_token }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="inputPassword3"
                               class="col-sm-2 control-label">@lang('admin.pages.users.columns.password')</label>

                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputPassword3" placeholder="Password"
                                   value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Save</button>
        </div>
        <!-- /.box-footer -->
    </form>
@stop
