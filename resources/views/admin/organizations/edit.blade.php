@extends('layouts.master')
@section('title', __('Organization'))
@push('styles')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/monolith.min.css') }}" />
    <!-- 'monolith' theme -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .icon-bigger{
            font-size: 25px;
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
            {{-- <div class="card-header" class="card-options-collapse">
                <div class="row justify-content-around text-center">
                    <div class="col-lg-12">
                        <h3 class="card-title">{{ trans('translation.edit-organization') }}</h3>
                    </div>
                </div>
            </div> --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2" data-simplebar-track="primary" data-simplebar style="height: 700px;">
                        {{-- Menu --}}
                        <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center"
                            role="tablist" aria-orientation="vertical">
                                @foreach($tabs = [
                                    [ 'name'=> 'information', 'icon' => 'ri-profile-line',],
                                    [ 'name'=> 'services', 'icon' => 'ri-service-line',],
                                    [ 'name'=> 'reason-dangers', 'icon' => 'ri-alert-line',],
                                    [ 'name'=> 'fines-bank', 'icon' => 'ri-bank-line',],
                                    [ 'name'=> 'operations', 'icon' => 'ri-customer-service-2-line',],
                                    [ 'name'=> 'categories', 'icon' => 'ri-checkbox-blank-circle-line',],
                                    [ 'name'=> 'sector-setup', 'icon' => 'ri-grid-line',],
                                    [ 'name'=> 'countries', 'icon' => 'ri-flag-line',],
                                    [ 'name'=> 'meal-preparation', 'icon' => 'ri-restaurant-line',],
                                    [ 'name'=> 'question-bank', 'icon' => 'mdi mdi-office-building-marker-outline',],
                                    [ 'name'=> 'contract', 'icon' => 'ri-hand-coin-line',],
                                    [ 'name'=> 'providors', 'icon' => 'ri-shield-line',],
                                    {{-- ?? commented to optimize edit organization page --}}
                                    // [ 'name'=> 'employees-contracts', 'icon' => 'ri-newspaper-line',],
                                ] as  $column )
                                    @component('admin.organizations.components.organization-bill',['columnName' => $column['name'] , 'billIcon' => $column['icon'], 'parent' => 'organization'])@endcomponent
                                @endforeach
                        </div>
                    </div>
                    <div class="col-lg-10" data-simplebar style="height: 700px;">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            @foreach($tabs as $column)
                                @component('admin.organizations.components.pill-content', ['key' => $column['name']])
                                    @include('admin.organizations.components.bills-content.'.$column['name'].'-organization-content')
                                @endcomponent
                            @endforeach
                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div><!-- end card-body -->
        </div>
        <!--end card-->
    </div>
    @include('admin.organizations.modals.edit-question-bank-organization')
    @include('admin.organizations.modals.edit-fine-organization')
    {{--
    @include('admin.organizations.modals.edit-food-weight')
    @include('admin.organizations.modals.add-menu')
    @include('admin.organizations.modals.add-food-weights')
    @include('admin.organizations.modals.add-meal-by-nationality')
    @include('admin.organizations.modals.add-stage')
    @include('admin.organizations.modals.sort-organization-stages')
    @include('admin.organizations.modals.edit-nationality-organizations')
    --}}

    @include('admin.organizations.modals.add-nationality')

    @include('admin.organizations.modals.add-news')
    @include('admin.organizations.modals.add-sector')
    @include('admin.organizations.modals.add-ticket')
    @include('admin.organizations.modals.add-support-water')
    @include('admin.organizations.modals.add-support-food')
    @include('admin.organizations.modals.add-classification')
    @include('admin.organizations.modals.add-reason-danger')
    @include('admin.organizations.modals.add-fine')
    @include('admin.organizations.modals.add-category')
    {{-- @include('admin.organizations.modals.add-meal') --}}
    @include('admin.organizations.modals.add-providor')
    @include('admin.organizations.modals.add-question-bank-organization')
    @include('admin.organizations.modals.add-signed-contract')
    {{-- ?? commented to optimize edit organization page --}}
    {{-- @include('admin.organizations.modals.add-employee-contracts') --}}
    {{-- @include('admin.organizations.modals.add-country') --}}
    {{-- @include('admin.organizations.modals.add-service') --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = JSON.parse(localStorage.getItem('organization'))?.tab;
            const activeInformationTab = JSON.parse(localStorage.getItem('information'))?.tab;
            const activeMealsTab = JSON.parse(localStorage.getItem('meals'))?.tab;
            const activeSectorTab = JSON.parse(localStorage.getItem('sectors'))?.tab;
            const activeOperationTab = JSON.parse(localStorage.getItem('operations'))?.tab;
            // if it was null we will set it to first one

            checkNullTap(activeTab, 'custom-v-pills-information-tab', `custom-v-pills-${activeTab}-tab`)
            checkNullTap(activeInformationTab, 'about-tab', `${activeInformationTab}-tab`)
            checkNullTap(activeMealsTab, 'border-tab-food-weights', `border-tab-${activeMealsTab}`)
            checkNullTap(activeSectorTab, 'border-tab-classifications', `border-tab-${activeSectorTab}`)
            checkNullTap(activeOperationTab, 'border-tab-tickets', `border-tab-${activeOperationTab}`)

        });

        const checkNullTap = (sessionTap, defaultTap, clickTap) => {
            if (sessionTap == null) {
                openTab(document.getElementById(defaultTap));
            } else {
                openTab(document.getElementById(clickTap))
            }
        }

        const setActiveTab = (tab, parent) => {
            let state = JSON.parse(localStorage.getItem(parent))
            state = {
                ...state,
                tab: tab
            };
            localStorage.setItem(parent, JSON.stringify(state));
            localStorage.setItem('goBackHref',location.href);

        }


        const openTab = (elem) => {
            elem?.click();
        }
        document.addEventListener('DOMContentLoaded', function() {
            let tabId = localStorage.getItem('activeReason');
            if(!tabId){
                tabId = 1;
            }
            document.getElementById('operationTypeTab'+tabId).click();
            // const form = document.querySelector(`form[action="{{url('admin/store-reason-danger')}}"]`);
        })
        const activateOperationType = (id)=>{
            localStorage.setItem('activeReason', id);
        }
    </script>

    @push('after-scripts')
        @vite(['resources/js/bootstrap.js'])

        {{-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> --}}
        {{-- <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script> --}}
        <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        <script>
            // reload the page then fire the toast
            if (localStorage.getItem('reloadPending') != null) {
                let msg = localStorage.getItem('reloadPending');
                localStorage.removeItem('reloadPending');
                // Display the toast after the reload
                Toast.fire({
                    icon: "success",
                    title: msg
                });
            }
        </script>
        <script>
            $(document).ready(function() {
                //triggered when modal is about to be shown
                $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust()
                        .responsive.recalc();
                });

                $(document.body).on('click', '.edit_services', function(e) {
                    let deleteBtn = $(this);
                    Swal
                        .fire(window.deleteWarningPopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                var organization_service_id = $(this).val();
                                var service_id = $(this).attr('data-service-id');
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "DELETE",
                                    url: '{{ url('/organization-services') }}/' +
                                        organization_service_id,
                                    dataType: "json",
                                    success: function(response, jqXHR, xhr) {
                                        // let card = deleteBtn.closest('.serviceCard');
                                        // card.remove();

                                        // let addButton = $('#add-services-button').attr(
                                        //     'data-services');
                                        // $('#select_organization_service option [value=' +
                                        //     service_id + ']').removeClass(
                                        //     'd-none')

                                        // Toast.fire({
                                        //     icon: "success",
                                        //     title: "{{ trans('translation.The Service Deleted successfully') }}"
                                        // });
                                        localStorage.setItem('reloadPending', "{{ trans('translation.The Service Deleted successfully') }}");
                                        window.location.reload();
                                        // setLoading(false);
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        Toast.fire({
                                            icon: "error",
                                            title: jqXHR.responseJSON.message
                                        });
                                    },
                                });
                            }
                        });
                })
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

                    $('.selectPicker').selectpicker({
                        width: '100%',
                    });
                });
                $(document.body).on('click', '.deleteNew', function(e) {
                    let deleteBtn = $(this);
                    Swal
                        .fire(window.deleteWarningPopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                var organization_news_id = $(this).attr('data-news-id');
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "DELETE",
                                    url: '{{ url('/organization-news') }}/' +
                                        organization_news_id,
                                    dataType: "json",
                                    success: function(response, jqXHR, xhr) {
                                        let card = deleteBtn.closest('.newsCard');
                                        card.remove();

                                        Toast.fire({
                                            icon: "success",
                                            title: "{{ trans('translation.The New Deleted successfully') }}"
                                        });
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        Swal
                                            .fire({
                                                title: "{{ trans('translation.Warning') }}",
                                                text: jqXHR.responseJSON.message,
                                                icon: "error",
                                                showConfirmButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonText: "{{ trans('translation.OK ') }}"
                                            })
                                    },
                                });
                            }
                        });
                })

                // can be replace with adding organization->id in the model as hidden attribute
                $('#addNews').on('show.bs.modal', function(e) {
                    var organizationId = $(e.relatedTarget).data('organization-id');
                    $(e.currentTarget).find('#hidden_organization_id_news').val(organizationId);
                });

                $('#organization_color').on('click', function() {
                });

                $('#inputLogo').change(function() {
                    var input = this;
                    var url = $(this).val();
                    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                    if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" ||
                            ext == "jpg")) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#img').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        $('#img').attr('src', '/assets/no_preview.png');
                    }
                });

            })
        </script>
        @if (session()->get('errors'))
            <script>
                // toastr.error("{{ session()->get('errors')->first() }}");
                Toast.fire({
                    icon: "error",
                    title: "{{ session()->get('errors') }}"
                });
            </script>
        @endif
    @endpush
@endsection
