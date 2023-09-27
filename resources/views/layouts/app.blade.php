<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>

    <meta charset="utf-8" />
    <title>Gamatara Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Layout config Js -->
    <script src="{{asset('assets/assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('assets/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
     <!-- custom Css-->
     <link href="{{asset('assets/assets/css/custom.css')}}" rel="stylesheet" type="text/css" />

</head>
<body>
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bgs" id="auth-particles">
            <div class="bg-overlay"></div>

        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            @yield('content')
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <!-- <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>document.write(new Date().getFullYear())</script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer> -->
        <!-- end Footer -->
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{asset('assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('assets/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('assets/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('assets/assets/js/plugins.js')}}"></script>

    <!-- particles js -->
    <script src="{{asset('assets/assets/libs/particles.js/particles.js')}}"></script>
    <!-- particles app js -->
    <script src="{{asset('assets/assets/js/pages/particles.app.js')}}"></script>
    <!-- password-addon init -->
    <script src="{{asset('assets/assets/js/pages/password-addon.init.js')}}"></script>
</body>
</html>
