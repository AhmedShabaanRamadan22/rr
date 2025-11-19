@extends('layouts.master')
@section('title',__('Service'))

@push('styles')

<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

@endpush
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ trans('translation.services') }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ trans('translation.services') }}</li>
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
                        <h3 class="card-title col-6">{{ trans('translation.all-service') }} </h3>
                        <div class="col-6 text-end">
                            <div class="d-flex gap-2 text-end justify-content-end">
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addservices"><i
                                        class="mdi mdi-plus align-baseline me-1"></i>
                                    {{ trans('translation.add-new-services') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-cols-lg-3 mb-3">
                        @forelse ($services as $service)
                        @include('admin.services.components.service-card')
                        @empty
                        <div class="text-center">
                            <p>
                                {{trans('translation.no-data')}}
                            </p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
</div>
{{-- <div class="row">
    <div class="col-md-12  col-xl-12">
        <div class="card ">
            <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                <h3 class="card-title">{{trans('translation.add-new-services')}}</h3>
                <div class="card-options">
                    <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    <!-- <a href="javascript:void(0)" class="card-options-remove" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a> -->
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{route('services.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3">
                            <div class=" row mb-4">
                                <div class="col-md-12">
                                    <label for="inputName" class=" form-label">{{trans('translation.service-name')}}</label>
                                    <input type="text" class="form-control" id="inputName" name="name" placeholder="{{trans('translation.service-name')}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class=" row mb-4">
                                <div class="col-md-12">
                                    <label for="inputPrice" class="form-label">{{trans('translation.price')}}</label>
                                    <input type="number" step="0.01" class="form-control" id="inputPrice" name="price" placeholder="{{trans('translation.price')}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 my-auto">
                            <button class="btn btn-primary">{{trans('translation.add')}}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div> --}}
<!-- end page title -->
{{-- <h3 class="p-3 mb-3">{{trans('translation.all-service')}}</h3> --}}
<!-- ROW-2 -->
{{-- <div class="row mb-3">
    @forelse ($services as $service)
    @include('admin.services.components.service-card')
    @empty
    <div class="text-center">
        <p>
            No data
        </p>
    </div>
    @endforelse
</div> --}}
<!-- END ROW-2 -->

@include('admin.services.modals.edit-service')

@component('modals.add-modal-template',['modalName'=>'services'])
    @foreach ($columnInputs as $column => $type)
        @component('components.inputs.' . $type .'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3']) @endcomponent
    @endforeach
@endcomponent

@push('after-scripts')
<script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script>
    $(document).ready(function() {
        //triggered when modal is about to be shown
        $('#editService').on('show.bs.modal', function(e) {
            //get data-id attribute of the clicked element
            var serviceBtn = $(e.relatedTarget);
            var service_id = serviceBtn.attr('data-service-id');
            var service_name = serviceBtn.attr('data-service-name');
            var service_price = serviceBtn.attr('data-service-price');
            //populate the textbox
            $(e.currentTarget).find('#form-edit-service').attr('action', $(e.currentTarget).find('#form-edit-service').attr('action') + '/' + service_id);
            $(e.currentTarget).find('#inputName').val(service_name);
            $(e.currentTarget).find('#inputPrice').val(service_price);
        });

        $('.deleteService').click(function(e) {
            e.preventDefault();
            let deleteBtn = $(this);
            Swal
                .fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        var service_id = $(this).attr('data-service-id');
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('services') }}/" + service_id,
                            dataType: "json",
                            success: function(response, jqXHR, xhr) {
                                let $card = deleteBtn.closest('.serviceCard');
                                $card.remove();
                                e.preventDefault();
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.Service was deleted successfuly') }}"
                                });
                            },
                            error: function(response, jqXHR, xhr) {
                                Toast.fire({
                                    icon: "error",
                                    title: "{{trans('translation.Service is connected with organization') }}"
                                });
                            },
                        });
                    }
                });
        })
    });
</script>
@endpush
@endsection
