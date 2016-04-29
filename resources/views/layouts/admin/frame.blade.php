<div class="wrapper">
@include('layouts.admin.header')
@include('layouts.admin.side_menu')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('header', 'Dashboard')
                <small>@yield('subheader', 'Dashboard')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{!! \URL::action('Admin\IndexController@index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                @yield('breadcrumb')
            </ol>
        </section>

        <section class="content">
        @if(Session::has('message-success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            {{ Session::get('message-success') }}
        </div>
        @endif
        @if(Session::has('message-failed'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Failed!</h4>
            {{ Session::get('message-failed') }}
        </div>
        @endif
        @yield('content')
        </section>
    </div>

@include('layouts.admin.footer')
@include('layouts.admin.control_side_bar')
</div>
