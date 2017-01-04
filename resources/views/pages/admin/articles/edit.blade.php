@extends('layouts.admin.application', ['menu' => 'articles'] )

@section('metadata')
@stop

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/froala_editor.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/froala_style.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/char_counter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/code_view.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/colors.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/emoticons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/file.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/fullscreen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/image.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/image_manager.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/line_breaker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/quick_insert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/table.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/plugins/video.css">
    <link href="{{ \URLHelper::asset('libs/datetimepicker/css/bootstrap-datetimepicker.min.css', 'admin') }}" rel="stylesheet" type="text/css">
    <style>
        .froala-element {
            min-height: 500px;
            max-height: 500px;
            overflow-y: scroll;
        }
        .f-html .froala-element {
            min-height: 520px;
            max-height: 520px;
            overflow-y: scroll;
        }
    </style>
@stop

@section('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/froala_editor.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/align.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/char_counter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/code_beautifier.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/code_view.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/colors.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/emoticons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/entities.min.js"></script>
    <!--
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/file.min.js"></script>
    -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/font_family.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/font_size.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/fullscreen.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/image.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/image_manager.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/inline_style.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/line_breaker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/link.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/lists.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/paragraph_format.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/paragraph_style.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/quick_insert.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/quote.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/table.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/save.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/url.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/video.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/plugins/code_view.min.js"></script>
    <script src="{{ \URLHelper::asset('libs/moment/moment.min.js', 'admin') }}"></script>
    <script src="{{ \URLHelper::asset('libs/datetimepicker/js/bootstrap-datetimepicker.min.js', 'admin') }}"></script>
    <script>
        Boilerplate.imageUploadUrl = "{!! URL::action('Admin\ArticleController@postImage') !!}";
        Boilerplate.imageUploadParams = {
            "article_id" : "{!! empty($article->id) ? 0 : $article->id !!}",
            "_token": "{!! csrf_token() !!}"
        };
        Boilerplate.imagesLoadURL = "{!! URL::action('Admin\ArticleController@getImages') !!}";
        Boilerplate.imagesLoadParams = {
            "article_id" : "{!! empty($article->id) ? 0 : $article->id !!}"
        };
        Boilerplate.imageDeleteURL = "{!! URL::action('Admin\ArticleController@deleteImage') !!}";
        Boilerplate.imageDeleteParams = {
            "_token": "{!! csrf_token() !!}"
        };
    </script>
    <script>
        $.FroalaEditor.DEFAULTS.key = '';
    </script>
    <script src="{{ \URLHelper::asset('js/pages/articles/edit.js', 'admin') }}"></script>
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
    <form
        @if( $isNew )
        id="form-article" class="form-horizontal" action="{!! action('Admin\ArticleController@store') !!}" method="POST" enctype="multipart/form-data">
        @else
            id="form-article" class="form-horizontal" action="{!! action('Admin\ArticleController@update', [$article->id]) !!}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
        @endif
        {!! csrf_field() !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <div class="input-group @if ($errors->has('title')) has-error @endif">
                        <span class="input-group-addon"><i class="fa fa-newspaper-o"></i></span>
                        <input type="text" id="input-title" name="title" class="form-control" placeholder="Title" value="{{ old('title') ? old('title') : $article->title }}">
                    </div>
                </h3>
            </div>
            <div class="box-body">
                <div class="">
                    <div class="col-md-9">
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_info" data-toggle="tab">Info</a></li>
                                <li><a href="#tab_metainfo" data-toggle="tab">Meta Info</a></li>
                                <li><a href="#tab_contents" data-toggle="tab">Contents</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_info">
                                    <div class="form-group @if ($errors->has('slug')) has-error @endif">
                                        <label for="slug">@lang('admin.pages.articles.columns.slug')</label>
                                        <input type="text" name="slug" class="form-control" placeholder="Slug"  value="{{ old('slug') ? old('slug') : $article->slug }}">
                                    </div>
                                    <div class="form-group @if ($errors->has('locale')) has-error @endif">
                                        <label for="locale">@lang('admin.pages.articles.columns.locale')</label>
                                        <select name="locale" id="locale" class="form-control">
                                            @foreach( config('locale.languages') as $code => $locale )
                                                <option value="{!! $code !!}" @if( (old('locale') && old('locale') == $code) || (!old('locale') && $article->locale == $code)) selected @endif >{{ trans(array_get($locale, 'name', $code)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_metainfo">
                                    @if( !empty($article->coverImage) )
                                        <img width="400" src="{!! $article->coverImage->getThumbnailUrl(480, 300) !!}" alt="" class="margin" />
                                    @endif
                                    <div class="form-group">
                                        <label for="cover_image">Cover Image</label>
                                        <input type="file" id="cover-image" name="cover_image">
                                        <p class="help-block">Cover Image ( Should be bigger than 1440x900 )</p>
                                    </div>
                                    <div class="form-group @if ($errors->has('keywords')) has-error @endif">
                                        <label for="keywords">@lang('admin.pages.articles.columns.keywords')</label>
                                        <input type="text" name="keywords" class="form-control" placeholder="Keywords" value="{{ old('keywords') ? old('keywords') : $article->keywords }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">@lang('admin.pages.articles.columns.description')</label>
                                        <textarea name="description" class="form-control" rows="5" placeholder="Description">{{ old('description') ? old('description') : $article->description }}</textarea>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_contents">
                                    <div class="form-group">
                                        <section id="editor">
                                            <div id='edit'>
                                            </div>
                                        </section>
                                        <input type="hidden" name="content" id="input-content" value="{{ old('content') ? old('content') : $article->content }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="#" class="btn btn-block btn-primary" id="button-preview">@lang('admin.pages.common.buttons.preview')</a>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_enabled" value="1"
                                           @if( $article->is_enabled) checked @endif
                                    > @lang('admin.pages.articles.columns.is_enabled')
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="publish_started_at">@lang('admin.pages.articles.columns.publish_started_at')</label>
                            <div class='input-group date' id='input-publish-started-at'>
                                <input type='text' class="form-control" name="publish_started_at"
                                       value="{{ old('publish_started_at') ? old('publish_started_at') : \DateTimeHelper::formatDateTime($article->publish_started_at) }}">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="publish_ended_at">@lang('admin.pages.articles.columns.publish_ended_at')</label>
                            <div class='input-group date' id='input-publish-ended-at'>
                                <input type='text' class="form-control" name="publish_ended_at"
                                       value="{{ old('publish_ended_at') ? old('publish_ended_at') : !empty($article->publish_ended_at) ? \DateTimeHelper::formatDateTime($article->publish_ended_at) : '' }}">
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="form-group">
                    <a href="#" class="btn btn-block btn-primary" id="button-save">@lang('admin.pages.common.buttons.save')</a>
                </div>
            </div>
        </div>
    </form>
    <form id="form-preview" action="{!! action('Admin\ArticleController@preview') !!}" method="POST" enctype="multipart/form-data" target="_blank">
        {!! csrf_field() !!}
        <input type="hidden" name="title" id="preview-title">
        <input type="hidden" name="content" id="preview-content">
        <input type="hidden" name="locale" id="preview-locale">
    </form>

@stop
