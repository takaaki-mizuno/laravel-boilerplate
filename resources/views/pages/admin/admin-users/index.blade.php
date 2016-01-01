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
Admin Users
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
            @foreach( $adminUsers as $adminUser )
                <tr>
                    <td>{{ $adminUser->id }}</td>
                    <td>{{ $adminUser->name }}</td>
                    <td>{{ $adminUser->email }}</td>
                    <td>
                        <a href="{!! URL::action('Admin\AdminUserController@show', [$adminUser->id]) !!}" class="btn">Edit</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
