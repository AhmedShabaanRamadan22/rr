@extends('layouts.master')
@section('title', trans('translation.interview-standard-orders'))

@section('content')
    @push('styles')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- SelectPicker -->
        <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
        <!-- Sweet Alert -->
        <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>

    @endpush
    @php
        $i = 0;
  $is_chairman = auth()->user() != null ? auth()->user()->hasRole('organization chairman') : false;

//
                $disabled = '';
                $html = '<div><select class="selectpicker status-select w-25"' . $disabled . ' name="service_id" style="background:' . $order->status->color . '" data-status-id="' . $order->status_id . '" data-order-id="' . $order->id . '" onchange="changeSelectPicker(this)"  >';
                foreach ($statuses as $status) {
                    $span = " data-content=\"<span class='badge ' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
                    $html .= '<option value="' . $status->id . '" ' . ($status->id == ($order->interview_status->id ?? 0) ? 'selected' : '') . ' ' . $span . ' data-note-required="' . ($status->is_note_required) . '" >' . $status->name . '</option>';
                }
                $html .= "</select></div>";
    @endphp
    <x-breadcrumb title="{{ $order->code }}">
        <li class="breadcrumb-item"><a
                href="{{ route('order-interviews.index') }}">{{ trans('translation.order-interviews') }}</a></li>
    </x-breadcrumb>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <x-row-info id="id"
                            label="{{ trans('translation.facility-owner-name') }}">{{$order->user->name ?? '-'}}</x-row-info>
                <x-row-info id="id"
                            label="{{ trans('translation.facility-name') }}">{{ $order->facility->name ?? '-'}}</x-row-info>

                <x-row-info id="id" label="{{ trans('translation.status') }}">{!! $html !!}</x-row-info>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header flex">
            <div class="card-title">{{trans('translation.order-interview')}}</div>
            <div class="text-end">
                <a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 "
                   href="{{route('order-interviews.edit',$order->id)}}">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>
            </div>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-6">

                    @component('components.row-info', ['id'=> 'interview_standard','label' => trans('translation.interview-standard') , 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5'])
                        {{ trans('translation.score') }}
                    @endcomponent
                    @forelse ($order->interview_standard_orders as $interview_standard_order)

                        @component('components.row-info', ['id'=> 'interview_standard_'.$interview_standard_order->id,'label' => $interview_standard_order->interview_standard->name , 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5'])
                            {{$interview_standard_order->score}} / {{$interview_standard_order->max_score}}
                        @endcomponent
                    @empty

                    @endforelse
                    @component('components.row-info', ['id'=> 'interview_standard','label' => trans('translation.interview_total_score') , 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5'])
                        {{ $order->interview_total_score_before_bonus }}
                    @endcomponent
                </div>
                <div class="col-6">

                    <x-row-info id="id" label="{{ trans('translation.code') }}">{{$order->code}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.organization-name') }}">{{$order->organization->name}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.service-name') }}">{{$order->organization_service->service_name}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.user-name') }}">{{$order->user->name ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.facility-name') }}">{{ $order->facility->name ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.facility-user-phone') }}">{{  $order->user->phone  ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.facility-user-national_id') }}"> {{  $order->user->national_id  ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.registration-number') }}">{{  $order->facility->registration_number  ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.version-date') }}">{{ $order->facility->version_date . ' (' . \Carbon\Carbon::parse($order->facility->version_date)->diffForHumans() .')'  ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.license') }}">{{ $order->facility->license ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.license-expired') }}">{{ $order->facility->license_expired ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.capacity') }}">{{ $order->facility->capacity ?? '-'}}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.tax-certificate') }}">{{ $order->facility->tax_certificate ?? '-'}}</x-row-info>
                    <!-- <x-row-info id="id"
                                label="{{ trans('translation.chefs-number') }}">{{ $order->facility->chefs_number ?? '-'}}</x-row-info> -->
                    <x-row-info id="id"
                                label="{{ trans('translation.kitchen-space') }}">{{ $order->facility->kitchen_space ?? '-' }}</x-row-info>
                    <x-row-info id="id"
                                label="{{ trans('translation.employee-number') }}">{{  $order->facility->employee_number ?? '-' }}</x-row-info>

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
    <script>
        function goBack() {
            location.href = localStorage.getItem('goBackHref');
        }
    </script>
    <script>

        $(document).ready(function() {
            $('.selectPicker').selectpicker({
                width: '100%',
            });
        });

        function changeSelectPicker(select) {
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
                        url: "{{ url('order-interviews-status') }}",
                        data: {
                            status_id: select.val(),
                            order_id: select.attr('data-order-id'),
                            old_status_id: select.attr('data-status-id'),
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                            window.location.reload();
                            Toast.fire({
                                icon: "success",
                                title: "{{ trans('translation.Updated successfuly') }}"
                            });
                        },
                        error:function(response, jqXHR, xhr) {
                            window.location.reload();
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


@endsection
