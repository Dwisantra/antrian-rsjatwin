<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Dashboard</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('asset/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('asset/vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/vendors/chartist/chartist.min.css') }}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('asset/images/favicon.png') }}" />

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/931792e485.js" crossorigin="anonymous"></script>
    <!-- toastr notification -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    @stack('css-internal')
    @stack('css-external')
</head>

<body>
    <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
        @include('layouts._partials.navbar')
    <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            @include('layouts._partials.sidebar')
            <!-- partial -->
            <div class="main-panel">
                @yield('content')
                    <!-- content-wrapper ends -->

                <!-- partial:partials/_footer -->
                @include('layouts._partials.footer')
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    {{-- <script src="{{ asset('asset/vendors/js/vendor.bundle.base.js') }}"></script> --}}
    <!-- endinject -->

    <!-- Plugin js for this page -->
    {{-- <script src="{{ asset('asset/vendors/chart.js/Chart.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('asset/vendors/moment/moment.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('asset/vendors/daterangepicker/daterangepicker.js') }}"></script> --}}
    {{-- <script src="{{ asset('asset/vendors/chartist/chartist.min.js') }}"></script> --}}
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    {{-- <script src="{{ asset('asset/js/off-canvas.js') }}"></script> --}}
    {{-- <script src="{{ asset('asset/js/misc.js') }}"></script> --}}
    <!-- endinject -->

    <!-- Custom js for this page -->
    {{-- <script src="{{ asset('asset/js/dashboard.js') }}"></script> --}}
    <!-- End custom js for this page -->
    <!-- Jquery -->

    @stack('javascript-internal')
    @stack('javascript-external')
</body>
</html>
