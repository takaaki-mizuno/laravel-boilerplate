@extends('layouts.admin.application' )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
    {{ \Config::get('site.name') }} | Admin | Admin Users | Edit
@stop

@section('header')
Edit Admin Users
@stop

@section('content')
    <form class="form-horizontal" action="{!! \URL::action('Admin\AdminUserController@update', [$adminUser->id]) !!}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="put">
        <div class="box-body">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" placeholder="Name" value="{{ $adminUser->name }}">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">EMail Address</label>

                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" placeholder="Email" value="{{ $adminUser->email }}">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" placeholder="Password"  value="">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
@foreach( \Config::get('admin_user.roles') as $role => $data )
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="role[]" value="{{ $role }}"
                               @if( $adminUser->hasRole($role, false)) checked @endif
                        > @lang($data['name'])
                    </label>
                </div>
@endforeach
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
