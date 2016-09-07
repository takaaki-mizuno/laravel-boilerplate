@extends('layouts.admin.application',['menu' => 'dashboard'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
    {{ \Config::get('site.name') }} | Admin | Update Me
@stop

@section('header')
    Me
@stop

@section('breadcrumb')
    <li class="active">Me</li>
@stop

@section('content')
    <form class="form-horizontal" action="{!! action('Admin\MeController@update') !!}" method="post">
        <input type="hidden" name="_method" value="put">
        {!! csrf_field() !!}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Edit Your Information</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                               value="{{ $authUser->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">EMail Address</label>

                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email"
                               value="{{ $authUser->email }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="inputPassword3"
                               placeholder="Password" value="">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Save</button>
            </div>
        </div>
    </form>
@stop
