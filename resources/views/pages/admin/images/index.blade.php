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

            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title">
                        <p class="text-right">
                            <a href="{!! URL::action('Admin\ImageController@create') !!}"
                               class="btn btn-block btn-primary btn-sm"
                               style="width: 125px;">@lang('admin.pages.common.buttons.create')</a>
                        </p>
                    </h3>
                    <br>
                    <p style="display: inline-block;">@lang('admin.pages.common.label.search_results', ['count' => $count])</p>
                </div>
                <div class="col-sm-6 wrap-top-pagination">
                    <div class="heading-page-pagination">
                        {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], [], $count, 'shared.topPagination') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body" style=" overflow-x: scroll; ">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                    <th>{!! \PaginationHelper::sort('url', trans('admin.pages.images.columns.url')) !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('entity_type', trans('admin.pages.images.columns.entity_type')) !!}</th>
                    <th>{!! \PaginationHelper::sort('is_local', trans('admin.pages.images.columns.is_local')) !!}</th>
                    <th>{!! \PaginationHelper::sort('media_type', trans('admin.pages.images.columns.media_type')) !!}</th>
                    <th>{!! \PaginationHelper::sort('format', trans('admin.pages.images.columns.format')) !!}</th>
                    <th>{!! \PaginationHelper::sort('file_size', trans('admin.pages.images.columns.file_size')) !!}</th>
                    <th>{!! \PaginationHelper::sort('width', trans('admin.pages.images.columns.width')) !!}</th>
                    <th>{!! \PaginationHelper::sort('height', trans('admin.pages.images.columns.height')) !!}</th>

                    <th style="width: 40px">{!! \PaginationHelper::sort('is_enabled', trans('admin.pages.common.label.is_enabled')) !!}</th>
                    <th style="width: 40px">@lang('admin.pages.common.label.actions')</th>
                </tr>
                @foreach( $models as $model )
                    <tr>
                        <td>{{ $model->id }}</td>
                        <td>{{ $model->url }}</td>
                        <td>{{ $model->entity_type }}</td>
                        <td>{{ $model->is_local }}</td>
                        <td>{{ $model->media_type }}</td>
                        <td>{{ $model->format }}</td>
                        <td>{{ $model->file_size }}</td>
                        <td>{{ $model->width }}</td>
                        <td>{{ $model->height }}</td>

                        <td>
                            @if( $model->is_enabled )
                                <span class="badge bg-green">@lang('admin.pages.common.label.is_enabled_true')</span>
                            @else
                                <span class="badge bg-red">@lang('admin.pages.common.label.is_enabled_false')</span>
                            @endif
                        </td>
                        <td>
                            <a href="{!! URL::action('Admin\ImageController@show', $model->id) !!}"
                               class="btn btn-block btn-primary btn-xs">@lang('admin.pages.common.buttons.edit')</a>
                            <a href="#" class="btn btn-block btn-danger btn-xs delete-button"
                               data-delete-url="{!! action('Admin\ImageController@destroy', $model->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], []) !!}
        </div>
    </div>
@stop