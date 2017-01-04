@extends('layouts.admin.application',['menu' => 'site_configurations'] )

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
    <li><a href="{!! action('Admin\SiteConfigurationController@index') !!}"><i class="fa fa-files-o"></i>
            SiteConfigurations</a></li>
    @if( $isNew )
        <li class="active">New</li>
    @else
        <li class="active">{{ $siteConfiguration->id }}</li>
    @endif
@stop

@section('content')

    <form
        @if( $isNew )
        action="{!! action('Admin\SiteConfigurationController@store') !!}" method="POST" enctype="multipart/form-data">
        @else
            action="{!! action('Admin\SiteConfigurationController@update', [$siteConfiguration->id]) !!}" method="POST"
            enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
        @endif
        {!! csrf_field() !!}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Site Configuration
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('locale')) has-error @endif">
                            <label for="locale">@lang('admin.pages.site-configurations.columns.locale')</label>
                            <select name="locale" class="form-control">
                                @foreach( config('locale.languages') as $code => $locale )
                                    <option value="{!! $code !!}"
                                            @if( (old('locale') && old('locale') == $code) || (!old('locale') && $siteConfiguration->locale == $code)) selected @endif >@lang(array_get($locale, 'name', $code))</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            <label for="name">@lang('admin.pages.site-configurations.columns.name')</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name') ? old('name') : $siteConfiguration->name }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('title')) has-error @endif">
                            <label for="title">@lang('admin.pages.site-configurations.columns.title')</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ old('title') ? old('title') : $siteConfiguration->title }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('keywords')) has-error @endif">
                            <label for="keywords">@lang('admin.pages.site-configurations.columns.keywords')</label>
                            <input type="text" class="form-control" id="keywords" name="keywords"
                                   value="{{ old('keywords') ? old('keywords') : $siteConfiguration->keywords }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label
                                for="description">@lang('admin.pages.site-configurations.columns.description')</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Description">{{ old('description') ? old('description') : $siteConfiguration->description }}</textarea>
                        </div>
                        @if( !empty($siteConfiguration->ogpImage) )
                            <img width="400" height="157"
                                 src="{!! $siteConfiguration->ogpImage->getThumbnailUrl(400, 157) !!}" alt=""
                                 class="margin"/>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputFile">OGP Image</label>
                            <input type="file" id="cover-image" name="ogp_image">
                            <p class="help-block">OGP Image ( Should be bigger than 1200 x 628 )</p>
                        </div>

                        @if( !empty($siteConfiguration->twitterCardImage) )
                            <img width="400" height="200"
                                 src="{!! $siteConfiguration->twitterCardImage->getThumbnailUrl(400, 200) !!}" alt=""
                                 class="margin"/>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputFile">Twitter Card Image</label>
                            <input type="file" id="cover-image" name="twitter_card_image">
                            <p class="help-block">Twitter Card Image ( Should be bigger than 1024 x 512 )</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">@lang('admin.pages.common.buttons.save')</button>
            </div>
        </div>
    </form>
@stop
