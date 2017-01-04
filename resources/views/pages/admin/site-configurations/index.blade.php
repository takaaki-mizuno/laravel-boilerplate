@extends('layouts.admin.application',['menu' => 'site_configurations'] )

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
SiteConfigurations
@stop

@section('breadcrumb')
<li class="active">SiteConfigurations</li>
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <p class="text-right">
                <a href="{!! URL::action('Admin\SiteConfigurationController@create') !!}" class="btn btn-block btn-primary btn-sm">@lang('admin.pages.common.buttons.create')</a>
            </p>
        </h3>
        {!! \PaginationHelper::render($offset, $limit, $count, $baseUrl, []) !!}
    </div>
    <div class="box-body scroll">
        <table class="table table-bordered">
            <tr>
                <th style="width: 10px">ID</th>
                <th>@lang('admin.pages.site-configurations.columns.locale')</th>
                <th>@lang('admin.pages.site-configurations.columns.name')</th>
                <th>@lang('admin.pages.site-configurations.columns.title')</th>
                <th style="width: 40px">&nbsp;</th>
            </tr>
            @foreach( $models as $siteConfiguration )
                <tr>
                    <td>{{ $siteConfiguration->id }}</td>
                    <td>@lang(array_get(config('locale.languages.' . $siteConfiguration->locale), 'name', $siteConfiguration->locale))</td>
                    <td>{{ $siteConfiguration->name }}</td>
                    <td>{{ $siteConfiguration->title }}</td>
                    <td>
                        <a href="{!! URL::action('Admin\SiteConfigurationController@show', $siteConfiguration->id) !!}" class="btn btn-block btn-primary btn-sm">@lang('admin.pages.common.buttons.edit')</a>
                        <a href="#" class="btn btn-block btn-danger btn-sm delete-button" data-delete-url="{!! action('Admin\SiteConfigurationController@destroy', $siteConfiguration->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
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
