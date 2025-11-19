@extends('layouts.master')
@section('title', __('Organization'))
@push('styles')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/monolith.min.css') }}" />
    <!-- 'monolith' theme -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .icon-bigger {
            font-size: 25px;
        }

        /* Ensure hover effect applies to the entire card */
        .custom-card {
            min-height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-card:hover {
            transform: scale(1.05);
            /* box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2); */
        }

        /* Icon and title initial styling */
        .card-icon {
            font-size: 2rem;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        /* Hide icon and title on hover */
        .custom-card:hover .card-icon {
            opacity: 0;
            transform: translateY(-20px);
        }

        /* Description styling - hidden initially */
        .card-description {
            opacity: 0;
            transform: translateY(50px); /* Start below the visible area */
            transition: opacity 0.3s ease, transform 0.3s ease;
            /* color: #666; */
            color: #fff;
            font-size: 0.7rem;
            padding: 10px;
            height: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            position: absolute;
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%) translateY(20px); /* Centered, starting below */
            border-radius: 4px;
        }

        /* Show description on hover */
        .custom-card:hover .card-description {
            opacity: 1;
            transform: translate(-50%, -50%) translateY(0); /* Fully centered on hover */
        }

    </style>
@endpush
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ $organization->name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ $organization->name }}</li>
                        <li class="breadcrumb-item \"><a href="{{ route('organizations.index') }}">
                            {{ trans('translation.organizations') }}</a></li>
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
            <div class="card-header" class="card-options-collapse">
                <h3 class="card-title text-center">{{ trans('translation.organization-settings') }}</h3>
                <p class="text-center">
                    الاعدادات الخاصة بالمنظمة والعمليات التشغيلية المتعلقة بها
                </p>
            </div>
            <div class="card-body">
                
                <div class="row g-4 mb-4">
                    @foreach ([
                        [ 'name'=> 'information', 'icon' => 'ri-profile-line','link' => route('organization.show-via',[$organization->id,'information'])],
                        [ 'name'=> 'services', 'icon' => 'ri-service-line','link' => route('organization.show-via',[$organization->id,'services'])],
                        [ 'name'=> 'reason-dangers', 'icon' => 'ri-alert-line','link' => route('organization.show-via',[$organization->id,'reasonDangers'])],
                        [ 'name'=> 'fines-bank', 'icon' => 'ri-bank-line','link' => route('organization.show-via',[$organization->id,'finesBank'])],
                        [ 'name'=> 'operations', 'icon' => 'ri-customer-service-2-line','link' => route('organization.show-via',[$organization->id,'operations'])],
                        [ 'name'=> 'categories', 'icon' => 'ri-checkbox-blank-circle-line','link' => route('organization.show-via',[$organization->id,'categories'])],
                        [ 'name'=> 'sector-setup', 'icon' => 'ri-grid-line','link' => route('organization.show-via',[$organization->id,'sectorSetup'])],
                        [ 'name'=> 'countries', 'icon' => 'ri-flag-line','link' => route('organization.show-via',[$organization->id,'countries'])],
                        [ 'name'=> 'meal-preparation', 'icon' => 'ri-restaurant-line','link' => route('organization.show-via',[$organization->id,'mealPreparation'])],
                        [ 'name'=> 'question-bank', 'icon' => 'mdi mdi-office-building-marker-outline','link' => route('organization.show-via',[$organization->id,'questionBank'])],
                        [ 'name'=> 'contract', 'icon' => 'ri-hand-coin-line','link' => route('organization.show-via',[$organization->id,'contract'])],
                        [ 'name'=> 'providors', 'icon' => 'ri-shield-line','link' => route('organization.show-via',[$organization->id,'providors'])],
                        [ 'name'=> 'assist-questions', 'icon' => 'ri-shield-line','link' => route('organization.show-via',[$organization->id,'assistQuestions'])],
                    ] as $setting)
                        
                        <!-- Card 1 -->
                        <div class="col-sm-3">
                            <a class="btn btn-outline-primary d-block align-middle-items p-0" onclick="return setLoading(true);" href="{{$setting['link']}}">
                                <div class="custom-card">
                                    <!-- <div class="card-content"> -->
                                        <div class="card-icon icon-bigger">
                                            <i class="{{$setting['icon']}}"></i>
                                            <h5 class="card-title">{{trans('translation.'.$setting['name'])}}</h5>
                                        </div>
                                        <div class="card-description">
                                            {{trans('translation.'.$setting['name'])}} {{trans('translation.'.$setting['name'])}} {{trans('translation.'.$setting['name'])}} {{trans('translation.'.$setting['name'])}}
                                        </div>
                                    <!-- </div> -->
                                </div>
                            </a>
                        </div>


                    @endforeach
                </div>
            </div><!-- end card-body -->
            <div class="card-footer text-center">
                <button class="btn btn-secondary px-5 mx-auto" type="button" onclick="goBack()"
                                    id="backButton">{{ trans('translation.back') }}</button>
            </div>
        </div>
        <!--end card-->
    </div>

@endsection
@push('after-scripts')
    <script>
        localStorage.setItem('goBackOrganizationSettingsHref',location.href);

    function goBack() {
        location.href=localStorage.getItem('goBackHrefOrganizations');
    }
    </script>
@endpush
