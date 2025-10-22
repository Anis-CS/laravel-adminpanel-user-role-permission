<header class="main-header">
    <div class="d-flex align-items-center logo-box justify-content-start">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
            <!-- logo-->
            <div class="logo-mini w-160 me-2">
                <span class="light-logo">
                    <img src="{{ asset('admin/assets/images/login_admin/vms_logo_black.svg') }}" alt="logo">
                </span>
                <span class="dark-logo">
                    <img src="{{ asset('admin/assets/images/login_admin/vms_logo_white.svg') }}" alt="logo">
                </span>
            </div>
            {{-- <div class="logo-lg me-2">
                <span class="light-logo">
                    <img src="{{ asset('admin/assets/images/login_admin/vms_logo.png') }}" alt="logo">
                </span> 
                <span class="dark-logo">
                    <img src="{{ asset('admin/assets/images/login_admin/vms_logo.png') }}" alt="logo">
                </span>
            </div> --}}
        </a>
    </div>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="app-menu">
            <ul class="header-megamenu nav">
                <li class="btn-group nav-item">
                    <a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light"
                        data-toggle="push-menu" role="button">
                        <i data-feather="menu"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="navbar-custom-menu r-side">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu btn-group">
                    <label class="switch">
                        <a class="waves-effect waves-light btn-primary-light svg-bt-icon">
                            <input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin">
                            <span class="switch-on"><i data-feather="moon"></i></span>
                            <span class="switch-off"><i data-feather="sun"></i></span>
                        </a>
                    </label>
                </li>
                <li class="dropdown notifications-menu btn-group ">
                    <a href="#" class="waves-effect waves-light btn-primary-light svg-bt-icon"
                        data-bs-toggle="dropdown" title="Notifications">
                        <i data-feather="bell"></i>
                        <div class="pulse-wave"></div>
                    </a>
                    <ul class="dropdown-menu animated bounceIn">
                        <li class="header">
                            <div class="p-20">
                                <div class="flexbox">
                                    <div>
                                        <h4 class="mb-0 mt-0">Notifications</h4>
                                    </div>
                                    <div>
                                        <a href="#" class="text-danger">Clear All</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu sm-scrol">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-info"></i> Curabitur id eros quis nunc
                                        suscipit blandit.
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-warning text-warning"></i> Duis malesuada justo eu
                                        sapien elementum, in semper diam posuere.
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-danger"></i> Donec at nisi sit amet tortor
                                        commodo porttitor pretium a erat.
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-shopping-cart text-success"></i> In gravida mauris et
                                        nisi
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-danger"></i> Praesent eu lacus in libero
                                        dictum fermentum.
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-primary"></i> Nunc fringilla lorem
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-success"></i> Nullam euismod dolor ut quam
                                        interdum, at scelerisque ipsum imperdiet.
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="component_notification.html">View all</a>
                        </li>
                    </ul>
                </li>

                <li class="btn-group nav-item d-xl-inline-flex d-none">
                    <a href="#" data-provide="fullscreen"
                        class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon" title="Full Screen">
                        <i data-feather="maximize"></i>
                    </a>
                </li>

                <!-- User Account-->
                <li class="dropdown user user-menu">
                    <a href="#"
                        class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow"
                        title="User" data-bs-toggle="modal" data-bs-target="#quick_user_toggle">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('admin/assets/images/profile_pic/profile_pic.png') }}"
                            class="avatar rounded-circle bg-primary-light h-40 w-40" alt="" />
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</header>
