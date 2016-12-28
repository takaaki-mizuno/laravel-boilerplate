@extends('layouts.admin.application', ['menu' => 'articles'] )

@section('metadata')
@stop

@section('styles')
    <link rel="stylesheet" href="{!! \URLHelper::asset('libs/datetimepicker/css/bootstrap-datetimepicker.min.css', 'admin') !!}">
@stop

@section('scripts')
    <script src="{{ \URLHelper::asset('libs/moment/moment.min.js', 'admin') }}"></script>
    <script src="{{ \URLHelper::asset('libs/datetimepicker/js/bootstrap-datetimepicker.min.js', 'admin') }}"></script>
    <script>
        $('.datetime-field').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss'});

        $(document).ready(function () {
            $('#cover-image').change(function (event) {
                $('#cover-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
@stop

@section('title')
@stop

@section('header')
    Articles
@stop

@section('breadcrumb')
    <li><a href="{!! action('Admin\ArticleController@index') !!}"><i class="fa fa-files-o"></i> Articles</a></li>
    @if( $isNew )
        <li class="active">New</li>
    @else
        <li class="active">{{ $article->id }}</li>
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

    <form action="@if($isNew) {!! action('Admin\ArticleController@store') !!} @else {!! action('Admin\ArticleController@update', [$article->id]) !!} @endif"
          method="POST" enctype="multipart/form-data">
        @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
        {!! csrf_field() !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <a href="{!! URL::action('Admin\ArticleController@index') !!}"
                       class="btn btn-block btn-default btn-sm"
                       style="width: 125px;">@lang('admin.pages.common.buttons.back')</a>
                </h3>
            </div>
            <div class="box-body">
                {{--------------------------------------}}
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group text-center">
                            @if( !empty($article->coverImage) )
                                <img id="cover-image-preview" style="max-width: 500px; width: 100%;"
                                     src="{!! $article->coverImage->getThumbnailUrl(480, 300) !!}" alt="" class="margin"/>
                            @else
                                <img id="cover-image-preview" style="max-width: 500px; width: 100%;"
                                     src="{!! \URLHelper::asset('img/no_image.jpg', 'common') !!}" alt="" class="margin"/>
                            @endif
                            <input type="file" style="display: none;" id="cover-image" name="cover_image">
                            <p class="help-block" style="font-weight: bolder;">
                                @lang('admin.pages.articles.columns.cover_image_id')
                                <label for="cover-image"
                                       style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">@lang('admin.pages.common.buttons.edit')</label>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <table class="edit-user-profile">
                            <tr class="@if ($errors->has('title')) has-error @endif">
                                <td>
                                    <label for="title">@lang('admin.pages.articles.columns.title')</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') ? old('title') : $article->title }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('slug')) has-error @endif">
                                <td>
                                    <label for="slug">@lang('admin.pages.articles.columns.slug')</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') ? old('slug') : $article->slug }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('locale')) has-error @endif">
                                <td>
                                    <label for="locale">@lang('admin.pages.articles.columns.locale')</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="locale" name="locale" value="{{ old('locale') ? old('locale') : $article->locale }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('is_enabled')) has-error @endif">
                                <td>
                                    <label for="is_enabled">@lang('admin.pages.common.label.is_enabled')</label>
                                </td>
                                <td>
                                    <div class="switch" style="margin-bottom: 10px;">
                                        <input id="is_enabled" name="is_enabled" value="1" @if( $article->is_enabled) checked @endif class="cmn-toggle cmn-toggle-round-flat" type="checkbox">
                                        <label for="is_enabled"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('publish_started_at')) has-error @endif">
                                <td>
                                    <label for="publish_started_at">@lang('admin.pages.articles.columns.publish_started_at')</label>
                                </td>
                                <td>
                                    <div class="input-group date datetime-field" style="margin-bottom: 10px;">
                                        <input type="text" class="form-control" style="margin: 0;" name="publish_started_at" value="{{ old('publish_started_at') ? old('publish_started_at') : $article->publish_started_at }}">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('publish_ended_at')) has-error @endif">
                                <td>
                                    <label for="publish_ended_at">@lang('admin.pages.articles.columns.publish_ended_at')</label>
                                </td>
                                <td>
                                    <div class="input-group date datetime-field" style="margin-bottom: 10px;">
                                        <input type="text" class="form-control" style="margin: 0;" name="publish_ended_at" value="{{ old('publish_ended_at') ? old('publish_ended_at') : $article->publish_ended_at }}">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                {{--------------------------------------}}
                <div class="form-group @if ($errors->has('keywords')) has-error @endif">
                    <label for="keywords">@lang('admin.pages.articles.columns.keywords')</label>
                    <textarea name="keywords" class="form-control" rows="5"
                              placeholder="@lang('admin.pages.articles.columns.keywords')">{{ old('keywords') ? old('keywords') : $article->keywords }}</textarea>
                </div>

                <div class="form-group @if ($errors->has('description')) has-error @endif">
                    <label for="description">@lang('admin.pages.articles.columns.description')</label>
                    <textarea name="description" class="form-control" rows="5"
                              placeholder="@lang('admin.pages.articles.columns.description')">{{ old('description') ? old('description') : $article->description }}</textarea>
                </div>

                <div class="form-group @if ($errors->has('content')) has-error @endif">
                    <label for="content">@lang('admin.pages.articles.columns.content')</label>
                    <textarea name="content" class="form-control" rows="5"
                              placeholder="@lang('admin.pages.articles.columns.content')">{{ old('content') ? old('content') : $article->content }}</textarea>
                </div>






            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm"
                        style="width: 125px;">@lang('admin.pages.common.buttons.save')</button>
            </div>
        </div>
    </form>
@stop
