@extends('layouts.user.application', ['noFrame' => true, 'bodyClasses' => ''])

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
    Sign In
@stop

@section('header')
    Sign In
@stop

@section('content')
    <form action="{!! action('User\AuthController@postSignIn') !!}" method="post">
        {!! csrf_field() !!}
        <input type="email" name="email" placeholder="@lang('user.pages.auth.messages.email')">
        <input type="password" name="password" placeholder="@lang('user.pages.auth.messages.password')">
        <input type="checkbox" name="remember_me" value="1"> @lang('user.pages.auth.messages.remember_me')
        <button type="submit"
                class="btn btn-primary btn-block btn-flat">@lang('user.pages.auth.buttons.sign_in')</button>
    </form>
    <a href="{!! action('User\PasswordController@getForgotPassword') !!}">@lang('user.pages.auth.messages.forgot_password')</a>
    <br>
@stop
