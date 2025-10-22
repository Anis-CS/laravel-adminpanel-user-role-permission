<!-- Inside layouts/app.blade.php -->
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h4 class="page-title">@yield('page-title', 'Dashboard')</h4>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="mdi mdi-home-outline"></i>
                            </a>
                        </li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
