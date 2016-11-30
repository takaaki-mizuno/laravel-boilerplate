@extends('layouts.admin.application',['menu' => 'users'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
    {{ config('site.name') }} | Admin | Admin Users | Edit
@stop

@section('header')
Edit Admin Users
@stop

@section('content')
    <form class="form-horizontal" action="{!! action('Admin\UserController@update', [$user->id]) !!}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="put">
        <div class="box-body">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" placeholder="Name" value="{{ $user->name }}">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">EMail Address</label>

                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" placeholder="Email" value="{{ $user->email }}">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" placeholder="Password"  value="">
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
