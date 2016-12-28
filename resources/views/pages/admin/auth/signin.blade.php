@extends('layouts.admin.application', ['noFrame' => true, 'bodyClasses' => 'hold-transition login-page'])

@section('metadata')
@stop

@section('styles')
    <style>
        .input-group .input-group-addon {
            border: none;
            border-right: 1px solid #ccc;
            vertical-align: middle;
            font-size: 14px;
            font-weight: 400;
            line-height: 1;
            color: #555;
            text-align: center;
            background-color: #eee;
            padding: 5px 50px;
        }
        @media screen and (max-width: 480px) {
            .profile-img {
                width: 100px;
            }
            .input-group .input-group-addon {
                padding: 5px 25px;
            }
        }
    </style>
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
    <div id="login" class="container" style="margin-top:90px">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <strong style="font-size: 20px;">Laravel Boilerplate </strong>Admin
                    </div>
                    <div class="panel-body" style="padding: 30px 60px;">
                        <form action="{!! action('Admin\AuthController@postSignIn') !!}" method="post">
                            {!! csrf_field() !!}
                            <fieldset>
                                <div class="row" style="margin-bottom: 30px;">
                                    <div class="center-block text-center">
                                        <img width="150" src="{{ \URLHelper::asset('img/user_avatar.png', 'common') }}" class="profile-img" alt="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                        <div class="form-group" style=" margin-bottom: 20px; width: 100%;">
                                            <div class="input-group" style="width: 100%; border: 1px solid #ccc;">
                                                <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                                <input type="email" name="email" class="form-control" placeholder="Email" style="border: none;">
                                            </div>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0; width: 100%;">
                                            <div class="input-group" style="width: 100%; border: 1px solid #ccc;">
                                                <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true" style="font-size: 20px;"></i></span>
                                                <input type="password" name="password" class="form-control" placeholder="Password" style="border: none;">
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input id="remember_me" type="checkbox" name="remember_me" value="1"> @lang('admin.pages.auth.messages.remember_me')
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row text-center">
                                            <input type="submit" class="btn  btn-primary" value="Sign in" style="width: 150px;">
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-12 control" style="padding: 0; margin-top: 15px;">
                                                <div style="border-top: 1px solid#888; padding-top:10px; font-size:12px;" >
                                                    Forgot your password!
                                                    <a href="{!! action('Admin\PasswordController@getForgotPassword') !!}">Click here</a><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
