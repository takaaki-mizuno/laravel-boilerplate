@extends('layouts.admin.application', ['menu' => 'articles'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('js/sortable.js', 'admin') !!}"></script>
    <script src="{!! \URLHelper::asset('js/delete_item.js', 'admin') !!}"></script>
@stop

@section('title')
@stop

@section('header')
    Articles
@stop

@section('breadcrumb')
    <li class="active">Articles</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                <p class="text-right">
                    <a href="{!! URL::action('Admin\ArticleController@create') !!}" class="btn btn-block btn-primary btn-sm">@lang('admin.pages.common.buttons.create')</a>
                </p>
            </h3>
            {!! \PaginationHelper::render($offset, $limit, $count, $baseUrl, []) !!}
        </div>
        <div class="box-body scroll">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">ID</th>
                    <th>@lang('admin.pages.articles.columns.slug')</th>
                    <th>@lang('admin.pages.articles.columns.title')</th>
                    <th>@lang('admin.pages.articles.columns.locale')</th>
                    <th>@lang('admin.pages.articles.columns.publish_started_at')</th>
                    <th>@lang('admin.pages.articles.columns.publish_ended_at')</th>

                    <th style="width: 40px">@lang('admin.pages.articles.columns.is_enabled')</th>
                    <th style="width: 40px">&nbsp;</th>
                </tr>
                @foreach( $models as $model )
                    <tr>
                        <td>{{ $model->id }}</td>
                        <td>{{ $model->slug }}</td>
                        <td>{{ $model->title }}</td>
                        <td>{{ $model->locale }}</td>
                        <td>{{ $model->publish_started_at }}</td>
                        <td>{{ $model->publish_ended_at }}</td>
                        <td>
                            @if( $model->is_enabled )
                                <span class="badge bg-green">@lang('admin.pages.articles.columns.is_enabled_true')</span>
                            @else
                                <span class="badge bg-red">@lang('admin.pages.articles.columns.is_enabled_false')</span>
                            @endif
                        </td>
                        <td>
                            <a href="{!! URL::action('Admin\ArticleController@show', $model->id) !!}" class="btn btn-block btn-primary btn-sm">@lang('admin.pages.common.buttons.edit')</a>
                            <a href="#" class="btn btn-block btn-danger btn-sm delete-button" data-delete-url="{!! action('Admin\ArticleController@destroy', $model->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
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
