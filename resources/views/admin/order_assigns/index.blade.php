@extends('layouts.master')
@section('title',__('Order assigns'))

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
            <h4 class="mb-0">{{ trans('translation.orders-assigns') }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ trans('translation.order-assigns') }}</li>
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
                <h3 class="card-title">{{trans('translation.order-assigns')}}</h3>
                <div class="card-options">
                    <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    <!-- <a href="javascript:void(0)" class="card-options-remove" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a> -->
                </div>
            </div>
            <div class="card-body">

                @include('admin.order_assigns.components.filters')
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<h3 class="p-3 mb-3">{{trans('translation.all-order-assigns')}}</h3>
<!-- ROW-2 -->
<div class="row">
    <div class="col">
        <div class="card">
            @include('admin.order_assigns.components.order-assigns-table')
        </div>
    </div>
</div>
<!-- END ROW-2 -->

@include('admin.order_assigns.modals.assign-order-to-user-modal')
@include('admin.order_assigns.modals.assign-multiple-orders-to-user-modal')
@include('admin.order_assigns.modals.unassign-multiple-orders-to-user-modal')

@push('after-scripts')

<!-- SelectPicker -->
<script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

<script>
    $(document).ready(function() {

        $('.selectPicker').selectpicker({
            width: '100%',
        });


    }); // end document ready
</script>
@endpush
@endsection
