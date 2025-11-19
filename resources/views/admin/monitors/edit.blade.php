@extends('layouts.master')
@section('title', trans('translation.monitor'))

@push('styles')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush

@section('content')

<x-breadcrumb title="{{ $monitor->user->name }}">
    <li class="breadcrumb-item"><a href="{{ route('monitors.index') }}">{{ trans('translation.monitor') }}</a></li>
</x-breadcrumb>

<div class="row">
    <div class="col-xxl-12">
        <div class="card">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-4 mb-4">
                        <label for="input_monitor" class=" form-label">{{ trans('translation.monitor-information') }}</label>
                        @component('components.data-row', ['id'=>'monitor-name']) {{$monitor->user->name}} @endcomponent
                        @component('components.data-row', ['id'=>'code']) {{$monitor->code}} @endcomponent
                        @component('components.data-row', ['id'=>'position']) {{$monitor->user->monitor_position}} @endcomponent
                        @component('components.data-row', ['id'=>'phone'])
                            <a href="{{'https://api.whatsapp.com/send?phone=966' . $monitor->user->phone}}" dir="ltr" target="_blank">{{$monitor->user->phone_code . ' ' . $monitor->user->phone}}</a>
                        @endcomponent
                    </div>
                    <div class="col-md-8 mb-2">
                        <div class="mb-4">
                            <label for="input_monitor" class="mb-3 form-label">{{ trans('translation.manage-sectors') }}</label>
                            <div class="row mb-2 col-12">
                                <button type="button" class="btn btn-sm col-auto mx-1 btn-outline-primary" data-bs-target="#addNewMonitorSector" data-bs-toggle="modal" data-monitor-id="{{$monitor->id}}">
                                    <i class="mdi mdi-plus"></i> {{ trans('translation.add') }}
                                </button>
                                <button type="button" class="btn btn-sm col-auto mx-1 btn-outline-danger" data-bs-target="#deleteMonitorSector" data-bs-toggle="modal" data-monitor-id="{{$monitor->id}}">
                                    <i class="mdi mdi-trash-can-outline"></i> {{ trans('translation.delete') }}
                                </button>
                                <button type="button" class="btn btn-sm col-auto mx-1 btn-outline-info" data-bs-target="#moveMonitorSector" data-bs-toggle="modal" data-monitor-id="{{$monitor->id}}">
                                    <i class="mdi mdi-arrow-right-thin"></i> {{ trans('translation.move') }}
                                </button>
                                {{-- <button type="button" class="btn col-auto mx-1 btn-outline-info" data-bs-target="#swapMonitorSector" data-bs-toggle="modal" data-monitor-id="{{$monitor->id}}">
                                    <i class="mdi mdi-swap-horizontal"></i> {{ trans('translation.swap') }}
                                </button> --}}
                            </div>
                        </div>
                        <label for="input_monitor" class="mb-2 form-label">{{ trans('translation.assigned-sectors') }}</label>
                        <div class="row">
                            @if (count($monitor->monitor_order_sectors) != 0)
                                @foreach ($sectors = explode(',',$assigned_sectors) as $sector)
                                <div class="col-md-6 p-1">
                                    <div class="bg-light rounded m-1 p-3 text-center fw-bold text-primary">{{$sector}}</div>
                                </div>
                                @endforeach
                            @else
                                <div>{{trans('translation.no-data')}}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <button class="btn btn-secondary px-5 mx-2" type="button" onclick="goBack()"
                            id="backButton">{{ trans('translation.back') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.monitors.modals.add-monitor-sector')
@include('admin.monitors.modals.delete-monitor-sector')
@include('admin.monitors.modals.move-monitor-sector')
{{-- @include('admin.monitors.modals.swap-monitor-sector') --}}

@endsection
@push('after-scripts')
    <script>
        function goBack() {
            location.href=localStorage.getItem('goBackHref');
        }
        $(document).ready(function() {
            $('.selectpicker').selectpicker({});
        }); // end document ready
    </script>
@endpush
