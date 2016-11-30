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
Forgot Password
@stop

@section('content')
    <body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{!! action('User\IndexController@index') !!}"><b>{{ config('site.name') }}</b> Admin</a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">@lang('admin.pages.auth.messages.forgot_password')</p>
            <form action="{!! URL::action('Admin\PasswordController@postForgotPassword') !!}" method="post">
                {!! csrf_field() !!}
                <div class="form-group has-feedback">
                    <input type="email" name="email" class="form-control" placeholder="Email"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('admin.pages.auth.buttons.forgot_password')</button>
                    </div><!-- /.col -->
                </div>
            </form>
        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    </body>
@stop
