<!doctype html>

@php
    $langDirection = __('dashboard.direction');
@endphp



<html lang="{{ app()->getLocale() }}" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="{{ $langDirection }}" data-skin="default"
    data-assets-path="{{ admin_asset('layout/') }}" data-template="vertical-menu-template-starter" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ $title ?? 'Ymtaz Dashboard' }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.3.2/css/flag-icons.min.css" integrity="sha512-+WVTaUIzUw5LFzqIqXOT3JVAc5SrMuvHm230I9QAZa6s+QRk8NDPswbHo2miIZj3yiFyV9lAgzO1wVrjdoO4tw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Page CSS -->


    <!-- Icon Fonts -->
    <link rel="stylesheet" href="{{ admin_asset('layout/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ admin_asset('layout/css/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ admin_asset('layout/css/pickr-themes.css') }}" />
    <link rel="stylesheet" href="{{ admin_asset('layout/css/core.css') }}" />
    <link rel="stylesheet" href="{{ admin_asset('layout/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ admin_asset('layout/css/perfect-scrollbar.css') }}" />

    <!-- Helpers -->
    <script defer src="{{ admin_asset('layout/js/helpers.js') }}"></script>

    <!-- Template customizer: To hide customizer set displayCustomizer value false in config.js -->
    <script defer src="{{ admin_asset('layout/js/template-customizer.js') }}"></script>

    <!-- Config: Mandatory theme config file with global vars & default theme options -->
    <script defer src="{{ admin_asset('layout/js/config.js') }}"></script>
    @stack('Dashboard-styles') 
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">


            <!-- sidebar -->
            <x-common.sidebar>
            </x-common.sidebar>


            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <x-common.nav></x-common.nav>


                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                      
                              {{ $slot }}
                    </div>
                    <!-- / Content -->

                   <!--footer-->
                   <x-common.footer></x-common.footer>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->


    <script  src="{{ admin_asset('layout/js/jquery.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/popper.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/bootstrap.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/node-waves.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/autocomplete-js.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/pickr.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/perfect-scrollbar.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/hammer.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/menu.js') }}"></script>
    <script defer src="{{ admin_asset('layout/js/main.js') }}"></script>



 @stack('Dashboard-scripts') 
    <!-- Page JS -->
</body>

</html>
