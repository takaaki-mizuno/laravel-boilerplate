@extends('layouts.admin.application' )

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
                <td>
                <a href="{!! URL::action('Admin\UserController@show') !!}" class="btn">Edit</a>
                </td>
                <td><span class="badge bg-red">55%</span></td>
            </tr>
@endforeach
        </table>
    </div>
@stop
