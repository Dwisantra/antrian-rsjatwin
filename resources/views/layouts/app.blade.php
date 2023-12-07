<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{{ config('app.name')}}</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="{{ asset('asset/vendors/simple-line-icons/css/simple-line-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/vendors/flag-icon-css/css/flag-icon.min.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/vendors/css/vendor.bundle.base.css') }}">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <!-- endinject -->
        <!-- Layout styles -->
        <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}" />
        <!-- End layout styles -->
        <link rel="shortcut icon" href="{{ asset('asset/images/3302063.png') }}" />
        <!-- Toastr Notification CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>
    <body>
        <main>
            @yield('content')
        </main>
        <!-- container-scroller -->

        <!-- plugins:js -->
        <script src="{{ asset('asset/vendors/js/vendor.bundle.base.js') }}"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="{{ asset('asset/js/off-canvas.js') }}"></script>
        <script src="{{ asset('asset/js/misc.js') }}"></script>
        <!-- endinject -->
        <!-- Fontawesome -->
        <script src="https://kit.fontawesome.com/931792e485.js" crossorigin="anonymous"></script>
        <!-- toastr notification -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        @stack('javascript-internal')
        @stack('javascript-external')
    </body>
</html>
