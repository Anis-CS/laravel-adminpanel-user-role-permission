<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('admin/assets/images/favicon.ico') }}">
    <title>XYZ - Dashboard</title>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/skin_color.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom.css') }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    @yield('styles')

</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

    @include('layouts.header')

    <aside class="main-sidebar">
        <!-- sidebar-->
        @include('layouts.sidebar')
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            @php
                $route = Route::currentRouteName();
            @endphp
            @if ($route != 'dashboard')
                @include('layouts.breadcrumb')
            @endif
            <section class="content">
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer bt-1">
        &copy;
        <script>
            document.write(new Date().getFullYear())
        </script> <a href="{{ env('APP_URL') }}">xyz</a>. All Rights
        Reserved.
    </footer>
    <!-- quick_user_toggle -->
    <div class="modal modal-right fade" id="quick_user_toggle" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content slim-scroll3">
                <div class="modal-body p-30 bg-white">
                    <div class="d-flex align-items-center mb-15 justify-content-between pb-30">
                        <h4 class="m-0">User Profile
                        </h4>
                        <a href="#" class="btn btn-icon btn-danger-light btn-sm no-shadow"
                            data-bs-dismiss="modal">
                            <span class="fa fa-close"></span>
                        </a>
                    </div>
                    <div>
                        <div class="d-flex flex-row">
                            <div class="">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('admin/assets/images/profile_pic/profile_pic.png') }}"
                                    alt="user" class="rounded bg-danger-light w-150" width="100">

                            </div>
                            <div class="ps-20">
                                <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                                <p class="my-5 text-fade">{{ Auth::user()->name }}</p>
                                <a href="mailto:dummy@gmail.com">
                                    <!-- <span
                                        class="icon-Mail-notification me-5 text-success"><span
                                            class="path1"></span><span class="path2"></span>
                                        </span> -->
                                    {{ Auth::user()->email }}</a>
                                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm mt-5">
                                        <i class="ti-power-off"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider my-30"></div>
                    <div>
                        <div class="d-flex align-items-center mb-30">
                            <div class="me-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                                <span class="icon-Library fs-24"><span class="path1"></span><span
                                        class="path2"></span></span>
                            </div>
                            <div class="d-flex flex-column fw-500">
                                <a href="" class="text-dark hover-primary mb-1 fs-16">My
                                    Profile</a>
                                    {{-- {{ route('my-profile') }} --}}
                                <span class="text-fade">Account settings and more</span>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /quick_user_toggle -->

 
    <!-- ./wrapper -->


    <!-- 1. Load jQuery (Only once) -->
    <script type="text/javascript" src="{{ asset('admin/assets/js/jquery-3.6.0.min.js') }}"></script>

    <!-- 2. Load Vendor JS that depend on jQuery (Ensure these are loaded after jQuery) -->
    <script src="{{ asset('admin/assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('admin/assets/icons/feather-icons/feather.min.js') }}"></script>

    <!-- 3. Load jQuery Plugins -->
    <script src="{{ asset('admin/assets/vendor_components/jvectormap/lib2/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/jvectormap/lib2/jquery-jvectormap-world-mill-en.js') }}">
    </script>
    <script src="{{ asset('admin/assets/vendor_components/jvectormap/lib2/jquery-jvectormap-in-mill.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/jvectormap/lib2/jquery-jvectormap-us-aea-en.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/jvectormap/lib2/jquery-jvectormap-uk-mill-en.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/jvectormap/lib2/jquery-jvectormap-au-mill.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/jvectormap/lib2/jvectormap.custom.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/apexcharts-bundle/irregular-data-series.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>

    <!-- 4. Load Other JS Libraries (Those not dependent on jQuery) -->
    <script src="{{ asset('admin/assets/vendor_components/chartist-js-develop/chartist.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/Flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/Flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/Flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/Flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/morris.js/morris.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor_components/echarts/dist/echarts-en.min.js') }}"></script>

    <!-- 5. Load Select2 and DataTable -->
    {{-- <script src="{{ asset('admin/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script> --}}
    <script src="{{ asset('admin/assets/vendor_components/datatable/datatables.min.js') }}"></script>

    <!-- 6. Load Custom JS and Pages -->
    <script src="{{ asset('admin/assets/js/demo.js') }}"></script>
    <script src="{{ asset('admin/assets/js/template.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/timeline.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/data-table.js') }}"></script>

    <!-- 7. Additional Inline Scripts -->
    <script src="{{ asset('admin/assets/js/pages/widget-flot-charts.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/widget-inline-charts.js') }}"></script>

    <!-- 8. Add any other necessary custom scripts here -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%' // Ensures it adapts to parent container
            });
        });
    </script>
    <script>
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('info'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: '{{ session('info') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    </script>

    @yield('scripts')
</body>

</html>
