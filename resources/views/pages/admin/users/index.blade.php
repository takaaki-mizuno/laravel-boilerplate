@extends('layouts.admin.application',['menu' => 'users'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
{{ \Config::get('site.name') }} | Admin | Admin Users
@stop

@section('header')
Users
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            {!! \PaginationHelper::render($offset, $limit, $count, $baseUrl, []) !!}
        </div>

    <div class="box-body no-padding">
        <table class="table">
            <tr>
                <th style="width: 10px">#</th>
                <th>Name</th>
                <th>Email</th>
                <th style="width: 40px"></th>
            </tr>
@foreach( $users as $user )
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                <a href="{!! URL::action('Admin\UserController@show') !!}" class="btn btn-block btn-primary">Edit</a>
                </td>
            </tr>
@endforeach
        </table>
    </div>
@stop
