@extends('layouts.admin.application', ['noFrame' => true, 'bodyClasses' => 'hold-transition login-page'])

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
@stop

@section('header')
Reset Password
@stop

@section('content')
    <body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{!! action('User\IndexController@index') !!}"><b>{{ config('site.name') }}</b> Admin</a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">@lang('admin.pages.auth.messages.reset_password')</p>
            <form action="{!! URL::action('Admin\PasswordController@postResetPassword') !!}" method="post">
                {!! csrf_field() !!}
                <div class="form-group has-feedback">
                    <input type="email" name="email" class="form-control" placeholder="Email"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="Password"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Email"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <input type="hidden" name="token" value="{{ $token }}"/>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('admin.pages.auth.buttons.reset_password')</button>
                    </div><!-- /.col -->
                </div>
            </form>
        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    </body>
@stop
