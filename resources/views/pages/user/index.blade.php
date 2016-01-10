@extends('layouts.user.application')

@section('metadata')
@stop

@section('styles')
@parent
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<style>
    html, body {
        height: 100%;
    }

    body {
        margin: 0;
        padding: 0;
        width: 100%;
        display: table;
        font-weight: 100;
        font-family: 'Lato';
    }

    .container {
        text-align: center;
        display: table-cell;
        vertical-align: middle;
    }

    .content {
        text-align: center;
        display: inline-block;
    }

    .title {
        font-size: 96px;
    }
</style>
@stop

@section('title')
@stop

@section('scripts')
@parent
@stop

@section('content')
<div class="container">
    <p class="content">
        <div class="title">Laravel Boilerplate</div>
        <p><a href="https://github.com/takaaki-mizuno/laravel-boilerplate/">GitHub</a></p>
    </div>
</div>
@stop
