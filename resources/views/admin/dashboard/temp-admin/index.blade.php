@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" /> 
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

@endsection
@section('content')
    
    @hasrole(['superadmin','admin'])
    <div class="row">
        @include('admin.dashboard.temp-admin.sections.tracking-map')
    </div>
    @endhasrole
    <div class="row">
        @hasrole(['superadmin'])
        @include('admin.dashboard.temp-admin.sections.organizations')
        @endhasrole
        @include('admin.dashboard.temp-admin.sections.general-info')
    </div>
    <div class="row">
        @include('admin.dashboard.temp-admin.sections.operations')
    </div>


    @push('after-scripts')
        <!-- apexcharts -->
        <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/echarts/echarts.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
        <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
        {{-- <script src="{{ URL::asset('build/js/app.js') }}"></script> --}}
        <script src="{{ URL::asset('build/js/chartSettings.js') }}"></script>

       
    @endpush
@endsection
