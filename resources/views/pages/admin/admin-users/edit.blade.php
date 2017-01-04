@extends('layouts.admin.application', ['menu' => 'admin_users'] )

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
    <form class="form-horizontal"
          @if( $isNew )
          action="{!! action('Admin\AdminUserController@store') !!}" method="POST" enctype="multipart/form-data">
        @else
            action="{!! action('Admin\AdminUserController@update', [$adminUser->id]) !!}" method="post"
            enctype="multipart/form-data">
            <input type="hidden" name="_method" value="put">
        @endif
        {!! csrf_field() !!}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ $adminUser->name }}</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                                       value="{{ old('name') ? old('name') : $adminUser->name }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">EMail Address</label>

                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email"
                                       value="{{ old('email') ? old('email') : $adminUser->email }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                            <div class="col-sm-10">
                                <input type="password" name="password" class="form-control" id="inputPassword3"
                                       placeholder="Password" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                @foreach( config('admin_user.roles') as $role => $data )
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
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Save</button>
            </div>
            <!-- /.box-footer -->
        </div>
    </form>
@stop
