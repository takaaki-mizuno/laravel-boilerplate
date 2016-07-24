<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! $authUser->getProfileImageUrl() !!}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{!! $authUser->name !!}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>

        <ul class="sidebar-menu">
            <li class="header">HEADER</li>
            <li @if( $menu=='dashboard') class="active" @endif ><a href="#"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER) )
            <li @if( $menu=='admin_users') class="active" @endif ><a href="{!! URL::action('Admin\AdminUserController@index') !!}"><i class="fa fa-user-secret"></i> <span>Admin Users</span></a></li>
            <li @if( $menu=='users') class="active" @endif ><a href="{!! URL::action('Admin\UserController@index') !!}"><i class="fa fa-users"></i> <span>Users</span></a></li>
            <li @if( $menu=='site_configurations') class="active" @endif ><a href="{!! URL::action('Admin\SiteConfigurationController@index') !!}"><i class="fa fa-cogs"></i> <span>Site Configurations</span></a></li>
            @endif
            <li @if( $menu=='article') class="active" @endif ><a href="{!! URL::action('Admin\ArticleController@index') !!}"><i class="fa fa-file-word-o"></i> <span>Articles</span></a></li>
            <!-- %%SIDEMENU%% -->
        </ul>
    </section>
</aside>