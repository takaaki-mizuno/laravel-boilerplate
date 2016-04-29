@extends('layouts.admin.application')

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
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
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 10px">ID</th>
                <th style="width: 40px">@lang('admin.pages.site-configurations.columns.is_enabled')</th>
                <th style="width: 40px">&nbsp;</th>
            </tr>
            @foreach( $models as $model )
                <tr>
                    <td>{{ $model->id }}</td>
                    <td>
                        @if( $model->is_enabled )
                            <span class="badge bg-green">@lang('admin.pages.site-configurations.columns.is_enabled_true')</span>
                        @else
                            <span class="badge bg-red">@lang('admin.pages.site-configurations.columns.is_enabled_false')</span>
                        @endif
                    </td>
                    <td>
                        <a href="{!! URL::action('Admin\SiteConfigurationController@show', $model->id) !!}" class="btn btn-block btn-primary btn-sm">@lang('admin.pages.common.buttons.edit')</a>
                        <a href="#" class="btn btn-block btn-danger btn-sm delete-button" data-expreso-model-id="{{ $model->id }}">@lang('admin.pages.common.buttons.delete')</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="box-footer">
        @include('shared.admin.pagination')
    </div>
</div>
@stop