@extends('layouts.admin.application', ['noFrame' => true, 'bodyClasses' => 'hold-transition login-page'])

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
<script>
$(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
    });
});
</script>
@stop

@section('title')
Sign In
@stop

@section('header')
Sign In
@stop

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{!! action('User\IndexController@index') !!}"><b>{{ config('site.name') }}</b> Admin</a>
    </div>
    <!-- /.login-logo -->

    <div class="login-box-body">
        <p class="login-box-msg">@lang('admin.pages.auth.messages.please_sign_in')</p>

        <form action="{!! action('Admin\AuthController@postSignIn') !!}" method="post">
            {!! csrf_field() !!}
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember_me" value="1"> @lang('admin.pages.auth.messages.remember_me')
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('admin.pages.auth.buttons.sign_in')</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <a href="{!! action('Admin\PasswordController@getForgotPassword') !!}">@lang('admin.pages.auth.messages.forgot_password')</a><br>

    </div>
    <!-- /.login-box-body -->
</div>
@stop
