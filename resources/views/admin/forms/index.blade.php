@extends('layouts.master')
@section('title', __('Forms'))

@push('styles')
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

    <!-- sweetalert2 -->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ trans('translation.forms') }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ trans('translation.forms') }}</li>
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ trans('translation.home') }}</a></li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-md-12  col-xl-12">
        <div class="card ">
            <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                <div class="row">
                    <h3 class="card-title col-6">{{ trans('translation.all-forms') }} </h3>
                    <div class="col-6 text-end">
                        <div class="d-flex gap-2 text-end justify-content-end">
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addforms"><i
                                    class="mdi mdi-account-group-outline align-baseline me-1"></i>
                                {{ trans('translation.add-new-form') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class=" mb-3">
                    @include('admin.forms.components.form-card')

                    @include('admin.forms.modals.add-section')
                    @include('admin.forms.modals.edit-section')
                    @include('admin.forms.modals.edit-form')

                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.forms.modals.add-form')

@push('after-scripts')
        <!-- SelectPicker -->
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                localStorage.setItem('goBackHref',location.href);
                $('#organization_id').on('change',function(){
                    let emptyServiceFlag = retrieveSelect('#organization_service_id');
                    let emptycategoryFlag = retrieveSelect('#organization_category_id');
                })

                $('.form-collapser').on('click', function() {
                    $('#form-collapser-' + $(this).attr('data-form-id')).click();
                });
                $('.deleteSectionBtn').on('click', function() {
                    var section_id = $(this).attr('data-section-id');
                    var form_id = $(this).attr('data-form-id');
                    var card_section = $(this).closest('.card-section');
                    Swal
                        .fire(window.deleteWarningPopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                var question_id = $(this).attr('data-question-id');
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: 'DELETE',
                                    url: "{{ url('forms') }}/" + form_id + "/sections/" + section_id,
                                    success: function(response) {
                                        Toast.fire({
                                                icon: "success",
                                                title: response.message
                                            });
                                        card_section.remove();
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        // alert(jqXHR.responseJSON.message);
                                        Toast.fire({
                                            icon: "error",
                                            title: jqXHR.responseJSON.message
                                        });
                                    },
                                });
                            }
                        });
                })
                $('.formDeleteButton').on('click', function() {
                    var form_id = $(this).attr('data-form-id');
                    var card_form = $(this).closest('.card-form');
                    Swal
                        .fire(window.deleteWarningPopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                var question_id = $(this).attr('data-question-id');
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: 'DELETE',
                                    url: "{{ url('/forms') }}" + "/" + form_id,
                                    success: function(response) {
                                        card_form.remove();
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        // alert(jqXHR.responseJSON.message);
                                        Toast.fire({
                                            icon: "error",
                                            title: jqXHR.responseJSON.message
                                        });
                                    },
                                });
                            }
                        });
                })
            });

            function formSubmitted(e,id) {
                sessionStorage.setItem("offsetY", JSON.stringify(window.scrollY));
                // e.preventDefault()
            }

            function retrieveSelect(selector){
                let organization_id = $('#organization_id').val();
                let emptyFlag = true
                $(selector + ' option').each(function(){
                    $(this).hide();
                    if($(this).attr('data-organization-id') == organization_id){
                        $(this).show();
                        emptyFlag = false;
                    }
                })

                if(emptyFlag){
                    $(selector ).attr('title','{{trans("translation.no-data")}}');
                    $(selector).prop('disabled',true);
                    // $(selector).prop('required',false);
                }else{
                    $(selector ).attr('title',"{{trans('translation.choose-one')}}");
                    $(selector).prop('disabled',false);
                    // $(selector).prop('required',true);

                }

                $(selector).selectpicker('destroy').selectpicker({});

                return emptyFlag;
            }
        </script>
    @endpush
@endsection
