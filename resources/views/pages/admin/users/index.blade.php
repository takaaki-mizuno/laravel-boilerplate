@extends('layouts.admin.application',['menu' => 'users'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('js/sortable.js', 'admin') !!}"></script>
    <script src="{!! \URLHelper::asset('js/delete_item.js', 'admin') !!}"></script>
@stop

@section('title')
    {{ config('site.name') }} | Admin | Admin Users
@stop

@section('header')
    Users
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            {!! \PaginationHelper::render($offset, $limit, $count, $baseUrl, []) !!}
        </div>

        <div class="box-body scroll">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">ID</th>
                    <th class="sortable"
                        data-key="name">@lang('admin.pages.users.columns.name')  @if( $order=="name") @if( $direction=='asc')
                            <i class="fa fa-sort-amount-asc"></i> @else <i
                                    class="fa fa-sort-amount-desc"></i> @endif @endif</th>
                    <th>@lang('admin.pages.users.columns.email')</th>
                    <th style="width: 40px"></th>
                </tr>
                @foreach( $users as $user )
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{!! URL::action('Admin\UserController@show', [$user->id]) !!}"
                               class="btn btn-block btn-primary">@lang('admin.pages.common.buttons.edit')</a>
                            <a href="#" class="btn btn-block btn-danger btn-sm delete-button" data-delete-url="{!! action('Admin\UserController@destroy', $user->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($offset, $limit, $count, $baseUrl, []) !!}
        </div>
    </div>
@stop
