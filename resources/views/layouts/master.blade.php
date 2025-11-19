<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-layout="{{ auth()->user()->hasRole(['superadmin', 'admin'])? 'vertical': 'horizontal' }}" data-sidebar-size="lg"
    data-preloader="disable" data-theme="default" data-bs-theme="light" data-topbar="light" data-sidebar="light"
    dir="rtl">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | منصة ركايا لجودة التشغيل </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="منصة ركايا لجودة التشغيل" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('layouts.head-css')
    <style>
        body {}



        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* background: url({{ URL::asset('build/images/bg/1.jpeg') }}) no-repeat center center fixed; */
            background-size: cover;
            opacity: 0.5;
            /* Adjust the opacity as needed */
           
        }

        .content {
            position: relative;
            z-index: 1;
            /* Ensures content is above the background */
            color: white;
            /* Assuming you need high contrast text */
            text-align: center;
            padding-top: 50px;
            /* Adds some spacing from the top */
        }

        .loadingModal {
            z-index: 100000;
            backdrop-filter: blur(0px);
            width: 100vw;
            height: 100%; /* 100vh ; */
        }

        /* IMPORTANT NOTE !!!!! */
        /* IF YOU WISH TO CHANGE DURATION DONT FORGET TO CHANGE THE SETTIMEOUT DURATION TOO FROM vendor-scripts setLoading() */
        .blurLoadingModal {
            animation: blurBgAnimation 0.5s ease forwards;
        }

        .unBlurLoadingModal {
            animation: unBlurBgAnimation 0.5s ease forwards;
        }

        @keyframes blurBgAnimation {
            0% {

                background-color: rgba(0, 0, 0, 0);
                backdrop-filter: blur(0px);
            }

            100% {
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
            }
        }

        @keyframes unBlurBgAnimation {
            0% {
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
            }

            100% {
                backdrop-filter: blur(0px);
                background-color: rgba(0, 0, 0, 0.0);
            }
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column position-absolute top-0 left-0 loadingModal justify-content-center align-items-center d-none"
        id="loading-modal" style="z-index:100000;">
        <div class="spinner-border text-primary" role="status" id="loading-content">
            <!-- Spinner content here -->
        </div>
        <div class="mt-2">
            <h6 class="text-light">{{ trans('translation.wait_loading') }}</h6>
        </div> <!-- Text directly under spinner -->
    </div>

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')

        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @hasrole(['superadmin', 'admin'])
        @include('layouts.customizer')
    @endhasrole

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
