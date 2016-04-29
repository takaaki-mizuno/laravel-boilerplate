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
    <li><a href="{!! \URL::action('Admin\SiteConfigurationController@index') !!}"><i class="fa fa-files-o"></i> SiteConfigurations</a></li>
    @if( $isNew )
        <li class="active">New</li>
    @else
        <li class="active">{{ $siteConfiguration->id }}</li>
    @endif
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if( $isNew )
        <form action="{!! \URL::action('Admin\SiteConfigurationController@store') !!}" method="POST" enctype="multipart/form-data">
    @else
        <form action="{!! \URL::action('Admin\SiteConfigurationController@update', [$siteConfiguration->id]) !!}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
    @endif
            {!! csrf_field() !!}
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">

                    </h3>
                </div>
                <div class="box-body">
                    <div class="form-group @if ($errors->has('title')) has-error @endif">
                        <label for="title">@lang('admin.pages.site-configurations.columns.title')</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ \Input::old('title') ? \Input::old('title') : $siteConfiguration->title }}">
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('admin.pages.site-configurations.columns.description')</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Description">{{ \Input::old('description') ? \Input::old('description') : $siteConfiguration->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_enabled" value="1"
                                       @if( $siteConfiguration->is_enabled) checked @endif
                                > @lang('admin.pages.site-configurations.columns.is_enabled')
                            </label>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">@lang('admin.pages.common.buttons.save')</button>
                </div>
            </div>
        </form>
@stop