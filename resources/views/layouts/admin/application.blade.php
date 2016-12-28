<!DOCTYPE html>
<html>
<head>
    <!-------------------------------- Begin: Meta ----------------------------------->
    @include('layouts.admin.meta')
    @yield('metadata')
    <!-------------------------------- End: Meta ----------------------------------->

    <!-------------------------------- Begin: stylesheet ----------------------------------->
    @include('layouts.admin.styles')
    @yield('styles')
    <!-------------------------------- End: stylesheet ----------------------------------->

</head>
<body class="{!! isset($bodyClasses) ? $bodyClasses : 'hold-transition skin-blue sidebar-mini' !!}">
@if( isset($noFrame) && $noFrame == true )
    @yield('content')
@else
    <div class="wrapper">
        <!-------------------------------- Begin: Header ----------------------------------->
        @include('layouts.admin.header')
                <!-------------------------------- End: Header ----------------------------------->

        <!-------------------------------- Begin: Left Navigation ----------------------------------->
        @include('layouts.admin.left_navigation')
                <!-------------------------------- End: Left Navigation ----------------------------------->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    @yield('header', 'Dashboard')
                </h1>
                <ul class="breadcrumb">
                    <li><a href="{!! action('Admin\IndexController@index') !!}"><i class="fa fa-dashboard"></i> Home</a></li>
                    @yield('breadcrumb')
                </ul>
            </section>

            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
            <!-- /.content -->
        </div>

        <!-------------------------------- Begin: Footer ----------------------------------->
        @include('layouts.admin.footer')
                <!-------------------------------- End: Footer ----------------------------------->
    </div>
@endif

<!-------------------------------- Begin: Script ----------------------------------->
@include('layouts.admin.scripts')
@yield('scripts')
<!-------------------------------- End: Script ----------------------------------->
</body>
</html>
