@php $user = Auth::guard('web')->user(); @endphp
<section class="sidebar position-relative">
    <div class="multinav">
        <div class="multinav-scroll" style="height: 99%;">
            <!-- sidebar menu-->
            <ul class="sidebar-menu" data-widget="tree">
                @if ($user->can('dashboard.view'))
                    {{-- <li class="header fs-10 m-0 text-uppercase">Dashboard</li> --}}
                    <li class="@yield('dashboard')">
                        <a href="{{ route('dashboard') }}">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif
                
                @if ($user->can('admin.view') ||
                    $user->can('admin.create') ||
                    $user->can('admin.edit') ||
                    $user->can('admin.delete') ||
                    $user->can('role.view') ||
                    $user->can('role.create') ||
                    $user->can('role.edit') ||
                    $user->can('role.delete') ||
                    $user->can('permission.view') ||
                    $user->can('permission.create') ||
                    $user->can('permission.edit') ||
                    $user->can('permission.delete'))
                        
                    <li class="treeview">
                        <a href="#">
                            <i data-feather="users"></i>
                            <span>User Management</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if ($user->can('role.view') ||
                                    $user->can('role.create') ||
                                    $user->can('role.edit') || $user->can('role.delete'))
                                <li class="@yield('role')">
                                    <a href="{{ route('roles') }}">
                                        <i class="icon-Commit">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>Role
                                    </a>
                                </li>
                            @endif
                            @if ($user->can('permission.view') ||
                                    $user->can('permission.create') ||
                                    $user->can('permission.edit') ||
                                    $user->can('permission.delete'))
                                <li class="@yield('permission')">
                                    <a href="{{ route('permissions') }}">
                                        <i class="icon-Commit">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>Permission
                                    </a>
                                </li>
                            @endif
                            @if ($user->can('admin.view') || 
                                    $user->can('admin.create') ||
                                    $user->can('admin.edit') ||
                                    $user->can('admin.delete'))

                                <li class="@yield('admin')">
                                    <a href="{{ route('admin.index') }}">
                                        <i class="icon-Commit">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>All Admin
                                    </a>
                                </li>
                            @endif
                            
                        </ul>
                    </li>
                
                    <li class="treeview">
                        <a href="#">
                            <i data-feather="calendar"></i>
                            <span>Booking Management</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            
                                <li class="@yield('booking')">
                                    <a href="#">
                                        <i class="icon-Commit">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>All Booking Management
                                    </a>
                                </li>
                            
                                <li class="@yield('booking')">
                                    <a href="{{ route('permissions') }}">
                                        <i class="icon-Commit">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>Create Booking
                                    </a>
                                </li>  
                        </ul>
                    </li>
                    
                    @if($user->can('settings.view'))
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="settings"></i>
                                <span>Settings</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>

                            <ul class="treeview-menu">

                                @if ($user->can('settings.env_editor.view'))
                                    <li class="@yield('settings.env_editor.view')">
                                        <a href="{{ url('env-editor') }}" target="_blank">
                                            <i class="icon-Settings">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Env Editor
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                @endif
            </ul>
        </div>
    </div>
</section>
