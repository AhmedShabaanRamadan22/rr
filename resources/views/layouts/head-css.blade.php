@yield('css')
<!-- Fonts css load -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link id="fontsLink" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link id="fontsLink" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Layout config Js -->
<script src="{{ URL::asset('build/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link rel="stylesheet" href="{{ URL::asset('build/css/bootstrap.min.rtl.css') }}" type="text/css" />
<!-- Icons Css -->
<link rel="stylesheet" href="{{ URL::asset('build/css/icons.min.css') }}" type="text/css" />
<!-- App Css-->
<link rel="stylesheet" href="{{ URL::asset('build/css/app.min.rtl.css') }}" type="text/css" />
<!-- custom Css-->
<link rel="stylesheet" href="{{ URL::asset('build/css/custom.min.css') }}" type="text/css" />

<link rel="stylesheet" href="{{ URL::asset('build/libs/datatables/datatables.min.css') }}" type="text/css" />
<style>
    .bootstrap-select .dropdown-toggle .filter-option-inner-inner {
        text-align: right;
    }
    .dataTables_filter  {
        text-align: left !important; 
    }
</style>
@stack('styles')
