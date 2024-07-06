<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>PT. Tri Garda Buana Sakti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}">
<!-- Layout config Js -->
<script src="{{ asset('theme/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ asset('theme/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('theme/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset('theme/css/app.min.css') }}" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ asset('theme/css/custom.min.css') }}" rel="stylesheet" type="text/css" />


</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            {{ $slot }}
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                               {{ date('Y')}}  Crafted with <i class="mdi mdi-heart text-danger"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('theme/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('theme/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('theme/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('theme/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('theme/js/plugins.js') }}"></script>

    <!-- particles js -->
    <script src="{{ asset('theme/libs/particles.js/particles.js') }}"></script>
    <!-- particles app js -->
    <script src="{{ asset('theme/js/pages/particles.app.js') }}"></script>
    <!-- password-addon init -->
    <script src="{{ asset('theme/js/pages/password-addon.init.js') }}"></script>

</body>

</html>