@extends('layouts.master')
@section('title', $organization->name .' | '. trans('translation.organization-settings'))
@push('styles')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/monolith.min.css') }}" />
    <!-- 'monolith' theme -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.organization-settings') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ trans('translation.organization-settings') }}</li>
                        <li class="breadcrumb-item ">
                            <a href="{{ route('organization.settings',$organization->id) }}">
                                {{ $organization->name }}
                            </a>
                        </li>
                        <li class="breadcrumb-item ">
                            <a href="{{ route('organizations.index') }}">
                                {{ trans('translation.organizations') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ trans('translation.home') }}</a>
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="col-lg-12">
        <div class="card">
            <!-- <div class="card-header" class="card-options-collapse">
                <h3 class="card-title">{{ trans('translation.organization-settings') }}</h3>
            </div> -->
            <div class="card-body">
                
                <!-- Content -->
                    @yield('settings-content')
                <!-- End Content -->

            </div><!-- end card-body -->

            <div class="card-footer text-center">
                <button class="btn btn-secondary px-5 mx-auto" type="button" onclick="goBack()"
                                    id="backButton">{{ trans('translation.back') }}</button>
            </div>
        </div>
        <!--end card-->
    </div>

    <!-- Modals -->
    @yield('modals')
    <!-- End Modals -->

@endsection
@push('after-scripts')
    <script>      
        function goBack() {
            location.href=localStorage.getItem('goBackOrganizationSettingsHref');
        }
    </script>
@endpush
