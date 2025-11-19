@extends('layouts.master')
@section('title',__('Support'))

@push('styles')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<!-- SelectPicker -->
<link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

@endpush
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ trans('translation.support') }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ trans('translation.support') }}</li>
                    <li class="breadcrumb-item"><a href="{{route('root')}}">{{ trans('translation.home') }}</a></li>
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
                <h3 class="card-title">{{trans('translation.support')}}</h3>
                <div class="card-options">
                    <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    <!-- <a href="javascript:void(0)" class="card-options-remove" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a> -->
                </div>
            </div>
            <div class="card-body">

                @include('admin.supports.components.filters')
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<!-- ROW-2 -->
<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans('translation.all-supports') }}</h3>
            </div>
            @component('admin.supports.components.supports-table', ['columns'=>$columns])@endcomponent
        </div>
    </div>
</div>
<!-- END ROW-2 -->



@push('after-scripts')
@vite(['resources/js/bootstrap.js'])
<!-- SelectPicker -->
<script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

<script>
     $(document).ready(function(){
        localStorage.setItem('goBackHref',location.href);
        window.Echo.channel('ModelCRUD-changes').listen('.Support-changes',function(data) {
                window.datatable.ajax.reload();
            });
    })
    function changeSupportSelectPicker(select) {

        var select = $(select);
        Swal
            .fire(window.confirmChangeStatusPopupSetup).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ url('admin/supports-status') }}",
                        data: {
                            status_id: select.val(),
                            support_id: select.attr('data-support-id')
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                                window.datatable.ajax.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.Updated successfuly') }}"
                                });
                            },
                            error:function(response, jqXHR, xhr) {
                                window.datatable.ajax.reload();
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.You dont have permission') }}"
                                });
                            },
                    });
                } else {
                    select.selectpicker('destroy');
                    select.val(select.attr('data-status-id'));
                    select.selectpicker({
                        width: '100%',
                    });
                }
            });
    }
</script>
@endpush
@endsection
