<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! app('access')->user()->picture !!}" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>{{ app('access')->user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('menus.general') }}</li>

            <!-- Optionally, you can add icons to the links -->
            <li class="{{ Active::pattern('admin/dashboard') }}"><a
                        href="{!!route('backend.dashboard')!!}"><span>{{ trans('menus.dashboard') }}</span></a></li>

            @permission('view-access-management')
            <li class="{{ Active::pattern('admin/access/*') }}"><a
                        href="{!!url('admin/access/users')!!}"><span>{{ trans('menus.access_management') }}</span></a>
            </li>
            @endauth

            <li class="{{ Active::pattern('admin/log-viewer*') }} treeview">
                <a href="#">
                    <span>{{ trans('menus.log-viewer.main') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/log-viewer*', 'menu-open') }}"
                    style="display: none; {{ Active::pattern('admin/log-viewer*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/log-viewer') }}">
                        <a href="{!! url('admin/log-viewer') !!}">{{ trans('menus.log-viewer.dashboard') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/log-viewer/logs') }}">
                        <a href="{!! url('admin/log-viewer/logs') !!}">{{ trans('menus.log-viewer.logs') }}</a>
                    </li>
                </ul>
            </li>

            @permission('edit')
            <li class="{{ Active::pattern('admin/edit*') }} treeview">
                <a href="#">
                    <span>Create / Edit</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/edit*', 'menu-open') }}"
                    style="display: none; {{ Active::pattern('admin/edit*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/edit/athlete') }}">
                        {!! link_to_route('admin.edit.athlete.select', 'Edit Athletes') !!}
                    </li>
                    <li class="{{ Active::pattern('admin/create/athlete') }}">
                        {!! link_to_route('admin.create.athlete', 'Create Athletes') !!}
                    </li>
                    <li class="{{ Active::pattern('admin/edit/team') }}">
                        {!! link_to_route('admin.edit.team.select', 'Edit Teams') !!}
                    </li>
                    <li class="{{ Active::pattern('admin/edit/stadium') }}">
                        {!! link_to_route('admin.edit.stadium.select', 'Edit Stadiums') !!}
                    </li>
                </ul>
            </li>
            @endauth
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
