@extends('layouts.master')
@section('title', __('Organization'))
@push('styles')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.organizations') }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ trans('translation.organizations') }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ trans('translation.home') }}</a>
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12  col-xl-12">
            <div class="card ">
                <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                    <div class="row">
                        <h3 class="card-title col-md-6 col-5">{{ trans('translation.all-organizations') }} </h3>
                        {{-- <div class="card-options">
                            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fe fe-chevron-up"></i></a>
                        </div> --}}
                        <div class="col-7 col-md-6 text-end">
                            <div class="d-flex gap-2 text-end justify-content-end">
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addorganizations"><i
                                        class="mdi mdi-account-group-outline align-baseline me-1"></i>
                                    {{ trans('translation.add-new-organization') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-cols-xl-3 row-cols-lg-2 row-cols-md-1">
                        @foreach ($organizations as $organization)
                            <div class=" organizarionCard mb-4" >
                                <div class="card h-100">
                                    <div class="card-header bg-light" title="{{ $organization->domain }}">
                                        <div class="row mb-3">
                                            {{-- <div class="justify-content-center">
                                                    <div class="card overflow-hidden">
                                                        <div>
                                                            <img id="organization-backgroundImage"
                                                                src="{{ $organization->background_image ?? URL::asset('build/images/users/32/background_image.png') }}"
                                                                alt="" class="card-img-top profile-wid-img object-fit-cover"
                                                                style="height: 200px;">
                                                        </div>
                                                        <div class="card-body pt-0 mt-n5">
                                                            <div class="text-center">
                                                                <div class="profile-user position-relative d-inline-block mx-auto">
                                                                    <img id="organization-img"
                                                                        src="{{ $organization->logo ?? URL::asset('build/images/users/32/logo.png') }}"
                                                                        alt=""
                                                                        class="avatar-lg rounded-circle object-fit-cover border-0 img-thumbnail user-profile-image">

                                                                </div>
                                                                <div class="mt-3"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="col-lg-4 col-md-6 text-center">
                                                    {{-- <a class="btn btn-primary btn-sm addservicesBtn"
                                                            data-organization-id="{{ $organization->id }}" data-bs-target="#addservices"
                                                            data-services="{{ $organization->services->pluck('id')->implode(',') }}"
                                                            data-bs-toggle="modal" href="javascript:;">{{ trans('translation.choose-service') }}
                                                        </a> --}}
                                                    <div class="profile-user position-relative d-inline-block mx-auto">
                                                        <img id="organization-img"
                                                            src="{{ $organization->logo ?? URL::asset('build/images/users/32/logo.png') }}"
                                                            alt=""
                                                            class="avatar-lg rounded-circle object-fit-cover border-0 img-thumbnail user-profile-image">
    
                                                    </div>
                                                </div>
                                            <div class="col-lg-8 col-md-6 d-flex ">
                                                <div class="card-title align-self-end">
                                                    <p>
                                                        {{ $organization->name_ar }}
                                                        <br>
                                                        <span><small><a target="_blank"
                                                                    href="{{ $organization->domain }}">{{ $organization->domain }}</a></small></span>
                                                    </p>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body overflow-scroll" style="max-height: 7rem">
                                        <!-- ROW-1 -->
                                        <div class="row row-cols-2 row-cols-md-3">
                                            @forelse ($organization->services->unique() as $service)
                                                @include('admin.organizations.components.organization-card')
                                            @empty
                                                <div class="text-center">
                                                    <p>
                                                        {{ trans('translation.not_found') }}
                                                    </p>
                                                </div>
                                            @endforelse
                                        </div>
                                        <!-- END ROW-1 -->
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-info">
                                                <i class="align-middle mdi mdi-information-outline mdi-lg"></i>
                                                {{ trans('translation.org-info-msg') }}
                                            </span>
                                            <div class=" d-flex justify-content-end">
                                                <a class="btn btn-secondary"
                                                onclick="return setLoading(true);"
                                                    href="{{ route('organizations.edit', $organization->id) }}">{{ trans('translation.more') }}</a>
                                                <a class="btn btn-secondary mx-1"
                                                onclick="return setLoading(true);"
                                                    href="{{ route('organization.settings', $organization->id) }}">{{ trans('translation.more') }} (New)</a>
                                                <button class="btn btn-danger mx-1 deleteOrganization"
                                                    data-organization-id="{{ $organization->id }}">{{ trans('translation.delete') }}</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end of col of all organizations-->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End div row of all organizations  -->

    @include('admin.organizations.modals.add-organization')
    {{-- @include('admin.organizations.modals.add-service') --}}

    @push('after-scripts')
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                localStorage.setItem('goBackHrefOrganizations',location.href);

                //triggered when modal is about to be shown
                $('#addservices').on('show.bs.modal', function(e) {
                    //get data-id attribute of the clicked element
                    var organizationId = $(e.relatedTarget).data('organization-id');
                    var used_service = $(e.relatedTarget).attr('data-services');
                    var service_ids = used_service.split(',');
                    //populate the textbox
                    $(e.currentTarget).find('#hidden_organization_id').val(organizationId);
                    $('#select_organization_service option').each(function() {
                        $(this).removeClass('d-none');
                        if (service_ids.includes($(this).val()) && $(this).val() != "choose_one") {
                            $(this).addClass('d-none');
                        }
                    });

                });
                $('.selectPicker').selectpicker({
                    width: '100%',
                });

                $('.deleteOrganization').click(function(e) {
                    e.preventDefault();
                    let deleteBtn = $(this);
                    Swal
                        .fire(window.deleteWarningPopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                var organization_id = $(this).attr('data-organization-id');
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "DELETE",
                                    url: "{{ url('organizations') }}/" + organization_id,
                                    dataType: "json",
                                    success: function(response) {
                                        let $card = deleteBtn.closest('.organizarionCard');
                                        $card.remove();
                                        e.preventDefault();
                                        Toast.fire({
                                            icon: "success",
                                            title: "{{trans('translation.delete-successfully') }}"
                                        });
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        Toast.fire({
                                            icon: jqXHR.responseJSON['alert-type'] ,
                                            title: jqXHR.responseJSON.message
                                        });
                                    },
                                });
                            }
                        });
                })
            })
        </script>
    @endpush
@endsection
