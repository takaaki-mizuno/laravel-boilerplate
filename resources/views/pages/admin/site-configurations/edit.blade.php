@extends('layouts.admin.application', ['menu' => 'site_configurations'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{{ \URLHelper::asset('libs/moment/moment.min.js', 'admin') }}"></script>
    <script src="{{ \URLHelper::asset('libs/datetimepicker/js/bootstrap-datetimepicker.min.js', 'admin') }}"></script>
    <script>
        $('.datetime-field').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss'});

        $(document).ready(function () {
            $('#OGP-image').change(function (event) {
                $('#OGP-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });

            $('#twitter_card_image').change(function (event) {
                $('#twitter_card_image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
@stop

@section('title')
@stop

@section('header')
    SiteConfigurations
@stop

@section('breadcrumb')
    <li><a href="{!! action('Admin\SiteConfigurationController@index') !!}"><i class="fa fa-files-o"></i>
            SiteConfigurations</a></li>
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

    <form action="@if($isNew) {!! action('Admin\SiteConfigurationController@store') !!} @else {!! action('Admin\SiteConfigurationController@update', [$siteConfiguration->id]) !!} @endif"
          method="POST" enctype="multipart/form-data">
        @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
        {!! csrf_field() !!}

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <a href="{!! URL::action('Admin\SiteConfigurationController@index') !!}" style="width: 125px; font-size: 14px;" class="btn btn-block btn-default btn-sm">@lang('admin.pages.common.buttons.back')</a>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('name')) has-error @endif">
                                <label for="name">@lang('admin.pages.site-configurations.columns.name')</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ? old('name') : $siteConfiguration->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('locale')) has-error @endif">
                                <label for="locale">@lang('admin.pages.site-configurations.columns.locale')</label>
                                <select name="locale" class="form-control">
                                    @foreach( config('locale.languages') as $code => $locale )
                                        <option value="{!! $code !!}" @if( (old('locale') && old('locale') == $code) || (!old('locale') && $siteConfiguration->locale == $code)) selected @endif >@lang(array_get($locale, 'name', $code))</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('title')) has-error @endif">
                                <label for="title">@lang('admin.pages.site-configurations.columns.title')</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') ? old('title') : $siteConfiguration->title }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('keywords')) has-error @endif">
                                <label for="keywords">@lang('admin.pages.site-configurations.columns.keywords')</label>
                                <input type="text" class="form-control" id="keywords" name="keywords" value="{{ old('keywords') ? old('keywords') : $siteConfiguration->keywords }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">@lang('admin.pages.site-configurations.columns.description')</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Description">{{ old('description') ? old('description') : $siteConfiguration->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            @if( !empty($siteConfiguration->ogpImage) )
                                <img id="OGP-image-preview" style="max-width: 500px; width: 100%;" src="{!! $siteConfiguration->ogpImage->getThumbnailUrl(400, 157) !!}" alt="" class="margin" />
                            @else
                                <img id="OGP-image-preview" style="max-width: 500px; width: 100%;" src="{!! \URLHelper::asset('img/no_image.jpg', 'common') !!}" alt="" class="margin" />
                            @endif

                            <input type="file" style="display: none;"  id="OGP-image" name="ogp_image">
                            <p class="help-block text-center" style="font-weight: bolder;">
                                @lang('admin.pages.site-configurations.columns.ogp_image_id')
                                <label for="OGP-image" style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">@lang('admin.pages.common.buttons.edit')</label>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            @if( !empty($siteConfiguration->twitterCardImage) )
                                <img id="twitter_card_image-preview" style="max-width: 500px; width: 100%;" src="{!! $siteConfiguration->twitterCardImage->getThumbnailUrl(400, 157) !!}" alt="" class="margin" />
                            @else
                                <img id="twitter_card_image-preview" style="max-width: 500px; width: 100%;" src="{!! \URLHelper::asset('img/no_image.jpg', 'common') !!}" alt="" class="margin" />
                            @endif

                            <input type="file" style="display: none;"  id="twitter_card_image" name="twitter_card_image">
                            <p class="help-block text-center" style="font-weight: bolder;">
                                @lang('admin.pages.site-configurations.columns.twitter_card_image_id')
                                <label for="twitter_card_image" style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">@lang('admin.pages.common.buttons.edit')</label>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" style="width: 125px; font-size: 14px;" class="btn btn-primary">@lang('admin.pages.common.buttons.save')</button>
                </div>
            </div>
    </form>
@stop
