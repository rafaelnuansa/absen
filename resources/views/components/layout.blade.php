<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Tisen+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="Sistem Absensi" name="description" />
    <meta content="Absensi" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <!-- Layout config Js -->
    <script src="{{ asset('theme/js/layout.js') }}"></script>

    <!-- Bootstrap Css -->
    <link href="{{ asset('theme/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons Css -->
    <link href="{{ asset('theme/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App Css-->
    <link href="{{ asset('theme/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom Css-->
    <link href="{{ asset('theme/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <x-header></x-header>

        <x-aside></x-aside>
        <div class="main-content">
            <div class="page-content">
                {{ $slot }}
            </div>
            <x-footer></x-footer>
        </div>
    </div>

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


    <!-- JAVASCRIPT -->
    <script src="{{ asset('theme/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('theme/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('theme/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('theme/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('theme/js/plugins.js') }}"></script>


    @stack('scripts')
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    <!-- App js -->
    <script src="{{ asset('theme/js/app.js') }}"></script>

</body>

</html>
