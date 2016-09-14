@extends('layouts.admin.application', ['menu' => 'images'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('js/delete_item.js', 'admin') !!}"></script>
@stop

@section('title')
@stop

@section('header')
    Images
@stop

@section('breadcrumb')
    <li class="active">Images</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
            </h3>
            {!! \PaginationHelper::render($offset, $limit, $count, $baseUrl, []) !!}
        </div>
        <div class="box-body scroll">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">ID</th>
                    <th>@lang('admin.pages.images.columns.url')</th>
                    <th>@lang('admin.pages.images.columns.entity_type')</th>
                    <th>@lang('admin.pages.images.columns.entity_id')</th>
                    <th>@lang('admin.pages.images.columns.file_category_type')</th>
                    <th>@lang('admin.pages.images.columns.media_type')</th>
                    <th>@lang('admin.pages.images.columns.format')</th>
                    <th>@lang('admin.pages.images.columns.file_size')</th>
                    <th>@lang('admin.pages.images.columns.width')</th>
                    <th>@lang('admin.pages.images.columns.height')</th>
                    <th style="width: 40px">@lang('admin.pages.images.columns.is_enabled')</th>
                    <th style="width: 40px">&nbsp;</th>
                </tr>
                @foreach( $models as $model )
                    <tr>
                        <td>{{ $model->id }}</td>
                        <td>{{ $model->url }}</td>
                        <td>{{ $model->entity_type }}</td>
                        <td>{{ $model->entity_id }}</td>
                        <td>{{ $model->file_category_type }}</td>
                        <td>{{ $model->media_type }}</td>
                        <td>{{ $model->format }}</td>
                        <td>{{ $model->file_size }}</td>
                        <td>{{ $model->width }}</td>
                        <td>{{ $model->height }}</td>
                        <td>
                            @if( $model->is_enabled )
                                <span class="badge bg-green">@lang('admin.pages.images.columns.is_enabled_true')</span>
                            @else
                                <span class="badge bg-red">@lang('admin.pages.images.columns.is_enabled_false')</span>
                            @endif
                        </td>
                        <td>
                            <a href="{!! URL::action('Admin\ImageController@show', $model->id) !!}"
                               class="btn btn-block btn-primary btn-sm">@lang('admin.pages.common.buttons.edit')</a>
                            <a href="#" class="btn btn-block btn-danger btn-sm delete-button"
                               data-delete-url="{!! action('Admin\ImageController@destroy', $model->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
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