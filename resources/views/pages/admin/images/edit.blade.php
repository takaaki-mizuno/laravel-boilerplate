@extends('layouts.admin.application', ['menu' => 'images'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
@stop

@section('header')
    Images
@stop

@section('breadcrumb')
    <li><a href="{!! action('Admin\ImageController@index') !!}"><i class="fa fa-files-o"></i> Images</a></li>
    @if( $isNew )
        <li class="active">New</li>
    @else
        <li class="active">{{ $image->id }}</li>
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

    <form
        @if( $isNew )
        action="{!! action('Admin\ImageController@store') !!}" method="POST" enctype="multipart/form-data">
        @else
            action="{!! action('Admin\ImageController@update', [$image->id]) !!}" method="POST"
            enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
        @endif
        {!! csrf_field() !!}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">

                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <img width="400" src="{!! $image->url !!}" alt="" class="margin"/>

                        <div class="form-group @if ($errors->has('url')) has-error @endif">
                            <label for="url">@lang('admin.pages.images.columns.url')</label>
                            <input type="text" class="form-control" id="url" name="url"
                                   value="{{ old('url') ? old('url') : $image->url }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('title')) has-error @endif">
                            <label for="title">@lang('admin.pages.images.columns.title')</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ old('title') ? old('title') : $image->title }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('entity_type')) has-error @endif">
                            <label for="entity_type">@lang('admin.pages.images.columns.entity_type')</label>
                            <input type="text" class="form-control" id="entity_type" name="entity_type"
                                   value="{{ old('entity_type') ? old('entity_type') : $image->entity_type }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('entity_id')) has-error @endif">
                            <label for="entity_id">@lang('admin.pages.images.columns.entity_id')</label>
                            <input type="text" class="form-control" id="entity_id" name="entity_id"
                                   value="{{ old('entity_id') ? old('entity_id') : $image->entity_id }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('file_category_type')) has-error @endif">
                            <label
                                for="file_category_type">@lang('admin.pages.images.columns.file_category_type')</label>
                            <input type="text" class="form-control" id="file_category_type"
                                   name="file_category_type"
                                   value="{{ old('file_category_type') ? old('file_category_type') : $image->file_category_type }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('s3_key')) has-error @endif">
                            <label for="s3_key">@lang('admin.pages.images.columns.s3_key')</label>
                            <input type="text" class="form-control" id="s3_key" name="s3_key"
                                   value="{{ old('s3_key') ? old('s3_key') : $image->s3_key }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('s3_bucket')) has-error @endif">
                            <label for="s3_bucket">@lang('admin.pages.images.columns.s3_bucket')</label>
                            <input type="text" class="form-control" id="s3_bucket" name="s3_bucket"
                                   value="{{ old('s3_bucket') ? old('s3_bucket') : $image->s3_bucket }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('s3_region')) has-error @endif">
                            <label for="s3_region">@lang('admin.pages.images.columns.s3_region')</label>
                            <input type="text" class="form-control" id="s3_region" name="s3_region"
                                   value="{{ old('s3_region') ? old('s3_region') : $image->s3_region }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('s3_extension')) has-error @endif">
                            <label
                                for="s3_extension">@lang('admin.pages.images.columns.s3_extension')</label>
                            <input type="text" class="form-control" id="s3_extension" name="s3_extension"
                                   value="{{ old('s3_extension') ? old('s3_extension') : $image->s3_extension }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('media_type')) has-error @endif">
                            <label for="media_type">@lang('admin.pages.images.columns.media_type')</label>
                            <input type="text" class="form-control" id="media_type" name="media_type"
                                   value="{{ old('media_type') ? old('media_type') : $image->media_type }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('format')) has-error @endif">
                            <label for="format">@lang('admin.pages.images.columns.format')</label>
                            <input type="text" class="form-control" id="format" name="format"
                                   value="{{ old('format') ? old('format') : $image->format }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('file_size')) has-error @endif">
                            <label for="file_size">@lang('admin.pages.images.columns.file_size')</label>
                            <input type="text" class="form-control" id="file_size" name="file_size"
                                   value="{{ old('file_size') ? old('file_size') : $image->file_size }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('width')) has-error @endif">
                            <label for="width">@lang('admin.pages.images.columns.width')</label>
                            <input type="text" class="form-control" id="width" name="width"
                                   value="{{ old('width') ? old('width') : $image->width }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('height')) has-error @endif">
                            <label for="height">@lang('admin.pages.images.columns.height')</label>
                            <input type="text" class="form-control" id="height" name="height"
                                   value="{{ old('height') ? old('height') : $image->height }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_enabled" value="1"
                                           @if( $image->is_enabled) checked @endif
                                    > @lang('admin.pages.images.columns.is_enabled')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit"
                        class="btn btn-primary">@lang('admin.pages.common.buttons.save')</button>
            </div>
        </div>
    </form>
@stop
